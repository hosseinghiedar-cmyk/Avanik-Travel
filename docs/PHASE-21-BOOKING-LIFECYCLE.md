# Phase 21 — Booking Lifecycle

Implemented:
- Expanded booking statuses: pending, awaiting_payment, paid, confirmed, ticketed, cancelled, refunded, failed, expired.
- Added guarded transition rules to prevent invalid state changes.
- Added payment status, currency and lifecycle timestamps to booking schema.
- Added a central `BookingLifecycle::transition()` method that updates timestamps and emits `avanik_booking_status_changed`.

Core flow:
`pending -> awaiting_payment -> paid -> confirmed -> ticketed`

Alternative terminal paths include cancellation, failure and expiration; a cancelled booking may proceed to refunded.

Next: connect payment services to `paid`, provider/ticketing workflow to `confirmed/ticketed`, implement cancellation/refund policy, and add role-aware booking management UI.