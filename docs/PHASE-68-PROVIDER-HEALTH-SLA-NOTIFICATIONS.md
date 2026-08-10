# Phase 68 — Provider Health SLA Notifications

Phase 68 connects the read-only SLA evaluator to the existing internal NotificationCenter.

## Behavior
- A WordPress cron task runs every five minutes.
- Current SLA breaches are evaluated through `NotificationProviderHealthSla`.
- Each `incident_key:type` pair has a 15-minute notification cooldown.
- Breach notifications are queued only for WordPress administrators.
- Delivery uses the existing `provider_health_sla_breach` notification event and the internal channel.
- Notification history is bounded to 500 keys.

## Security
Only provider ID, incident key, breach type, threshold, actual elapsed time and status are included. Credentials and external request/response bodies are never included.

## Templates
A Persian and English template was added to the Notification Templates settings page and can be customized by administrators.

## Separation of concerns
The SLA evaluator remains read-only. The notifier is responsible only for scheduling, deduplication and queueing; the NotificationCenter remains responsible for delivery and retry behavior.
