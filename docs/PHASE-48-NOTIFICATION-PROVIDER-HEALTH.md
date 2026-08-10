# Phase 48 — Notification Provider Health

Adds an operational health layer on top of delivery analytics.

## Dashboard
WordPress → Settings → Provider Health

Tracks each provider/channel pair:
- total attempts
- sent count
- failed count
- success rate
- consecutive failures
- last status
- last error code/message
- last provider message ID
- last seen timestamp
- active/disabled state

## Design
No provider API is hard-coded. The monitor consumes the existing `avanik_notification_delivery_result` hook, so future email/SMS/WhatsApp providers can report through the same contract.

Automatic disabling is intentionally not performed in Phase 48. The state field is prepared for a later controlled circuit-breaker phase so a provider cannot be silently disabled by transient failures.
