# Phase 6 — Passenger Checkout & Payment Selection

Implemented:
- Product-specific checkout shortcode `[avanik_product_checkout]`
- Login requirement for checkout
- Quantity and capacity validation
- Passenger name, mobile and email capture
- Booking schema extended with product and passenger fields
- BookingRepository updated to persist those fields
- Payment initialization reused through PaymentService
- Current payment choices: manual/card-to-card and Zarinpal gateway key
- Payment remains gateway-abstracted for future international methods

Important: the Zarinpal option is now represented in checkout and routed by the gateway key, but the live Zarinpal request/verify adapter must be implemented/configured before production transactions. Card-to-card remains manual confirmation until its operational confirmation UI is completed.

Next: payment pages, Zarinpal request/verify adapter, card-to-card receipt upload, booking availability locking, and agency/admin booking management.