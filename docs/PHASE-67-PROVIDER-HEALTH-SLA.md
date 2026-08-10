# Phase 67 — Provider Health SLA

Phase 67 adds an SLA evaluation layer over the Provider Health Incident lifecycle.

## Default thresholds
- Acknowledgement: 15 minutes
- Resolution: 60 minutes
- Downtime: 60 minutes

The policy can be overridden through the `avanik_notification_provider_health_sla_policy` filter per provider.

## Breach types
- `acknowledgement`: an open, unacknowledged incident exceeded the acknowledgement threshold.
- `resolution`: an incident remained open beyond the resolution threshold.
- `downtime`: elapsed incident time exceeded the downtime threshold.

The evaluator is read-only: it does not mutate incidents or send notifications. This keeps SLA measurement separate from incident state and routing. The Provider Health dashboard exposes the active policy and current breach list.

## Security
Only provider IDs, incident IDs, timestamps and SLA metadata are used. Provider credentials and external request/response bodies are not included.
