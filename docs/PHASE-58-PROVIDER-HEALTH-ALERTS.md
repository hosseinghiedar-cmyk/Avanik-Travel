# Phase 58 — Provider Health Alerts

Phase 58 adds a normalized alert evaluator on top of the Phase 57 health classification.

## Alert classes
- `provider_unhealthy` / critical — latest provider test failed.
- `credentials_missing` / warning — provider is enabled without credentials.
- `provider_slow` / warning — response exceeded the configured slow threshold.
- `provider_disabled` / info — provider is disabled.

Each alert has a stable `dedupe_key` (`provider:code`) so a future notification/monitoring layer can avoid sending the same alert repeatedly.

The evaluator is exposed through `avanik_notification_provider_health_alert` and does not expose credential values or provider response bodies.
