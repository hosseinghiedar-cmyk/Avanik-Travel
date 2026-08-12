# Phase 208 — Supplier Sandbox Contract Validation

Phase 208 validates the structural mapping produced by Phase 207 before any non-production supplier calls are introduced.

## Checks
- Provider configured.
- Sandbox URL configured.
- Search, availability, booking, ticket and cancel mappings exist.
- Records missing operations.
- Keeps schema validation pending until a real supplier contract/sample payload is supplied.

## Safety
No booking, ticket issuance, cancellation, or payment request is sent by this phase. Sandbox execution remains blocked until contract/schema validation is explicitly completed.
