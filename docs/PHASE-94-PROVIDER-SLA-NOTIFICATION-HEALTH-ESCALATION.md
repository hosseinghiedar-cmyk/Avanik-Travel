# Phase 94 — Provider SLA Notification Health Escalation

Phase 94 adds a second escalation threshold on top of the Phase 91 health alert.

## Behavior
- The existing Phase 91 alert remains the first-level signal at 3 consecutive failures.
- Phase 94 escalates at 5 consecutive failures.
- Escalation is emitted through `avanik_provider_sla_notification_health_escalated`.
- A one-hour cooldown prevents repeated escalation storms.
- Recovery clears the active escalation state while preserving the escalation count/history state.
- An administrator-only Settings page exposes the current escalation state.

No notification payloads or credentials are persisted by this component.
