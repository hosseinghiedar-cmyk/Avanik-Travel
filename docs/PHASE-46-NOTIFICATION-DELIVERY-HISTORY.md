# Phase 46 — Notification Delivery History

## Goal
Keep an immutable operational history for each notification delivery attempt/result without coupling the core queue to a specific provider.

## New component
`NotificationDeliveryLog`

Table: `wp_avanik_notification_delivery_log` (uses the active WordPress table prefix)

Fields include:
- queue_id
- event
- role
- user_id
- channel
- provider
- attempt
- status
- provider_message_id
- error_code
- error_message
- metadata
- created_at

## Hooks
- `avanik_notification_delivery_attempt`
- `avanik_notification_delivery_result`

Providers can record provider message IDs, error codes and provider metadata through these hooks. No SMS, WhatsApp or ZarinPal API is assumed.

## Design
The queue remains the source of current delivery state. The delivery log is append-oriented history for troubleshooting and analytics. Future dashboard work can aggregate this table for success/failure rates, provider latency and retry analysis.
