# Phase 40 — Notification Templates & Internal Inbox

Implemented:
- Admin-editable Persian and English notification templates.
- Placeholder rendering for event payloads, including refund ID, booking ID and status.
- WordPress admin page: Settings → Notification Templates.
- Internal user notification inbox with read/unread state.
- Customer-scoped notification queries and nonce-protected mark-as-read action.
- Notification inbox shortcode: `[avanik_notifications]`.
- Notification template and inbox components registered in the main bootstrap.

Architecture:
- Templates are independent of delivery providers.
- Internal notifications are stored separately from the external delivery queue.
- Future agency/admin inboxes can use the same table and role-scoped query layer.

Next phase:
- agency/admin recipient resolution;
- notification preferences per user;
- template versioning and preview/test-send;
- delivery metrics and operational dashboard.