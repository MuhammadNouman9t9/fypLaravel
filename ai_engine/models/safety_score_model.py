"""
Weighted risk scoring (0–100) and Low / Moderate / High classification.
"""

from __future__ import annotations

from ai_engine.utils.helpers import get_logger, normalize_str

logger = get_logger(__name__)

# Sub-scores per factor (sum to 100 before normalization tweaks)
WEIGHTS = {
    "neighborhood_risk": 0.30,
    "entry_points": 0.20,
    "exit_points": 0.10,
    "property_type": 0.12,
    "occupancy": 0.08,
    "home_size": 0.08,
    "existing_security": 0.07,
    "previous_incidents": 0.05,
}


def _neighborhood_points(risk: str) -> float:
    r = normalize_str(risk)
    if "low" in r:
        return 10
    if "mod" in r or "medium" in r:
        return 40
    if "high" in r:
        return 90
    return 45


def _entry_exit_points(entry: int, exit_pts: int) -> tuple[float, float]:
    # More access points -> higher exposure (capped)
    e = min(100.0, 15.0 + entry * 18.0)
    x = min(100.0, 10.0 + exit_pts * 22.0)
    return e, x


def _property_points(pt: str) -> float:
    s = normalize_str(pt)
    if "apart" in s or "condo" in s:
        return 35
    if "town" in s:
        return 45
    return 55


def _occupancy_points(occ: str) -> float:
    s = normalize_str(occ)
    if "single" in s or "solo" in s:
        return 40
    if "family" in s or "children" in s:
        return 55
    if "roommate" in s or "shared" in s:
        return 50
    return 45


def _home_size_points(sqft: int) -> float:
    if sqft < 800:
        return 30
    if sqft < 1500:
        return 45
    if sqft < 3000:
        return 60
    return 70


def analyze(
    property_type: str,
    home_size_sqft: int,
    entry_points: int,
    exit_points: int,
    neighborhood_risk: str,
    occupancy: str,
    has_existing_security: bool,
    previous_incidents: bool,
) -> dict:
    nh = _neighborhood_points(neighborhood_risk)
    ent, ex = _entry_exit_points(entry_points, exit_points)
    pt = _property_points(property_type)
    oc = _occupancy_points(occupancy)
    hs = _home_size_points(home_size_sqft)
    sec = 15.0 if has_existing_security else 75.0
    inc = 80.0 if previous_incidents else 20.0

    breakdown_raw = {
        "neighborhood_risk": nh * WEIGHTS["neighborhood_risk"],
        "entry_points": ent * WEIGHTS["entry_points"],
        "exit_points": ex * WEIGHTS["exit_points"],
        "property_type": pt * WEIGHTS["property_type"],
        "occupancy": oc * WEIGHTS["occupancy"],
        "home_size": hs * WEIGHTS["home_size"],
        "existing_security": sec * WEIGHTS["existing_security"],
        "previous_incidents": inc * WEIGHTS["previous_incidents"],
    }

    total = sum(breakdown_raw.values())
    # Model is roughly 0-100 already; clamp
    score = int(round(min(100.0, max(0.0, total))))

    if score <= 39:
        classification = "Low Risk"
    elif score <= 69:
        classification = "Moderate Risk"
    else:
        classification = "High Risk"

    # Breakdown as contribution points (rounded for API clarity)
    breakdown = {k: round(v, 2) for k, v in breakdown_raw.items()}

    recs: list[str] = []
    if not has_existing_security:
        recs.append("Install monitored door/window sensors on primary entry points.")
    if entry_points >= 3:
        recs.append("Add motion coverage or cameras to secondary entries.")
    if normalize_str(neighborhood_risk).find("high") >= 0:
        recs.append("Consider visible outdoor deterrents (siren, floodlight camera).")
    if previous_incidents:
        recs.append("Review incident logs and upgrade perimeter detection.")
    if not recs:
        recs.append("Maintain firmware updates and test alerts monthly.")

    logger.info("Safety score=%s class=%s", score, classification)
    return {
        "score": score,
        "classification": classification,
        "breakdown": breakdown,
        "recommendations": recs[:5],
    }
