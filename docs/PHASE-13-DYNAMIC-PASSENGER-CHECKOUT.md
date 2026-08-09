# Phase 13 — Dynamic Multi-Passenger Checkout

Implemented:
- Checkout now renders a passenger form for the selected quantity.
- Passenger fields are driven by `PassengerRequirements::for_product()`.
- Domestic/international requirements therefore flow directly into checkout.
- Passenger data is validated before a booking is created.
- Exactly N passenger records are required for quantity N.
- Passenger records are stored in `booking_passengers` using the booking's string booking ID.
- Booking/passenger rollback is performed if a passenger insert or payment initialization fails.
- Existing payment gateway selection remains unchanged (manual/card-to-card or ZarinPal adapter).

Important production follow-up:
- Encrypt sensitive passport data at rest.
- Add masked views and role-based access for passport information.
- Add agency-configurable custom fields to the editor and checkout.
- Add a real database transaction abstraction when supported by the site's database configuration.
- Test all product types and legacy bookings before production deployment.