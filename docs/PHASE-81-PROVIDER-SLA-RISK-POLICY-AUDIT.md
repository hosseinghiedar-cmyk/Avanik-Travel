# Phase 81 — Provider SLA Risk Policy Audit

Phase 81 adds an audit trail for changes to the Provider SLA Risk Notification Policy.

## Scope
- Records only actual policy changes.
- Captures timestamp, WordPress user ID, and changed policy fields.
- Keeps the latest 100 entries.
- Provides a read-only admin page under Settings → Provider SLA Risk Policy Audit.
- Does not store provider credentials, tokens, request/response bodies, or notification payloads.

## Event
The policy emits `avanik_provider_health_sla_risk_notification_policy_saved` with the previous and new policy state. The audit listener records the field-level delta.
