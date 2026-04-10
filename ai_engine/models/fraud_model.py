"""
Rule-based fraud signals plus IsolationForest trained on mock transactions.
"""

from __future__ import annotations

import numpy as np
from sklearn.ensemble import IsolationForest
from sklearn.preprocessing import StandardScaler

from ai_engine.utils.helpers import get_logger, normalize_str

logger = get_logger(__name__)

RNG = np.random.RandomState(42)
_MODEL: IsolationForest | None = None
_SCALER: StandardScaler | None = None


def _synthetic_training_rows(n_normal: int = 400, n_outliers: int = 40) -> tuple[np.ndarray, np.ndarray]:
    """Features: log1p(amount), hour, is_night, is_new, log1p(prev_orders), card_indicator."""
    X = []
    y = []
    for _ in range(n_normal):
        amt = RNG.uniform(20, 800)
        hour = int(RNG.choice(range(8, 22)))
        is_night = 0.0
        is_new = float(RNG.choice([0, 1], p=[0.85, 0.15]))
        prev = float(RNG.poisson(3)) if is_new < 0.5 else 0.0
        card = float(RNG.choice([0, 1]))
        X.append(
            [
                np.log1p(amt),
                hour / 24.0,
                is_night,
                is_new,
                np.log1p(prev),
                card,
            ]
        )
        y.append(1)
    for _ in range(n_outliers):
        amt = RNG.uniform(2000, 12000)
        hour = int(RNG.choice(list(range(0, 6)) + [23]))
        is_night = 1.0
        is_new = 1.0
        prev = 0.0
        card = 1.0
        X.append(
            [
                np.log1p(amt),
                hour / 24.0,
                is_night,
                is_new,
                np.log1p(prev),
                card,
            ]
        )
        y.append(-1)
    return np.asarray(X, dtype=float), np.asarray(y)


def _get_model() -> tuple[IsolationForest, StandardScaler]:
    global _MODEL, _SCALER
    if _MODEL is not None and _SCALER is not None:
        return _MODEL, _SCALER
    X, _ = _synthetic_training_rows()
    scaler = StandardScaler()
    Xs = scaler.fit_transform(X)
    model = IsolationForest(
        n_estimators=200,
        contamination=0.08,
        random_state=42,
        n_jobs=-1,
    )
    model.fit(Xs)
    _MODEL = model
    _SCALER = scaler
    logger.info("IsolationForest fitted on synthetic transaction data.")
    return _MODEL, _SCALER


def _parse_hour(time_of_day: str) -> float:
    try:
        parts = time_of_day.strip().split(":")
        h = int(parts[0])
        return float(h % 24)
    except (ValueError, IndexError):
        return 12.0


def _feature_vector(
    order_amount: float,
    time_of_day: str,
    is_new_account: bool,
    previous_orders: int,
    payment_method: str,
) -> np.ndarray:
    hour = _parse_hour(time_of_day)
    is_night = 1.0 if 2 <= hour <= 5 or hour == 23 else 0.0
    pm = normalize_str(payment_method)
    card = 1.0 if "card" in pm or "credit" in pm or "debit" in pm else 0.0
    return np.array(
        [
            [
                np.log1p(max(order_amount, 0.0)),
                hour / 24.0,
                is_night,
                1.0 if is_new_account else 0.0,
                np.log1p(max(previous_orders, 0)),
                card,
            ]
        ],
        dtype=float,
    )


def assess(
    user_id: str,
    order_amount: float,
    payment_method: str,
    location: str,
    time_of_day: str,
    is_new_account: bool,
    previous_orders: int,
    user_avg_order: float | None = None,
) -> dict:
    """
    Returns fraud_score 0-1, alert_level, reasons, recommendation.
    """
    reasons: list[str] = []
    rule_score = 0.0

    hour = _parse_hour(time_of_day)
    if 2 <= hour <= 5:
        reasons.append("Unusual hour")
        rule_score += 0.25

    if is_new_account and order_amount >= 800:
        reasons.append("New account")
        rule_score += 0.2
        if order_amount >= 1500:
            reasons.append("Large order")
            rule_score += 0.2

    avg = user_avg_order if user_avg_order is not None else 200.0
    if previous_orders >= 3 and order_amount > 3 * max(avg, 1.0):
        reasons.append("Order well above user average")
        rule_score += 0.25

    if previous_orders == 0 and order_amount > 1200:
        if "Large order" not in reasons:
            reasons.append("Large order")
        rule_score += 0.15

    # Location anomaly (very light mock)
    loc = normalize_str(location)
    if "pk" in loc or "lahore" in loc:
        pass  # neutral for demo

    model, scaler = _get_model()
    x = _feature_vector(order_amount, time_of_day, is_new_account, previous_orders, payment_method)
    xs = scaler.transform(x)
    raw = model.decision_function(xs)[0]
    pred = model.predict(xs)[0]
    # IsolationForest: lower decision_function -> more anomalous
    # Map to [0,1] probability-like score
    ml_component = float(1.0 / (1.0 + np.exp(raw * 2.0)))
    if pred == -1:
        ml_component = min(1.0, ml_component + 0.15)
        if "ML anomaly flag" not in reasons:
            reasons.append("ML anomaly flag")

    fraud_score = float(min(1.0, max(0.0, 0.45 * rule_score + 0.55 * ml_component)))

    if fraud_score < 0.35:
        alert_level = "safe"
        recommendation = "Approve; routine monitoring only."
    elif fraud_score < 0.65:
        alert_level = "review"
        recommendation = "Queue for manual review before fulfillment."
    else:
        alert_level = "block"
        recommendation = "Hold order for manual review"

    if not reasons:
        reasons.append("No strong risk signals")

    logger.info(
        "Fraud check user=%s amount=%s score=%.2f level=%s",
        user_id,
        order_amount,
        fraud_score,
        alert_level,
    )
    return {
        "fraud_score": round(fraud_score, 4),
        "alert_level": alert_level,
        "reasons": reasons,
        "recommendation": recommendation,
    }
