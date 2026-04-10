"""POST /ai/chat — LLM chatbot with in-memory session history.

Providers:
- Anthropic (default): set ANTHROPIC_API_KEY (+ optional ANTHROPIC_MODEL)
- Groq: set AI_PROVIDER=groq + GROQ_API_KEY (+ optional GROQ_MODEL)
"""

import os

from anthropic import Anthropic
from fastapi import APIRouter, HTTPException
import httpx

from ai_engine.schemas.schemas import ChatRequest, ChatResponse
from ai_engine.utils.helpers import (
    append_chat_message,
    format_context_for_prompt,
    get_chat_history,
    get_logger,
)

logger = get_logger(__name__)

router = APIRouter(tags=["chat"])

SYSTEM_BASE = (
    "You are SafeNest's AI security advisor. Help customers choose the right home security "
    "products. Be concise, helpful, and always suggest products relevant to their home profile. "
    "Do not discuss topics unrelated to home security."
)

DEFAULT_MODEL = "claude-sonnet-4-20250514"
DEFAULT_GROQ_MODEL = "llama-3.1-8b-instant"


def _provider() -> str:
    return (os.getenv("AI_PROVIDER", "anthropic") or "anthropic").strip().lower()


def _extract_text_from_openai_like(payload: dict) -> str:
    try:
        return (
            payload.get("choices", [{}])[0]
            .get("message", {})
            .get("content", "")
            .strip()
        )
    except Exception:
        return ""


def _call_groq(system_prompt: str, messages: list[dict]) -> str:
    api_key = os.getenv("GROQ_API_KEY", "").strip()
    if not api_key:
        logger.error("GROQ_API_KEY is not set")
        raise HTTPException(
            status_code=503,
            detail="Chat service unavailable: configure GROQ_API_KEY in ai_engine/.env",
        )

    model = (os.getenv("GROQ_MODEL", DEFAULT_GROQ_MODEL) or DEFAULT_GROQ_MODEL).strip()
    # Groq uses OpenAI-compatible Chat Completions.
    req = {
        "model": model,
        "messages": [{"role": "system", "content": system_prompt}, *messages],
        "temperature": 0.3,
        "max_tokens": 512,
    }
    try:
        with httpx.Client(timeout=30.0) as client:
            r = client.post(
                "https://api.groq.com/openai/v1/chat/completions",
                headers={"Authorization": f"Bearer {api_key}"},
                json=req,
            )
            data = r.json()
            if r.status_code >= 400:
                detail = (
                    data.get("error", {}).get("message")
                    or data.get("message")
                    or str(data)[:300]
                )
                raise HTTPException(status_code=502, detail=detail)
            text = _extract_text_from_openai_like(data)
            return text or "(No response text)"
    except HTTPException:
        raise
    except Exception as e:
        logger.exception("Groq API error: %s", e)
        raise HTTPException(status_code=502, detail="Upstream AI provider error") from e


@router.post("/chat", response_model=ChatResponse)
def post_chat(body: ChatRequest) -> ChatResponse:
    context_block = format_context_for_prompt(body.context)
    system_prompt = f"{SYSTEM_BASE}\n\n{context_block}"

    append_chat_message(body.session_id, "user", body.message)
    history = get_chat_history(body.session_id)
    # Build messages for API: all but the last user message are history; last is current
    messages = [{"role": m["role"], "content": m["content"]} for m in history]

    prov = _provider()
    if prov == "groq":
        reply = _call_groq(system_prompt, messages)
    else:
        api_key = os.getenv("ANTHROPIC_API_KEY", "").strip()
        if not api_key:
            logger.error("ANTHROPIC_API_KEY is not set")
            raise HTTPException(
                status_code=503,
                detail="Chat service unavailable: configure ANTHROPIC_API_KEY in ai_engine/.env",
            )
        model = os.getenv("ANTHROPIC_MODEL", DEFAULT_MODEL).strip() or DEFAULT_MODEL
        try:
            client = Anthropic(api_key=api_key)
            resp = client.messages.create(
                model=model,
                max_tokens=1024,
                system=system_prompt,
                messages=messages,
            )
            text_parts = []
            for block in resp.content:
                if hasattr(block, "text"):
                    text_parts.append(block.text)
            reply = "".join(text_parts).strip() or "(No response text)"
        except Exception as e:
            logger.exception("Anthropic API error: %s", e)
            raise HTTPException(status_code=502, detail="Upstream AI provider error") from e

    append_chat_message(body.session_id, "assistant", reply)
    return ChatResponse(reply=reply, session_id=body.session_id)
