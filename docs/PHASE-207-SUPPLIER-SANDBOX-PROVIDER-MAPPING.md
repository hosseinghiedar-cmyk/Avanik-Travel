# Phase 207 — Supplier Sandbox Provider Mapping

Phase 207 maps Avanik's provider-neutral Supplier API contract to a configured sandbox environment without enabling production execution.

## Operations mapped
- Search
- Availability
- Booking
- Ticket
- Cancel

## Readiness requirements
- Phase 206 contract must be ready.
- Supplier provider must be configured.
- Supplier API endpoint must be a valid URL.
- Supplier sandbox URL must be a valid URL.

## Safety boundary
Sandbox validation remains pending. Booking, ticket issuance, cancellation, and payment execution remain disabled until subsequent phases explicitly validate the live provider contracts.
