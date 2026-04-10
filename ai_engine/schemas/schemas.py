"""Pydantic v2 request/response models for SafeNest AI Engine."""

from typing import Any

from pydantic import BaseModel, Field


# --- Recommendations ---


class RecommendationsRequest(BaseModel):
    property_type: str = Field(..., examples=["Apartment"])
    home_size_sqft: int = Field(..., ge=0, examples=[1200])
    budget: float = Field(..., ge=0, examples=[500])
    neighborhood_risk: str = Field(..., examples=["High"])
    occupancy: str = Field(..., examples=["Family"])
    past_purchases: list[str] = Field(default_factory=list)


class RecommendationItem(BaseModel):
    product_id: str
    name: str
    reason: str
    score: float = Field(..., ge=0.0, le=1.0)


class RecommendationsResponse(BaseModel):
    recommendations: list[RecommendationItem]


# --- Safety score ---


class SafetyScoreRequest(BaseModel):
    property_type: str
    home_size_sqft: int = Field(..., ge=0)
    entry_points: int = Field(..., ge=0)
    exit_points: int = Field(..., ge=0)
    neighborhood_risk: str
    occupancy: str
    has_existing_security: bool
    previous_incidents: bool


class SafetyScoreResponse(BaseModel):
    score: int = Field(..., ge=0, le=100)
    classification: str
    breakdown: dict[str, float]
    recommendations: list[str]


# --- Fraud ---


class FraudCheckRequest(BaseModel):
    user_id: str
    order_amount: float = Field(..., ge=0)
    payment_method: str
    location: str
    time_of_day: str = Field(..., description="HH:MM 24h format")
    is_new_account: bool
    previous_orders: int = Field(..., ge=0)


class FraudCheckResponse(BaseModel):
    fraud_score: float = Field(..., ge=0.0, le=1.0)
    alert_level: str
    reasons: list[str]
    recommendation: str


# --- Chat ---


class ChatRequest(BaseModel):
    message: str = Field(..., min_length=1)
    session_id: str = Field(..., min_length=1)
    context: dict[str, Any] = Field(default_factory=dict)


class ChatResponse(BaseModel):
    reply: str
    session_id: str


class ErrorResponse(BaseModel):
    detail: str
