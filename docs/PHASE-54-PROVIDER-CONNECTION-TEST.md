# Phase 54 — Provider Connection Test

Adds a safe admin-side connection test for notification providers.

## Admin flow
Settings → Provider Credentials → Test Connection

The test:
- validates the provider ID and admin capability;
- refuses disabled providers;
- refuses providers blocked by the circuit breaker;
- refuses missing credentials;
- requires a registered adapter that explicitly implements `NotificationProviderConnectionTestInterface`;
- passes decrypted credentials only to that adapter;
- never displays or logs credentials;
- catches adapter exceptions and returns a generic failure message.

## Adapter contract
A provider adapter may implement:

`NotificationProviderConnectionTestInterface::testConnection(array $credentials): array`

The adapter returns a small result array such as `ok`, `code`, and `message`.

No real external provider API is hard-coded in this phase. Until a concrete adapter implements the contract, the admin test reports that connection testing is not supported.
