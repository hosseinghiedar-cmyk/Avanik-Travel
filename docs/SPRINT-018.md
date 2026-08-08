# Sprint 018 — Booking Service Layer v0.4.2

## Objective
Add a service layer between booking UI and persistence.

## Included
- BookingService for validated creation and cancellation.
- BookingActions for authenticated WordPress form actions.
- Ownership check before cancellation.
- Nonce checks for state-changing requests.
- Error handling with WP_Error.

## Not included
- Payment gateway.
- External flight/hotel APIs.
- Ticket issuance.
- Email/SMS notifications.

## Regression
Sprint 001-017 remain unchanged except for the new Sprint 018 integration points.
