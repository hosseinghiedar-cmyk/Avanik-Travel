# Phase 59 — Persistent Provider Health Alert Log

Phase 59 persists normalized provider health alerts and adds cooldown-based deduplication.

## Behavior
- Alerts are stored in a WordPress option with a bounded history of 200 unique dedupe keys.
- Default cooldown is 900 seconds (15 minutes); callers may supply `cooldown_seconds`.
- A repeated alert inside the cooldown is marked `deduplicated` and is not written again.
- Each stored entry contains only provider ID, alert code, severity, message, dedupe key and timestamp.
- Credentials and external request/response bodies are never stored.

## Integration
`NotificationProviderHealthAlert::evaluate()` now passes every alert through `avanik_notification_provider_health_alert_record`.

This creates a stable persistence layer for the next phase, where alerts can be displayed in the admin dashboard and routed to the internal notification center without duplicating provider failures.
