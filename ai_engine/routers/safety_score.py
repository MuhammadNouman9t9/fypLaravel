"""POST /ai/safety-score — weighted home risk score."""

from fastapi import APIRouter, HTTPException

from ai_engine.models.safety_score_model import analyze
from ai_engine.schemas.schemas import SafetyScoreRequest, SafetyScoreResponse
from ai_engine.utils.helpers import get_logger

logger = get_logger(__name__)

router = APIRouter(tags=["safety-score"])


@router.post("/safety-score", response_model=SafetyScoreResponse)
def post_safety_score(body: SafetyScoreRequest) -> SafetyScoreResponse:
    try:
        out = analyze(
            property_type=body.property_type,
            home_size_sqft=body.home_size_sqft,
            entry_points=body.entry_points,
            exit_points=body.exit_points,
            neighborhood_risk=body.neighborhood_risk,
            occupancy=body.occupancy,
            has_existing_security=body.has_existing_security,
            previous_incidents=body.previous_incidents,
        )
        return SafetyScoreResponse(**out)
    except Exception as e:
        logger.exception("safety score failed: %s", e)
        raise HTTPException(status_code=500, detail="Failed to compute safety score") from e
