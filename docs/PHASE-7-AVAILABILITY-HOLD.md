# Phase 7 — Availability & Temporary Hold

Implemented:
- Inventory hold table
- Product capacity checks
- Temporary 15-minute reservation hold
- Expired hold release
- Booking-confirmed hold conversion
- Booking-cancelled hold release
- Availability failure state
- Bootstrap wiring in the Avanik theme

The availability layer is intentionally independent from ZarinPal. Payment gateways can change without changing inventory rules.

Next: transactional booking flow, customer booking dashboard, agency booking dashboard, and production-grade payment callback/adapter integration.