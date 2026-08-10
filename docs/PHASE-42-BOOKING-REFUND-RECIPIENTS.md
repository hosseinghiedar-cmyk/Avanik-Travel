# Phase 42 — Booking & Refund Recipient Context

Implemented:
- Notification recipient context resolver for booking and refund events.
- Existing customer/agency/admin IDs are accepted when supplied by event context.
- Booking and refund IDs are carried through dedicated filters so the real repository/schema can resolve recipient IDs without hard-coded database assumptions.
- NotificationCenter now expands a single event into recipient/channel-specific queue records.
- Queue records now persist recipient role, WordPress user ID and delivery channel.
- Worker delivers per-recipient/per-channel jobs rather than one generic event job.
- Email delivery uses the recipient user's locale and editable NotificationTemplates.
- Internal delivery remains provider-neutral and continues through the internal delivery hook.
- SMS and WhatsApp remain disabled until a real provider adapter is configured.

Compatibility:
- Legacy refund events without resolved recipients are still queued for the customer email path, avoiding a breaking change while booking/refund schemas are connected through their existing filters.

Next phase:
- add concrete booking/repository recipient lookup once the canonical booking-to-agency relation is finalized;
- add agency/admin inbox views;
- add per-event preference rules;
- add notification delivery metrics.