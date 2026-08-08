# Phase 11 — Passenger Requirements Editor

The passenger-information architecture is now product-driven.

## Rules
- Domestic flights require basic passenger information and national ID.
- International flights require basic information plus nationality, date of birth, passport number and passport expiry.
- Product-specific passenger fields are stored with the product metadata.
- Agencies can configure supported passenger fields for their own products.
- Required base fields cannot be removed.
- Saving requirements moves the product back to draft so moderation can review a material change.

## Security
Passport-related fields should be encrypted/masked in production and exposed only to authorized customer, agency and admin roles.

## Next
Connect the editor to the agency product edit screen, render the dynamic passenger form during checkout, validate fields server-side, and add encrypted storage/audit logging for sensitive passenger data.