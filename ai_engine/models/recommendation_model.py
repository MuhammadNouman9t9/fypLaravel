"""
Content-based (TF-IDF + cosine similarity) and collaborative filtering
(mock user–product matrix) for product recommendations.
"""

from __future__ import annotations

import numpy as np
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import cosine_similarity

from ai_engine.utils.helpers import get_logger, normalize_str, risk_bucket

logger = get_logger(__name__)

# Mock catalog: id, name, text for TF-IDF, optional price hint for budget filtering
MOCK_PRODUCTS: list[dict] = [
    {
        "product_id": "p_door_sensor_pro",
        "name": "SafeNest Door Sensor Pro",
        "doc": "door sensor wireless apartment entry magnetic alert intrusion detection",
        "max_budget_fit": 80,
    },
    {
        "product_id": "p_motion_wide",
        "name": "Wide-Angle Motion Detector",
        "doc": "motion sensor pir hallway large home family occupancy pet immune",
        "max_budget_fit": 120,
    },
    {
        "product_id": "p_cam_indoor_4k",
        "name": "Indoor 4K Security Camera",
        "doc": "camera indoor apartment streaming night vision two way audio cloud",
        "max_budget_fit": 200,
    },
    {
        "product_id": "p_cam_outdoor",
        "name": "Outdoor Weatherproof Camera",
        "doc": "outdoor camera weatherproof house detached garage high risk neighborhood",
        "max_budget_fit": 280,
    },
    {
        "product_id": "p_glass_break",
        "name": "Glass Break Acoustic Sensor",
        "doc": "glass break sensor window apartment condo ground floor burglary",
        "max_budget_fit": 95,
    },
    {
        "product_id": "p_smart_lock",
        "name": "Smart Lock with Keypad",
        "doc": "smart lock deadbolt entry exit family home automation access control",
        "max_budget_fit": 350,
    },
    {
        "product_id": "p_hub_zigbee",
        "name": "SafeNest Security Hub (Zigbee)",
        "doc": "hub zigbee coordinator sensors cameras whole home integration family",
        "max_budget_fit": 180,
    },
    {
        "product_id": "p_siren_outdoor",
        "name": "Outdoor Siren Strobe",
        "doc": "siren strobe deterrent high risk neighborhood loud alarm perimeter",
        "max_budget_fit": 140,
    },
    {
        "product_id": "p_water_leak",
        "name": "Smart Water Leak Sensor",
        "doc": "water leak sensor basement laundry pipe burst flood detection",
        "max_budget_fit": 60,
    },
    {
        "product_id": "p_smoke_combo",
        "name": "Smoke & CO Listener",
        "doc": "smoke carbon monoxide listener safety family occupancy apartment",
        "max_budget_fit": 55,
    },
]

# Mock collaborative data: segment key -> list of (user_id, product_id) purchases
MOCK_SEGMENT_PURCHASES: dict[str, list[tuple[str, str]]] = {
    "apartment|high": [
        ("u1", "p_cam_indoor_4k"),
        ("u1", "p_door_sensor_pro"),
        ("u2", "p_glass_break"),
        ("u2", "p_hub_zigbee"),
        ("u3", "p_door_sensor_pro"),
        ("u3", "p_motion_wide"),
        ("u4", "p_cam_indoor_4k"),
        ("u4", "p_siren_outdoor"),
    ],
    "apartment|medium": [
        ("u5", "p_door_sensor_pro"),
        ("u6", "p_motion_wide"),
        ("u7", "p_hub_zigbee"),
    ],
    "house|high": [
        ("u8", "p_cam_outdoor"),
        ("u8", "p_siren_outdoor"),
        ("u9", "p_motion_wide"),
        ("u9", "p_smart_lock"),
    ],
    "house|low": [
        ("u10", "p_door_sensor_pro"),
        ("u11", "p_water_leak"),
    ],
    "condo|medium": [
        ("u12", "p_cam_indoor_4k"),
        ("u12", "p_smoke_combo"),
    ],
}

_vectorizer: TfidfVectorizer | None = None
_product_matrix = None


def _segment_key(property_type: str, neighborhood_risk: str) -> str:
    pt = normalize_str(property_type)
    if "apart" in pt:
        ptype = "apartment"
    elif "condo" in pt or "town" in pt:
        ptype = "condo"
    else:
        ptype = "house"
    rb = risk_bucket(neighborhood_risk)
    return f"{ptype}|{rb}"


