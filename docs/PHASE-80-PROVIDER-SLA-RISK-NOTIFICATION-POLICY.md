# Phase 80 — Provider SLA Risk Notification Policy

Phase 80 adds an administrator-controlled policy layer for Provider SLA risk notifications.

## Policy
- Critical, high, medium and low risk levels can be enabled/disabled independently.
- Critical/high/medium alerts can carry an escalation role in the event payload.
- A configurable cooldown from 0 to 1440 minutes can suppress repeated alerts for the same provider state.
- Phase 76 remains the risk calculation source of truth.
- Phase 78 remains the risk transition/state tracker.
- Phase 79 remains the NotificationCenter bridge.

## Admin
Settings → Provider SLA Risk Policy

## Safety
The policy only controls notification behavior. It does not store provider credentials, tokens, request bodies, response bodies or notification secrets.
