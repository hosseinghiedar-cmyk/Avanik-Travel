# Phase 38 — Notification Center

Implemented:
- Admin notification settings page under WordPress Settings → Avanik Notifications.
- Configurable channels: Email, SMS, WhatsApp and Internal.
- Per-channel enable/disable flags and recipient roles: Customer, Agency, Admin.
- Persistent notification queue table with status, attempts, payload and last error fields.
- Refund status events are queued through the notification center rather than forcing a provider-specific implementation.
- Existing refund notification hook remains compatible with future SMS/WhatsApp adapters.
- All settings writes require `manage_options` and a WordPress nonce.

Current behavior:
- Email remains the only actual external delivery channel wired by the existing refund notification component.
- SMS/WhatsApp are configuration-ready but intentionally do not call undocumented providers.
- Queue persistence is implemented; a worker/retry dispatcher should be added before production use.

Next phase:
- notification queue worker with retry/backoff and dead-letter state;
- editable message templates;
- provider adapters configurable from admin;
- internal notification inbox and read/unread state.