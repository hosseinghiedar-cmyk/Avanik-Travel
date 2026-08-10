# Phase 87 — Provider SLA Risk Policy Audit Notification Delivery Audit

Phase 87 adds read-only delivery observability for the SLA risk policy audit integrity failure/recovery notifications.

## Scope
- Records delivery attempts/results emitted by NotificationCenter.
- Covers integrity failure and recovery notification events only.
- Keeps the latest 200 delivery events.
- Provides an administrator-only read-only page under Settings.
- Stores notification id, event, role, user, channel, status, provider and error code.
- Does not store notification payloads or provider credentials.

## Integration
The audit listens to `avanik_notification_delivery_result`, so queueing, retry and delivery remain owned by NotificationCenter.
