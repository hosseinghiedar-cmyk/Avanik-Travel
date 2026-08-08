# Phase 6 — Payment Checkout

Implemented directly in the WordPress Avanik layer:

- Customer payment method selection
- Zarinpal gateway selection foundation
- Card-to-card payment selection
- Card-to-card receipt upload
- Pending payment verification status
- Admin payment verification queue
- Admin approve/reject actions
- Successful manual payment confirms the related booking
- Nonce and capability checks

Shortcodes:
- `[avanik_payment_checkout]`
- `[avanik_card_to_card]`

Important: Zarinpal request/verify API credentials and production callback are intentionally not hard-coded yet. The gateway abstraction remains ready for the real merchant configuration.

Next: implement the production Zarinpal request/verify callback, booking availability locking, customer booking dashboard, and agency booking dashboard.