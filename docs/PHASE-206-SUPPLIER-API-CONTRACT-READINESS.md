# Phase 206 — Supplier API Contract Readiness

Phase 206 moves Avanik from connectivity probing to a provider-neutral Supplier API contract.

## Required operations
- Search: GET `/search`
- Availability: GET `/availability`
- Booking: POST `/bookings`
- Ticket: POST `/tickets`
- Cancel: POST `/bookings/{id}/cancel`

## Safety
- No live booking is executed.
- No ticket is issued.
- No cancellation is executed.
- `booking_execution_allowed` remains false.
- Real provider mapping and sandbox validation remain required before execution.
