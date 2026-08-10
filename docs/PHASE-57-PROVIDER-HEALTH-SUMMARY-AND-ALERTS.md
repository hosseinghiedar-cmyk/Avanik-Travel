# Phase 57 — Provider Health Summary & Alerts

Phase 57 adds a normalized health classification layer on top of the Phase 56 dashboard.

## Statuses
- Healthy: provider is enabled, credentials exist, and the latest test succeeded.
- Disabled: provider is intentionally disabled.
- Attention: provider is enabled but credentials are missing.
- Unhealthy: the latest recorded connection test did not succeed.
- Slow: the latest successful test exceeded the default 2000 ms threshold.

The threshold can be supplied by callers through `slow_threshold_ms` without hard-coding it into the provider adapter.

The summary is exposed through `avanik_notification_provider_health_summary` so future dashboards, REST endpoints, alerts, and monitoring integrations can consume the same normalized status.

No credential values or provider response bodies are exposed.
