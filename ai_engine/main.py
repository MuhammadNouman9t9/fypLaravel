"""
SafeNest AI Engine — FastAPI entrypoint.

Run from repository root:
  uvicorn ai_engine.main:app --host 127.0.0.1 --port 8001

Or:
  python -m ai_engine.main
"""

from __future__ import annotations

from pathlib import Path

from dotenv import load_dotenv
from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware

from ai_engine.routers import chatbot, fraud_detection, recommendations, safety_score
from ai_engine.utils.helpers import setup_logging

# Load secrets from ai_engine/.env (optional; OS env vars still work)
load_dotenv(Path(__file__).resolve().parent / ".env")

setup_logging()

app = FastAPI(
    title="SafeNest AI Engine",
    description="Recommendations, safety scoring, fraud checks, and security chatbot.",
    version="1.0.0",
)

app.add_middleware(
    CORSMiddleware,
    allow_origins=["http://127.0.0.1:8000", "http://localhost:8000"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

app.include_router(recommendations.router, prefix="/ai")
app.include_router(safety_score.router, prefix="/ai")
app.include_router(fraud_detection.router, prefix="/ai")
app.include_router(chatbot.router, prefix="/ai")


@app.get("/health")
def health():
    return {"status": "ok", "service": "ai_engine"}


if __name__ == "__main__":
    import uvicorn

    uvicorn.run(
        "ai_engine.main:app",
        host="127.0.0.1",
        port=8001,
        reload=True,
    )
