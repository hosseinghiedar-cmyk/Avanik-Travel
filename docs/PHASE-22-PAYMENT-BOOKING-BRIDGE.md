# Phase 22 — Payment → Booking Lifecycle Bridge

Implemented:
- Successful payment now emits `avanik_payment_paid` after the payment record is persisted.
- `BookingPaymentBridge` listens to that event and transitions an eligible booking from `pending` or `awaiting_payment` to `paid`.
- Gateway-specific code remains behind the existing payment service/gateway abstraction.
- The bridge is registered from the WordPress bootstrap.

Important:
- Provider confirmation and ticket issuance are intentionally not triggered by payment alone. They require their own provider/fulfillment workflow.
- Payment callbacks/webhooks must be made idempotent before production so repeated gateway callbacks cannot duplicate state changes or downstream actions.

Next: harden payment idempotency and gateway callback handling, then connect provider confirmation and ticketing to the booking lifecycle.