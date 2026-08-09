# Phase 23 — Payment Idempotency

Implemented:
- Added a dedicated payment-callback claim table with a unique callback key.
- Added claim/complete/release operations so a gateway callback can be processed once.
- Payment schema version advanced and callback table installation is registered.
- Payment idempotency module is loaded and installed from the WordPress bootstrap.

Integration rule:
- Each real gateway callback/webhook must derive a stable provider callback key (for example gateway + provider transaction/reference/event ID) and call `PaymentIdempotency::claim()` before changing payment state.
- If claim returns false, the callback is a duplicate or already being processed and must not repeat side effects.
- Mark the claim completed only after the payment state transition and downstream event handling succeed.
- Release the claim when processing fails before completion so a legitimate retry can be attempted.

Production note:
- The existing `PaymentService::mark_paid()` remains the application-level state transition. Gateway adapters should use the idempotency layer at their callback boundary before invoking it.
- Provider confirmation/ticketing is deliberately not triggered by payment success alone.