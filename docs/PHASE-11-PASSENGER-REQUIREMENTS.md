# Phase 11 — Dynamic Passenger Requirements

Passenger data collection is now product-type driven.

## Domestic flight
Basic passenger information plus national ID can be requested. Passport fields are not requested.

## International flight
Basic passenger information plus nationality, date of birth, passport number and passport expiry are requested.

## Extensible products
Other product types do not inherit passport fields automatically. Agencies can add product-specific requirements through the `avanik_product_passenger_requirements` filter in a future admin UI.

The form renderer and validation are separate from booking and payment. This allows the agency/product configuration to change without coupling passenger collection to a payment gateway.

Future work: persist per-product requirements in the database and expose them in the agency product editor, add secure encryption/access controls for sensitive passport data, and connect the dynamic form to multi-passenger booking creation.