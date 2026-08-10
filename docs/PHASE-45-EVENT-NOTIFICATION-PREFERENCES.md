# Phase 45 — Event-level Notification Preferences

Phase 45 extends the existing user notification preferences without replacing the channel defaults introduced earlier.

## Default channels

Users still have global defaults for:
- Email
- Internal
- SMS
- WhatsApp

Current project defaults remain Email + Internal enabled, SMS + WhatsApp disabled.

## Event-level controls

Supported events:
- booking_created
- booking_confirmed
- booking_cancelled
- ticket_issued
- refund_status
- payment_status

Each event can independently enable or disable every channel.

## Resolution order

1. Read the user's global channel preferences.
2. If an event-specific setting exists, use that setting for the event.
3. Keep role authorization through `avanik_notification_role_allowed`.
4. Return the resolved channel map to the existing notification queue.

This keeps the NotificationCenter provider-agnostic and allows future SMS/WhatsApp providers to be enabled without changing the preference model.
