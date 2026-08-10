# Phase 49 — Notification Provider Circuit Breaker

Adds controlled provider failure protection on top of Phase 48 health monitoring.

## Behavior
- Default failure threshold: 10 consecutive failures.
- Threshold is configurable through `avanik_notification_provider_failure_threshold` and clamped to 3–50.
- When a provider/channel reaches the threshold it is marked disabled.
- A successful delivery resets consecutive failures to zero.
- Admin can manually Enable, Disable, or Reset a provider/channel from Settings → Provider Health.
- All admin actions require `manage_options` and a WordPress nonce.

## Important
The circuit breaker is an operational state layer. It does not invent provider APIs and does not delete queued history.

The current delivery adapters should consult `NotificationProviderHealth::is_disabled()` before sending through a specific external provider when such a provider is introduced. Core/internal delivery remains unaffected unless explicitly integrated with this gate.
