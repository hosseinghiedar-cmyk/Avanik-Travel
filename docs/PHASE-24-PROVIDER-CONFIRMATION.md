# Phase 24 — Provider Confirmation

Implemented:
- Added `ProviderConfirmationService` to orchestrate confirmation after a booking is paid.
- Provider selection is resolved from the booking's provider key through `ProviderManager`.
- Passenger data is passed through the existing `BookingPassengers` access path.
- A successful provider booking transitions the booking from `paid` to `confirmed` and stores the provider reference in the lifecycle metadata.
- Added `BookingProviderBridge` to listen for `avanik_payment_paid` and trigger provider confirmation.
- Provider failures emit `avanik_provider_confirmation_failed` for retry/notification handling.

The provider abstraction remains compatible with Sepehr360, Moghim24, future providers, and Avanik-owned inventory. The provider adapter must implement the existing `FlightProviderInterface` contract.

Production note: provider calls should be made idempotent before production. A retry mechanism should use a stable provider request key and must never issue duplicate bookings. Ticket issuance remains a separate lifecycle step after confirmation.