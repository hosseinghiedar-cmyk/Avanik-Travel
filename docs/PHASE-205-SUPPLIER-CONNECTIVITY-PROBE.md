# Phase 205 — Supplier Connectivity Probe

Phase 205 moves the project from supplier configuration readiness to a safe connectivity test.

## Behavior
- Reads `avanik_supplier_api_url`.
- Validates the configured URL before any request.
- Performs a read-only WordPress HTTP GET with SSL verification enabled.
- Records HTTP status and response time.
- Does not send credentials, create bookings, reserve inventory, issue tickets, charge payments, or enable execution.
- Requires an administrator capability to run/view the probe.

## Important
A reachable endpoint does not mean the supplier API is production-ready. Authentication, schema compatibility, search contract, booking contract, ticketing and error handling must be validated in later phases.