def _build_user_query_text(
    property_type: str,
    home_size_sqft: int,
    budget: float,
    neighborhood_risk: str,
    occupancy: str,
) -> str:
    size_bucket = "small" if home_size_sqft < 900 else "medium" if home_size_sqft < 2000 else "large"
    budget_hint = "budget conscious" if budget < 200 else "mid range" if budget < 600 else "premium"
    return (
        f"{property_type} {size_bucket} home {home_size_sqft} sqft "
        f"{risk_bucket(neighborhood_risk)} risk neighborhood "
        f"{occupancy} occupancy {budget_hint} spending"
    )


def _ensure_tfidf():
    global _vectorizer, _product_matrix
    if _vectorizer is not None:
        return
    docs = [p["doc"] for p in MOCK_PRODUCTS]
    _vectorizer = TfidfVectorizer(lowercase=True, ngram_range=(1, 2), min_df=1)
    _product_matrix = _vectorizer.fit_transform(docs)


def _content_scores(query_text: str) -> np.ndarray:
    _ensure_tfidf()
    q = _vectorizer.transform([query_text])
    sims = cosine_similarity(q, _product_matrix).flatten()
    return sims


def _collaborative_scores(
    segment: str,
    past_purchases: list[str],
    n_products: int,
) -> np.ndarray:
    """Popularity scores in segment; products already owned get lower weight."""
    purchases = MOCK_SEGMENT_PURCHASES.get(segment)
    if not purchases:
        # Fallback: merge medium segments
        purchases = []
        for k, v in MOCK_SEGMENT_PURCHASES.items():
            if k.split("|")[-1] == segment.split("|")[-1]:
                purchases.extend(v)
    counts: dict[str, float] = {}
    for _, pid in purchases:
        counts[pid] = counts.get(pid, 0.0) + 1.0
    past = {normalize_str(p).replace(" ", "_") for p in past_purchases}
    scores = np.zeros(n_products, dtype=float)
    for i, p in enumerate(MOCK_PRODUCTS):
        pid = p["product_id"]
        base = counts.get(pid, 0.0)
        # Boost if slug-ish match in past purchases (complementary)
        slug = pid.replace("p_", "")
        if any(slug in pp or pp in slug for pp in past):
            base *= 0.3
        scores[i] = base
    max_c = scores.max() if scores.size else 0.0
    if max_c > 0:
        scores = scores / max_c
    return scores


def recommend(
    property_type: str,
    home_size_sqft: int,
    budget: float,
    neighborhood_risk: str,
    occupancy: str,
    past_purchases: list[str],
    top_k: int = 5,
    content_weight: float = 0.6,
    collab_weight: float = 0.4,
) -> list[dict]:
    """
    Return top_k recommendations with product_id, name, reason, score (0-1).
    """
    query = _build_user_query_text(
        property_type, home_size_sqft, budget, neighborhood_risk, occupancy
    )
    content = _content_scores(query)
    seg = _segment_key(property_type, neighborhood_risk)
    collab = _collaborative_scores(seg, past_purchases, len(MOCK_PRODUCTS))

    # Normalize content to [0,1]
    c_max = float(content.max()) if content.size else 0.0
    if c_max > 0:
        content_norm = content / c_max
    else:
        content_norm = content

    combined = content_weight * content_norm + collab_weight * collab

    # Soft penalty if product typical price exceeds budget (mock heuristic)
    for i, p in enumerate(MOCK_PRODUCTS):
        if budget > 0 and p.get("max_budget_fit", 9999) > budget * 0.9:
            combined[i] *= 0.85

    order = np.argsort(-combined)[:top_k]
    results = []
    for idx in order:
        p = MOCK_PRODUCTS[int(idx)]
        c_s = float(content_norm[idx])
        cf_s = float(collab[idx])
        final = float(combined[idx])
        # Map to 0-1 confidence
        conf = min(1.0, max(0.0, final))
        reason = (
            f"Content match {c_s:.0%} vs your home profile; "
            f"similar buyers in your segment scored this {cf_s:.0%}."
        )
        results.append(
            {
                "product_id": p["product_id"],
                "name": p["name"],
                "reason": reason,
                "score": round(conf, 4),
            }
        )
    logger.info("Recommendations for segment=%s query=%r count=%s", seg, query, len(results))
    return results
