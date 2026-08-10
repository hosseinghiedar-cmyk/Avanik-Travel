# Phase 50 — Notification Provider Adapter Boundary

Phase 50 connects the circuit-breaker state from Phase 49 to a provider-neutral notification adapter boundary.

## Adapter contract
`NotificationProviderAdapterInterface` exposes:
- `provider()`
- `supports(channel)`
- `send(channel, event, payload, user_id)`

Adapters are discovered through the `avanik_notification_provider_adapters` filter.

## Circuit breaker enforcement
External provider dispatch resolves a provider through `avanik_notification_provider_for_channel`. Before an external provider is used, `NotificationProviderHealth::is_disabled()` is checked.

If the provider/channel is disabled, delivery is blocked and the `avanik_notification_delivery_blocked` action is emitted with reason `circuit_open`.

## Why this matters
The core notification system remains independent from a specific SMS, WhatsApp, email, or payment vendor. A future provider can be added as an adapter without rewriting the queue, history, analytics, or health layers.

No real external API credentials or provider endpoints are introduced in this phase.
