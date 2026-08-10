# Phase 52 — Dynamic Notification Provider Resolution

Phase 52 connects the provider configuration from Phase 51 to runtime provider selection.

## Resolution rules
1. Read enabled providers from `NotificationProviderSettings`.
2. Keep only providers supporting the requested channel.
3. Exclude providers disabled by the Phase 49 circuit breaker.
4. Exclude providers without an Adapter name.
5. Select the lowest numeric priority; ties are resolved by provider ID.
6. If no configured provider is usable, retain the current/core provider value.

## Runtime hook
`avanik_notification_provider_for_channel`

This keeps provider selection configurable without hard-coding Sepehr360, Moghim24, SMS vendors, email vendors, or any other external service into the notification core.

## Next step
The next phase can add secure credential storage and per-provider connection/test controls without changing the resolver contract.
