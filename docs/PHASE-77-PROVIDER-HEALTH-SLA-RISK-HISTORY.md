# Phase 77 — Provider Health SLA Risk History

Phase 77 adds hourly historical snapshots of the Provider Health SLA risk score.

## What it adds
- Hourly snapshots of the existing Phase 76 risk assessment.
- Up to 365 snapshots are retained.
- Read-only admin page: Settings → Provider SLA Risk History.
- Last 48 snapshots are shown per provider.
- No credentials, tokens, notification payloads, request bodies, or provider response bodies are stored.

## Data source
Snapshots reuse `NotificationProviderHealthSlaRisk::assess(30)` so Phase 76 remains the single source of truth for risk calculation.

## Scheduling
The snapshot event is registered as `avanik_provider_health_sla_risk_snapshot` with an hourly WordPress cron interval. A snapshot is also available through the existing Provider Health SLA check event.
