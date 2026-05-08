# SafeNest (FYP Laravel) — Viva Preparation Notes

Ye file aap **word-to-word read** karke viva cover kar sakte hain. Roman Urdu me explanation hai, aur sath **English keywords** bhi diye gaye hain (viva me instructor usually English terms expect karta hai).

---

## Project One‑Liner (Elevator Pitch)

**SafeNest** ek **AI‑powered smart home security e‑commerce platform** hai jahan user security products browse + cart + checkout (Stripe) kar sakta hai, aur system AI ki help se **risk analysis**, **product recommendations**, **fraud detection**, aur **security chatbot** provide karta hai.

---

## Tech Stack (Confirmed from repo)

- **Backend**: **Laravel 12 (PHP 8.2+)** (`composer.json`)
- **Payments**: **Stripe** (`stripe/stripe-php`)
- **Auth & Security**:
  - Laravel Breeze (auth scaffolding)
  - **2FA** via `pragmarx/google2fa`
  - OTP flows (routes/controllers exist)
  - Role-based access: **Admin / Owner / User** (middleware + routes)
- **Database**: **MySQL** (from `.env.example`)
- **Session/Queue/Cache**: database drivers enabled in `.env.example`
- **AI Microservice**: **Python FastAPI** (`ai_engine/requirements.txt`)
  - `fastapi`, `uvicorn`, `pydantic`
  - ML: `scikit-learn`, `numpy`
  - LLM provider: `anthropic` + optional `groq` (AI engine `.env.example`)
- **Frontend**: repo me `package.json` minimal hai; UI mostly **Blade views** + Laravel tooling (Vite variable present in `.env.example`).

---

## High-Level Architecture (How the system is built)

**Monolith + Microservice** approach:

- **Laravel app** handles:
  - UI pages (Blade), auth, roles, DB, cart/orders, Stripe payments, admin panels, support, audit logs.
- **FastAPI AI engine** handles:
  - `/ai/recommendations`
  - `/ai/safety-score`
  - `/ai/fraud-check`
  - `/ai/chat`

### Architecture Diagram (Viva friendly)

```mermaid
flowchart LR
  U[User Browser] -->|HTTP| L[Laravel 12 App]
  L -->|Eloquent ORM| DB[(MySQL)]
  L -->|Stripe API| S[Stripe]
  L -->|HTTP (Bearer key)| AI[FastAPI AI Engine :8001]
  AI -->|LLM API| AIP[Anthropic/Groq]
```

**Why this design?**
- Laravel is best for **business logic + DB + security + admin panel**.
- Python FastAPI is best for **ML/LLM tasks** (recommendation/fraud/chat).
- Integration is via **HTTP calls** with API key (config driven).

---

## Core Modules (What features exist)

### E‑Commerce Flow
- **Landing + Products**: `/`, `/products`, `/products/{slug}`
- **Cart**: session-based cart (`CartController`)
- **Orders**: checkout creates `orders` + `order_items`
- **Payments**: Stripe PaymentIntent + webhook (`PaymentController`, `StripeService`)
- **Shipping**: after paid, `Shipment` auto created (SafeNest Express)

### AI Features
- **Risk Analyzer** (home/property risk): `/risk-analyzer` + analyze
- **Recommendations**: based on context/purchases (hybrid/collaborative/content-based)
- **Fraud Detection**: post-payment fraud analysis creates `fraud_alerts`
- **Chatbot**: security advisor (Dialogflow/OpenAI in Laravel config, or LLM in Python engine)

### Admin + Owner Roles
- **Admin**: `/admin/*` (products, users, orders, support, analytics, fraud alerts)
- **Owner**: `/owner/*` (dashboard + auth)
- Role middleware: `EnsureUserIsAdmin`, `EnsureUserIsOwner`

### Security Features
- **2FA enforcement** on some routes (`EnsureTwoFactorVerified`)
- OTP verification flows (`OtpVerificationController` routes)
- Audit/security logs tables exist (`audit_logs`, `security_audit_logs`, `security_logs`)

---

## Important Config (Viva me “How to enable AI?”)

### Laravel → config toggles
- `config/safenest.php`:
  - `safenest.ai.*.enabled` feature toggles
