"""Shared helpers: logging setup and in-memory session store for chat."""

from __future__ import annotations

import logging
import sys
from collections import defaultdict
from typing import Any

# In-memory chat history: session_id -> list of {"role": "user"|"assistant", "content": str}
_chat_sessions: dict[str, list[dict[str, str]]] = defaultdict(list)

MAX_MESSAGES_PER_SESSION = 10  # last 10 messages total (user + assistant pairs count as 2)


def setup_logging(level: int = logging.INFO) -> None:
    """Configure root logger once (idempotent enough for dev)."""
    root = logging.getLogger()
    if root.handlers:
        return
    handler = logging.StreamHandler(sys.stdout)
    handler.setFormatter(
        logging.Formatter("%(asctime)s | %(levelname)s | %(name)s | %(message)s")
    )
    root.addHandler(handler)
    root.setLevel(level)


def get_logger(name: str) -> logging.Logger:
    return logging.getLogger(name)


def append_chat_message(session_id: str, role: str, content: str) -> None:
    """Append a message and trim session to last MAX_MESSAGES_PER_SESSION entries."""
    history = _chat_sessions[session_id]
    history.append({"role": role, "content": content})
    if len(history) > MAX_MESSAGES_PER_SESSION:
        del history[: len(history) - MAX_MESSAGES_PER_SESSION]


def get_chat_history(session_id: str) -> list[dict[str, str]]:
    return list(_chat_sessions.get(session_id, []))


def format_context_for_prompt(context: dict[str, Any]) -> str:
    """Turn optional client context into a short string for the system prompt."""
    if not context:
        return "No additional profile context provided."
    parts = [f"{k}: {v}" for k, v in context.items()]
    return "Customer context — " + "; ".join(parts)


def normalize_str(s: str) -> str:
    return s.strip().lower()


def risk_bucket(neighborhood_risk: str) -> str:
    """Map free-text risk to a bucket for collaborative filtering keys."""
    r = normalize_str(neighborhood_risk)
    if r in ("low", "medium", "moderate", "high"):
        return r
    if "high" in r:
        return "high"
    if "low" in r:
        return "low"
    if "mod" in r or "medium" in r:
        return "medium"
    return "medium"
