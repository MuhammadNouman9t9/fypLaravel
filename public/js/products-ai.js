/**
 * SafeNest Products page — AI features via FastAPI ai_engine (port 8001).
 */
(function () {
    'use strict';

    const AI_BASE = 'http://127.0.0.1:8001/ai';

    const STORAGE_CHAT_SESSION = 'safenest_ai_chat_session';
    const STORAGE_FRAUD_USER = 'safenest_ai_fraud_user';

    function $(sel, root) {
        return (root || document).querySelector(sel);
    }

    function ensureSessionId() {
        let id = sessionStorage.getItem(STORAGE_CHAT_SESSION);
        if (!id) {
            id = typeof crypto !== 'undefined' && crypto.randomUUID
                ? crypto.randomUUID()
                : 'sess-' + Date.now() + '-' + Math.random().toString(36).slice(2);
            sessionStorage.setItem(STORAGE_CHAT_SESSION, id);
        }
        return id;
    }

    function ensureFraudUserId() {
        let id = sessionStorage.getItem(STORAGE_FRAUD_USER);
        if (!id) {
            id =
                'u' +
                (typeof crypto !== 'undefined' && crypto.randomUUID
                    ? crypto.randomUUID().replace(/-/g, '').slice(0, 12)
                    : String(Date.now()));
            sessionStorage.setItem(STORAGE_FRAUD_USER, id);
        }
        return id;
    }

    function capitalizeWords(str) {
        if (!str) return '';
        return str
            .split(/[\s_]+/)
            .map((w) => w.charAt(0).toUpperCase() + w.slice(1).toLowerCase())
            .join(' ');
    }

    function mapNeighborhoodRisk(val) {
        const m = {
            very_safe: 'Low',
            safe: 'Low',
            moderate: 'Moderate',
            risky: 'High',
            high_crime: 'High',
        };
        return m[val] || 'Moderate';
    }

    function mapOccupancy(val) {
        const m = {
            always_occupied: 'Family',
            mostly_occupied: 'Family',
            partially_occupied: 'Roommates',
            rarely_occupied: 'Single professional',
            vacant: 'Vacant',
        };
        return m[val] || 'Family';
    }

    function getForm(aiForm) {
        const fd = new FormData(aiForm);
        const propertyType = fd.get('property_type') || 'apartment';
        const homeSize = parseInt(String(fd.get('property_size') || '1200'), 10);
        const budget = parseFloat(String(fd.get('budget') || '0')) || 0;
        const entryPoints = parseInt(String(fd.get('entry_points') || '2'), 10);
        const exitPoints = parseInt(String(fd.get('exit_points') || '1'), 10);
        const neighborhoodRaw = String(fd.get('neighborhood_profile') || 'moderate');
        const occupancyRaw = String(fd.get('occupancy_pattern') || 'mostly_occupied');
        const hasSecurity = fd.get('has_security_system') === '1';
        const prevIncidents = fd.get('previous_incidents') === '1';

        return {
            property_type: capitalizeWords(propertyType.replace(/_/g, ' ')),
            home_size_sqft: Number.isFinite(homeSize) ? homeSize : 1200,
            budget,
            entry_points: Number.isFinite(entryPoints) ? entryPoints : 2,
            exit_points: Number.isFinite(exitPoints) ? exitPoints : 1,
            neighborhood_risk: mapNeighborhoodRisk(neighborhoodRaw),
            occupancy: mapOccupancy(occupancyRaw),
            has_existing_security: hasSecurity,
            previous_incidents: prevIncidents,
            neighborhood_raw: neighborhoodRaw,
        };
    }

    function formatTimeOfDay() {
        const d = new Date();
        const h = String(d.getHours()).padStart(2, '0');
        const m = String(d.getMinutes()).padStart(2, '0');
        return h + ':' + m;
    }

    function setButtonLoading(btn, loading, labelIdle) {
        if (!btn) return;
        if (loading) {
            btn.dataset.prevHtml = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML =
                '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>' +
                'Loading...';
        } else {
            btn.disabled = false;
            if (btn.dataset.prevHtml) {
                btn.innerHTML = btn.dataset.prevHtml;
                delete btn.dataset.prevHtml;
            } else if (labelIdle) {
                btn.textContent = labelIdle;
            }
        }
    }

    function findAiPanels() {
        const row = $('.card-body .row.g-3.mt-1');
        if (!row || row.children.length < 3) return null;
        return {
            recommendations: row.children[0],
            safety: row.children[1],
            fraud: row.children[2],
        };
    }

    function panelBox(col) {
        return col ? col.querySelector('.border.rounded-3.p-3') : null;
    }

    async function postJson(path, body) {
        const res = await fetch(AI_BASE + path, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
            body: JSON.stringify(body),
        });
        const text = await res.text();
        let data;
        try {
            data = text ? JSON.parse(text) : {};
        } catch {
            throw new Error(text.slice(0, 200) || 'Invalid JSON response');
        }
        if (!res.ok) {
            let msg = data.detail || data.message || res.statusText || 'Request failed';
            if (Array.isArray(msg)) {
                msg = msg
                    .map(function (x) {
                        return x.msg || JSON.stringify(x);
                    })
                    .join('; ');
            } else if (typeof msg !== 'string') {
                msg = JSON.stringify(msg);
            }
            throw new Error(msg);
        }
        return data;
    }

    function renderRecommendations(box, data, error) {
        if (!box) return;
        if (error) {
            box.innerHTML =
                '<div class="small text-secondary mb-1">AI Product Recommendations</div>' +
                '<div class="alert alert-danger small mb-0 py-2">' +
                escapeHtml(error) +
                '</div>';
            return;
        }
        const items = data.recommendations || [];
        let html =
            '<div class="small text-secondary mb-1">AI Product Recommendations</div>' +
            '<div class="fw-semibold mb-2 text-success">Active</div>' +
            '<div class="small text-secondary mb-2">Powered by the SafeNest AI engine (content + collaborative).</div>';
        if (!items.length) {
            html += '<p class="small mb-0">No recommendations returned.</p>';
        } else {
            html += '<ul class="list-unstyled small mb-0">';
            items.forEach(function (r) {
                html +=
                    '<li class="mb-2 pb-2 border-bottom border-light">' +
                    '<div class="fw-semibold">' +
                    escapeHtml(r.name || r.product_id) +
                    '</div>' +
                    '<div class="text-secondary">' +
                    escapeHtml(r.reason || '') +
                    '</div>' +
                    '<div class="text-muted mt-1">Score: ' +
                    (typeof r.score === 'number' ? r.score.toFixed(2) : '—') +
                    '</div>' +
                    '</li>';
            });
            html += '</ul>';
        }
        box.innerHTML = html;
    }

    function renderSafety(box, data, error) {
        if (!box) return;
        if (error) {
            box.innerHTML =
                '<div class="small text-secondary mb-1">Home Safety Score</div>' +
                '<div class="alert alert-danger small mb-0 py-2">' +
                escapeHtml(error) +
                '</div>';
            return;
        }
        const score = data.score;
        const classification = data.classification || '';
        const breakdown = data.breakdown || {};
        const recs = data.recommendations || [];

        let breakdownHtml = '<ul class="list-unstyled small mb-2">';
        Object.keys(breakdown).forEach(function (k) {
            breakdownHtml +=
                '<li><span class="text-secondary">' +
                escapeHtml(k.replace(/_/g, ' ')) +
                ':</span> ' +
                Number(breakdown[k]).toFixed(1) +
                '</li>';
        });
        breakdownHtml += '</ul>';

        let recHtml = '<ul class="small mb-0 ps-3">';
        recs.forEach(function (r) {
            recHtml += '<li>' + escapeHtml(r) + '</li>';
        });
        recHtml += '</ul>';

        box.innerHTML =
            '<div class="small text-secondary mb-1">Home Safety Score</div>' +
            '<div class="fw-semibold mb-1">' +
            escapeHtml(String(score)) +
            '/100 — ' +
            escapeHtml(classification) +
            '</div>' +
            '<div class="small fw-semibold mb-1">Breakdown</div>' +
            breakdownHtml +
            '<div class="small fw-semibold mb-1">Tips</div>' +
            recHtml;
    }

    function renderFraud(box, data, error) {
        if (!box) return;
        if (error) {
            box.innerHTML =
                '<div class="small text-secondary mb-1">Fraud Detection Monitor</div>' +
                '<div class="alert alert-danger small py-2">' +
                escapeHtml(error) +
                '</div>' +
                '<button type="button" class="btn btn-sm btn-outline-secondary mt-2" id="btn-ai-fraud-retry">Try again</button>';
            const retry = $('#btn-ai-fraud-retry', box);
            if (retry) {
                retry.addEventListener('click', function () {
                    runFraudCheck(aiFormGlobal, box);
                });
            }
            return;
        }
        const score = data.fraud_score;
        const level = data.alert_level || '';
        const reasons = data.reasons || [];
        const recommendation = data.recommendation || '';
        const openCount = level === 'safe' ? 0 : 1;
        const highCount = level === 'block' ? 1 : 0;

        box.innerHTML =
            '<div class="small text-secondary mb-1">Fraud Detection Monitor</div>' +
            '<div class="fw-semibold mb-1 text-capitalize">Enabled</div>' +
            '<div class="fw-semibold mb-1 text-capitalize">Latest check: ' +
            escapeHtml(level) +
            '</div>' +
            '<div class="small mb-2">Fraud score: <strong>' +
            (typeof score === 'number' ? score.toFixed(2) : '—') +
            '</strong></div>' +
            '<div class="small mb-1"><strong>Open alerts:</strong> ' +
            openCount +
            ' | <strong>High risk:</strong> ' +
            highCount +
            '</div>' +
            '<ul class="small ps-3 mb-2">' +
            reasons.map(function (r) {
                return '<li>' + escapeHtml(r) + '</li>';
            }).join('') +
            '</ul>' +
            '<div class="small text-secondary">' +
            escapeHtml(recommendation) +
            '</div>' +
            '<button type="button" class="btn btn-sm btn-outline-secondary mt-2" id="btn-ai-fraud-refresh">Run check again</button>';
        const again = $('#btn-ai-fraud-refresh', box);
        if (again) {
            again.addEventListener('click', function () {
                runFraudCheck(aiFormGlobal, box);
            });
        }
    }

    function escapeHtml(s) {
        const d = document.createElement('div');
        d.textContent = s;
        return d.innerHTML;
    }

    let aiFormGlobal = null;

    async function runRecommendations(aiForm, box, btn) {
        const data = getForm(aiForm);
        setButtonLoading(btn, true);
        try {
            const payload = {
                property_type: data.property_type,
                home_size_sqft: data.home_size_sqft,
                budget: data.budget,
                neighborhood_risk: data.neighborhood_risk,
                occupancy: data.occupancy,
                past_purchases: [],
            };
            const res = await postJson('/recommendations', payload);
            renderRecommendations(box, res, null);
        } catch (e) {
            renderRecommendations(box, null, e.message || String(e));
        } finally {
            setButtonLoading(btn, false);
        }
    }

    async function runSafetyScore(aiForm, box, btn) {
        const data = getForm(aiForm);
        setButtonLoading(btn, true);
        try {
            const payload = {
                property_type: data.property_type,
                home_size_sqft: data.home_size_sqft,
                entry_points: data.entry_points,
                exit_points: data.exit_points,
                neighborhood_risk: data.neighborhood_risk,
                occupancy: data.occupancy,
                has_existing_security: data.has_existing_security,
                previous_incidents: data.previous_incidents,
            };
            const res = await postJson('/safety-score', payload);
            renderSafety(box, res, null);
        } catch (e) {
            renderSafety(box, null, e.message || String(e));
        } finally {
            setButtonLoading(btn, false);
        }
    }

    async function runFraudCheck(aiForm, box) {
        if (box) {
            box.innerHTML =
                '<div class="small text-secondary mb-1">Fraud Detection Monitor</div>' +
                '<div class="small"><span class="spinner-border spinner-border-sm me-1" role="status"></span>Loading...</div>';
        }
        const data = getForm(aiForm);
        const orderAmount = data.budget > 0 ? data.budget : 199;
        const payload = {
            user_id: ensureFraudUserId(),
            order_amount: orderAmount,
            payment_method: 'credit_card',
            location: typeof navigator !== 'undefined' && navigator.language ? navigator.language : 'Local',
            time_of_day: formatTimeOfDay(),
            is_new_account: false,
            previous_orders: 0,
        };
        try {
            const res = await postJson('/fraud-check', payload);
            renderFraud(box, res, null);
        } catch (e) {
            renderFraud(box, null, e.message || String(e));
        }
    }

    function initChat() {
        const form = $('#ai-chatbot-form');
        const input = $('#ai-chatbot-input');
        const messages = $('#ai-chatbot-messages');
        if (!form || !input || !messages) return;

        const appendMessage = function (label, text) {
            const row = document.createElement('div');
            row.className = 'small mb-2';
            row.innerHTML = '<strong>' + escapeHtml(label) + ':</strong> ' + escapeHtml(text);
            messages.appendChild(row);
            messages.scrollTop = messages.scrollHeight;
        };

        form.addEventListener('submit', async function (event) {
            event.preventDefault();
            const message = input.value.trim();
            if (!message) return;

            const submitBtn = form.querySelector('button[type="submit"]');
            appendMessage('You', message);
            input.value = '';
            setButtonLoading(submitBtn, true);

            const aiForm = document.querySelector('button[name="ai_recommend"]')?.closest('form');
            const ctx = aiForm ? getForm(aiForm) : {};
            const context = {
                property_type: ctx.property_type,
                budget: ctx.budget,
                neighborhood_risk: ctx.neighborhood_risk,
                occupancy: ctx.occupancy,
            };

            try {
                const res = await postJson('/chat', {
                    message: message,
                    session_id: ensureSessionId(),
                    context: context,
                });
                appendMessage('AI', res.reply || 'No reply.');
            } catch (e) {
                appendMessage('AI', 'Error: ' + (e.message || String(e)));
            } finally {
                setButtonLoading(submitBtn, false);
            }
        });
    }

    function init() {
        initChat();

        const btnRec = $('button[name="ai_recommend"]');
        const btnScore = $('button[name="ai_score"]');
        const aiForm = btnRec && btnRec.closest('form');
        aiFormGlobal = aiForm;

        const panels = findAiPanels();
        if (!aiForm || !panels) return;

        const recBox = panelBox(panels.recommendations);
        const safetyBox = panelBox(panels.safety);
        const fraudBox = panelBox(panels.fraud);

        btnRec.type = 'button';
        btnScore.type = 'button';

        btnRec.addEventListener('click', function (e) {
            e.preventDefault();
            runRecommendations(aiForm, recBox, btnRec);
        });

        btnScore.addEventListener('click', function (e) {
            e.preventDefault();
            runSafetyScore(aiForm, safetyBox, btnScore);
        });

        runFraudCheck(aiForm, fraudBox);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
