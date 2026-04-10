"""POST /ai/fraud-check — rules + IsolationForest anomaly score."""

from fastapi import APIRouter, HTTPException

from ai_engine.models.fraud_model import assess
from ai_engine.schemas.schemas import FraudCheckRequest, FraudCheckResponse
from ai_engine.utils.helpers import get_logger

logger = get_logger(__name__)

router = APIRouter(tags=["fraud"])


@router.post("/fraud-check", response_model=FraudCheckResponse)
def post_fraud_check(body: FraudCheckRequest) -> FraudCheckResponse:
    try:
        out = assess(
            user_id=body.user_id,
            order_amount=body.order_amount,
            payment_method=body.payment_method,
            location=body.location,
            time_of_day=body.time_of_day,
            is_new_account=body.is_new_account,
            previous_orders=body.previous_orders,
            user_avg_order=None,
        )
        return FraudCheckResponse(**out)
    except Exception as e:
        logger.exception("fraud check failed: %s", e)
        raise HTTPException(status_code=500, detail="Failed to run fraud check") from e
