# Phase 204 — Production Supplier Integration Readiness

Phase 204 moves the project out of the repeated audit/verification chain and into production-readiness work.

## Scope
- Defines a production supplier configuration boundary using `AVANIK_SUPPLIER_PROVIDER`, `AVANIK_SUPPLIER_API_URL`, and `AVANIK_SUPPLIER_API_KEY`.
- Detects whether the production supplier configuration exists.
- Explicitly records that a live connection has **not** been tested by this phase.
- Keeps `execution_allowed = false` and `booking_release_allowed = false`.
- Does not invent a supplier, endpoint, credentials, booking, availability, or live API response.

## Required before live integration
1. Set the real supplier/provider name.
2. Configure the real supplier API endpoint.
3. Configure the credential through the production secret store/environment.
4. Implement the provider-specific adapter using the existing supplier boundary.
5. Run an explicit sandbox/live connectivity test in the next integration phase.
