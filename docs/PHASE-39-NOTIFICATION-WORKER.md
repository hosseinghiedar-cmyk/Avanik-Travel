# Phase 39 — Notification Worker & Retry System

Implemented:
- WordPress scheduled notification worker running every minute.
- Batch processing of queued/retry notifications (up to 20 per run).
- Exponential retry backoff: 60s, 120s, 240s, 480s, then dead-letter after the fifth failed attempt.
- Queue status tracking: queued, retry, sent, dead.
- Attempt count and last error persistence.
- Admin queue view under Settings → Avanik Notifications.
- Manual Retry action for failed notifications with capability and nonce checks.
- Queue index on `updated_at` for worker lookup efficiency.
- Delivery boundary remains provider-neutral through `avanik_notification_delivery` hook.

Production note:
- Email delivery uses the existing WordPress mail layer.
- SMS and WhatsApp providers are not invented; they can be attached to the delivery hook later.
- WordPress cron is suitable for the initial implementation, but a real server-side cron invoking WP-Cron should be configured for reliable production delivery.

Next phase:
- editable notification templates and localization;
- internal inbox/read state;
- provider adapter configuration for SMS/WhatsApp;
- delivery metrics and operational alerts.