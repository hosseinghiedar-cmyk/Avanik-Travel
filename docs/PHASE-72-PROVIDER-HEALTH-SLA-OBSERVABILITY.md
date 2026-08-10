# Phase 72 — Provider Health SLA Observability

Phase 72 adds a read-only operational report for effective SLA policy and incident performance per notification provider.

## What is reported

For every configured notification provider:
- effective acknowledgement SLA
- effective resolution SLA
- effective downtime SLA
- incident count
- open incidents
- total downtime
- total SLA breaches
- breach counts by acknowledgement / resolution / downtime

## Policy source

The report calls `NotificationProviderHealthSla::policy($provider)`, so Phase 71 provider overrides are reflected automatically. Empty override fields inherit the global policy and zero disables a criterion.

## Dashboard

Settings → Provider Health → SLA به تفکیک Provider

The report is read-only and does not change incidents, SLA policies, notification delivery, or credentials.

## Security

Only operational metadata is displayed. API keys, secrets, tokens, request bodies and provider response bodies are not stored or rendered by this feature.
