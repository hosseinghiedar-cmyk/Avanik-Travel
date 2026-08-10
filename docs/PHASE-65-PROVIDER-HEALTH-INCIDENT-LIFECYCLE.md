# Phase 65 — Provider Health Incident Lifecycle

Phase 65 introduces a provider-level incident abstraction above individual health alerts.

## Lifecycle
- A new incident opens when a provider receives a health alert and has no open incident.
- Repeated alerts for the same provider update the existing open incident instead of creating a new incident.
- A `provider_recovered` event resolves the provider's open incident.
- An incident stores a stable incident key, severity, first/last alert code, opened/resolved timestamps, and optional acknowledgement metadata.

## Admin dashboard
The Provider Health page now shows Incident History separately from raw Alert History, allowing operators to distinguish one prolonged outage from many individual health checks.

## Security
Only operational metadata is stored. Provider credentials, tokens, and external request/response bodies are not persisted in incident state.
