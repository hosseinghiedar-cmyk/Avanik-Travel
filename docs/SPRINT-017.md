# Avanik Travel — Sprint 017 v0.4.1

## Booking Persistence Foundation

Additive sprint. Sprint 001–016 remain unchanged.

### Features
- Booking repository abstraction
- WordPress booking table schema
- Booking lookup by booking ID
- Booking lifecycle hook on theme activation
- Booking persistence wiring

### Database

The schema creates `wp_avanik_bookings` using the active WordPress table prefix.

Fields include booking ID, customer ID, booking type, origin, destination, travel date, total amount, status, and timestamps.

### Not included yet
- Payment processing
- External flight/hotel API integration
- Ticket issuance
- Customer-facing booking history
- Cancellation/refund workflow
