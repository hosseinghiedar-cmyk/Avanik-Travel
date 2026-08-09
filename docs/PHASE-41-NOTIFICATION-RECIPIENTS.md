# Phase 41 — Recipient Resolution & User Notification Preferences

Implemented:
- Per-user notification preferences stored in WordPress user meta.
- Preferences UI shortcode: `[avanik_notification_preferences]`.
- Default channels: Email and Internal enabled; SMS and WhatsApp disabled until real providers are configured.
- Recipient resolver for customer, agency and admin IDs supplied by an event payload/context.
- Role-aware filtering hook: `avanik_notification_role_allowed`.
- Recipient channel data is resolved before delivery, keeping provider selection separate from business events.
- Refund-specific recipient helper is available for future refund event integration.

Important architecture note:
- This phase does not invent agency/admin IDs from an unknown schema. Existing booking/refund services can pass the appropriate IDs into the resolver when their real schema is available.
- Existing email delivery remains compatible; SMS/WhatsApp remain opt-in architecture only.

Next phase:
- connect real booking/refund agency recipient lookup;
- make NotificationCenter consume the resolver before queueing;
- add admin notification preferences and agency inbox;
- add per-event preferences rather than channel-only preferences.