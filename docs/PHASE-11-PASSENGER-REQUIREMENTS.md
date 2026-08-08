# Phase 11 — Configurable Passenger Requirements

Passenger data collection is product-type driven and configurable by the selling agency.

## Domestic flight
Basic passenger information plus national ID can be requested. Passport fields are not requested by default.

## International flight
Basic passenger information plus nationality, date of birth, passport number and passport expiry are requested by default.

## Agency product configuration
The agency product editor exposes passenger field checkboxes. Selected fields are persisted in product metadata. Basic identity/contact fields remain mandatory in the configuration layer.

## Extensible products
Tour, hotel and package products do not automatically inherit passport requirements. They can define their own selected passenger fields.

## Privacy
Passport and identity information is sensitive. Production hardening must include encryption at rest, role-based access, audit logging, masking in list views and controlled exports.

## Next
Render the persisted requirements dynamically during checkout, persist all selected values per passenger, add agency-defined custom fields, and implement privacy/security controls.