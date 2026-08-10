# Phase 78 — Provider Health SLA Risk Alerts

Phase 78 adds operational alert-state tracking for Provider SLA risk transitions.

## Scope
- Reuses Phase 76 risk assessment as the single source of truth.
- Tracks the current risk level and score per provider.
- Detects risk-level transitions.
- Flags providers whose risk score falls below 90.
- Exposes a read-only admin page under Settings → Provider SLA Risk Alerts.
- Emits `avanik_provider_health_sla_risk_alert_evaluated` for future notification integrations.

## Safety
No provider credentials, tokens, request bodies, response bodies, or notification payloads are persisted by this phase.
