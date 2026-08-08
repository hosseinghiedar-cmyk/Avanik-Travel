# Phase 5 — Product Detail & Booking

Implemented:
- Published product detail shortcode `[avanik_product_detail]`
- Product detail reads only published products
- Customer booking CTA
- Initial booking creation linked to product ID
- Quantity/capacity guard
- Guest and logged-in customer support foundation
- Existing BookingRepository is reused

Flow:
Catalog → Product Detail → Start Booking → Booking record → existing booking/payment lifecycle.

Next: customer details form, availability locking, booking validation, payment selection (card-to-card / Zarinpal), payment confirmation and agency/admin booking views.