- `config/services.php`:
  - `services.safenest.python_api.url/key` (integration with Python)
  - `services.stripe.*` (Stripe keys)
  - `services.safenest.chatbot.*` (provider settings)

**Key idea**: AI integration **optional** hai. Agar Python API configured ho to Laravel Python ko call karta hai; warna **fallback PHP logic** use karta hai (RiskAnalyzer + Recommendation + Fraud).

---

## Database Design (Tables you should mention)

E‑commerce:
- `products`, `product_media`, `product_specifications`
- `categories` + pivot `category_product`
- `inventory_items`
- `orders`, `order_items`, `payments`, `shipments`

AI related:
- `risk_assessments` (score, risk_level, recommendations JSON, analysis JSON)
- `ai_recommendations` (products JSON, algorithm, confidence_score)
- `fraud_alerts` (score, flags JSON, status open/resolved)

Users & security:
- `users`, `roles`, `role_user`
- `otps`
- `audit_logs`, `security_audit_logs`, `security_logs`
- `support_conversations`, `support_messages`

**Why JSON columns?**
- AI responses structured hoti hain (analysis, flags, recommended products list).
- Flexible schema for experiments without frequent migrations.

---

## Main User Journeys (Best viva storytelling)

### 1) Browse → Cart → Checkout → Payment
1. User `/products` se product choose karta hai.
2. `POST /cart` session me item add hota hai.
3. `POST /cart/checkout`:
   - `orders` + `order_items` create
   - cart clear
   - redirect to `payment.checkout`
4. `GET /payment/checkout/{order}`:
   - Stripe PaymentIntent create (`StripeService::createPaymentIntent`)
   - `payments` row create (pending + client_secret)
5. Payment succeed:
   - `payments.status = succeeded`
   - `orders.payment_status = paid`
   - shipment create
   - **fraud detection runs** (does not block payment)

**Viva line**: “Payment success ke baad fraud check asynchronous style me run hota hai; agar fraud check fail ho jaye to payment fail nahi hoti—sirf log/create alert hota hai.”

### 2) Risk Analyzer → Recommendations
1. User `/risk-analyzer` form fill karta hai.
2. `POST /risk-analyzer/analyze`
3. `RiskAnalyzerService`:
   - if Python configured: HTTP call
   - else: PHP scoring rules
4. Result page show + recommended products fetched via `RecommendationService`.

### 3) Chatbot
1. Frontend `POST /chatbot/chat` hits Laravel `ChatbotController`.
2. `ChatbotService` provider based response:
   - Dialogflow (if configured)
   - OpenAI style chat completions (if configured)
3. Python AI engine also has `/ai/chat` that uses Anthropic/Groq with session history.

---

## AI Engine (FastAPI) — What endpoints do

Run command (from docstring):

```bash
uvicorn ai_engine.main:app --host 127.0.0.1 --port 8001
```

Endpoints (prefix `/ai`):
- `POST /ai/recommendations`
  - hybrid recommendations (content + collaborative style)
- `POST /ai/safety-score`
  - weighted safety/risk score
- `POST /ai/fraud-check`
  - rule checks + anomaly detection (IsolationForest)
- `POST /ai/chat`
  - LLM chatbot (Anthropic default; Groq optional)
- `GET /health`
  - service health check

**Security note**: AI engine keys `.env` me; production me environment secrets use hotay hain.

---

## Viva Q/A (Most likely questions)

### 1) “Is project ka problem statement kya hai?”
**Answer**: Smart home security products ki buying ke sath users ko guidance nahi milti—SafeNest e‑commerce + AI guidance provide karta hai: risk analysis, personalized recommendations, fraud alerts, aur chatbot support.

### 2) “Laravel kyun use kiya?”
**Answer**: Laravel rapid development, strong security, Eloquent ORM, routing/middleware, auth scaffolding (Breeze), aur admin-style CRUD features ke liye best fit tha.

### 3) “Python microservice kyun banai?”
**Answer**: ML/LLM libraries Python me mature hain. FastAPI lightweight aur fast hai; is se AI features isolate ho jate hain, scaling/deployment independent ho jata hai.

