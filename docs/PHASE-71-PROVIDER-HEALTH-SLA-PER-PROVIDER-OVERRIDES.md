# Phase 71 — Provider Health SLA Per-Provider Overrides

Phase 70 introduced global Provider Health SLA settings. Phase 71 adds provider-specific overrides without changing the Phase 67 evaluator contract.

## Policy precedence

1. Global SLA defaults are loaded from `avanik_provider_health_sla_policy`.
2. If a provider has an override in `avanik_provider_health_sla_overrides`, the configured provider value replaces the corresponding global value.
3. Empty override fields inherit the global value.
4. `0` explicitly disables that SLA criterion for the provider.

## Admin UI

Settings → Provider Health SLA

The page now has a per-provider override table for:
- Acknowledgement
- Resolution
- Downtime

Provider definitions come from `NotificationProviderSettings::get()`.

## Example

Global:
- acknowledgement: 900s
- resolution: 3600s
- downtime: 3600s

SMS provider override:
- acknowledgement: 300s
- resolution: 1800s
- downtime: 0

Effective SMS policy:
- acknowledgement: 300s
- resolution: 1800s
- downtime: disabled

No credentials, request bodies, or provider responses are stored by this feature.
