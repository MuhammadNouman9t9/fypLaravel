"""POST /ai/recommendations — hybrid content + collaborative recommendations."""

from fastapi import APIRouter, HTTPException

from ai_engine.models.recommendation_model import recommend
from ai_engine.schemas.schemas import RecommendationsRequest, RecommendationsResponse, RecommendationItem
from ai_engine.utils.helpers import get_logger

logger = get_logger(__name__)

router = APIRouter(tags=["recommendations"])


@router.post("/recommendations", response_model=RecommendationsResponse)
def post_recommendations(body: RecommendationsRequest) -> RecommendationsResponse:
    try:
        rows = recommend(
            property_type=body.property_type,
            home_size_sqft=body.home_size_sqft,
            budget=body.budget,
            neighborhood_risk=body.neighborhood_risk,
            occupancy=body.occupancy,
            past_purchases=body.past_purchases,
            top_k=5,
        )
        items = [RecommendationItem(**r) for r in rows]
        return RecommendationsResponse(recommendations=items)
    except Exception as e:
        logger.exception("recommendations failed: %s", e)
        raise HTTPException(status_code=500, detail="Failed to compute recommendations") from e