### 4) “Monolith vs microservice trade-off?”
**Answer**:
- **Pros**: AI stack isolated, scaling easy, failures contained.
- **Cons**: network latency, extra deployment complexity, auth/key management.

### 5) “Stripe flow explain karo”
**Answer**: Order create → PaymentIntent generate → client_secret front-end use karta hai → payment succeed par payment/order update → webhook events handle → shipment create.

### 6) “Webhook ka role?”
**Answer**: Client-side success miss ho jaye to webhook server-to-server confirmation provide karta hai. Signature verification (`STRIPE_WEBHOOK_SECRET`) se spoofing prevent hoti hai.

### 7) “Fraud detection kab hoti hai?”
**Answer**: Payment success ke baad. Risk score threshold (>=30) par `fraud_alerts` create hota hai aur admin panel me visible hota hai.

### 8) “Risk score ka logic?”
**Answer**: Property type/size, occupancy, neighborhood profile, entry/exit points, security system, previous incidents. Output score 0–100 normalize hota hai then risk level (low/moderate/high/critical).

### 9) “Recommendations ka approach?”
**Answer**:
- Python configured: AI service product IDs return karta hai.
- Else PHP fallback:
  - content-based filters (context)
  - collaborative signals (past purchases)
  - hybrid merge + scoring.

### 10) “Data modeling me JSON columns kyun?”
**Answer**: AI outputs dynamic/structured hotay hain; schema changes ke baghair store kar sakte hain (analysis, flags, products array).

### 11) “Security: 2FA ka flow?”
**Answer**: User 2FA enable karta hai (Google2FA), verify route, recovery codes; `EnsureTwoFactorVerified` middleware protected routes pe enforce kar sakta hai.

### 12) “Roles kaise enforce hain?”
**Answer**: Admin routes prefix `admin` + middleware `auth` and `admin`. Owner routes `owner` + middleware `auth` and `owner`. Normal users auth routes separate.

### 13) “Caching/performance?”
**Answer**: RecommendationService me user purchases aur similar users ko cache keys ke sath remember kiya gaya (6–12 hours) to expensive queries reduce hon.

### 14) “Failure handling?”
**Answer**: Stripe init fail ho to user-friendly error; fraud detection fail ho to payment process continue (log only). External AI calls timeouts ke sath wrapped hain.

### 15) “Testing plan (bolne ke liye)?”
**Answer**:
- Unit: services (risk score calc, recommendation scoring)
- Feature: cart checkout → order created → payment intent created
- Webhook signature invalid case
- Admin access control tests.

---

## Short “Demo Script” (Viva me live demo karna ho)

1. Home → Products list → product open → Add to cart
2. Cart page → checkout (login required)
3. Payment checkout page (Stripe) → success page
4. Risk Analyzer page → submit form → score + recommendations
5. Admin login → Orders / Fraud Alerts / Support section show

---

## Important Files (Agar examiner poochay “code kahan hai?”)

- **Routes**: `routes/web.php`, `routes/auth.php`, `routes/admin.php`, `routes/owner.php`
- **Payments**: `app/Http/Controllers/Payment/PaymentController.php`, `app/Services/StripeService.php`
- **Cart/Orders**: `app/Http/Controllers/Cart/CartController.php`, `app/Models/Order.php`, `app/Models/Payment.php`
- **AI**: `app/Services/RecommendationService.php`, `app/Services/RiskAnalyzerService.php`, `app/Services/FraudDetectionService.php`
- **AI Engine**: `ai_engine/main.py`, `ai_engine/routers/*.py`, `ai_engine/requirements.txt`
- **Config**: `config/safenest.php`, `config/services.php`

---

## 30‑Second Closing Statement (Viva end)

“SafeNest ek secure, scalable e‑commerce platform hai jo smart home security products sell karta hai. Laravel business logic, auth, roles, aur payments handle karta hai; aur FastAPI AI engine recommendations, safety scoring, fraud checks aur chatbot provide karta hai. Config-driven integration se system external AI services ke baghair bhi fallback logic ke sath work karta hai, aur Stripe webhook + 2FA/OTP jaise features system ko secure banate hain.”

