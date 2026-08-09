# Phase 37 — Refund Notifications & Timeline

Implemented:
- Refund status notification delivery hook.
- Customer email notification on refund status changes when a valid account email exists.
- Extensible `avanik_refund_notification_queued` hook for SMS, WhatsApp or external notification providers later.
- Customer-facing refund timeline shortcode: `[avanik_refund_timeline]`.
- Timeline is scoped through the existing customer refund ownership check.
- Timeline exposes audit action, timestamp and note while intentionally excluding payment/provider credentials and settlement internals.

Current delivery is intentionally email-first and uses WordPress `wp_mail`; no external SMS/WhatsApp vendor has been hard-coded.

Next phase:
- agency notifications;
- notification preferences and templates in admin;
- queue/retry mechanism for reliable delivery;
- finance reconciliation export;
- automated tests.