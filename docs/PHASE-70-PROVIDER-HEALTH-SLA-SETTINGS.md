# Phase 70 — Provider Health SLA Settings

Phase 70 makes the Provider Health SLA policy configurable from WordPress admin instead of requiring a code-level filter.

## Admin
Settings → Provider Health SLA

Administrators can configure:
- Acknowledgement threshold in seconds.
- Resolution threshold in seconds.
- Downtime threshold in seconds.

A value of `0` disables that SLA criterion.

## Defaults
- Acknowledgement: 900 seconds (15 minutes).
- Resolution: 3600 seconds (60 minutes).
- Downtime: 3600 seconds (60 minutes).

## Storage and safety
Values are stored in the WordPress option `avanik_provider_health_sla_policy`. Input is normalized to non-negative integers. Saving requires the `manage_options` capability and a WordPress nonce.

The settings layer feeds the existing `avanik_notification_provider_health_sla_policy` filter, so the read-only SLA evaluator and Phase 68 notifier keep their existing responsibilities.

No provider credentials, tokens, request bodies or response bodies are stored by this phase.
