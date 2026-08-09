# Phase 12 — Multi-Passenger Checkout Foundation

Implemented:
- Passenger requirement rules remain product-type driven.
- Domestic flights request national ID and do not automatically request passport fields.
- International flights request nationality, date of birth, passport number and passport expiry.
- Passenger storage now supports national ID, nationality, date of birth, passport expiry and gender.
- Passenger date fields are validated as ISO `Y-m-d` before persistence.
- Existing booking passenger records remain backward-compatible.
- PassengerRequirements is registered in the WordPress bootstrap.

Next:
- Render the dynamic passenger form during checkout for quantity N.
- Validate every passenger against the product requirements.
- Persist N passenger records atomically with the booking.
- Add sensitive-data access controls, masking and encryption before production use.
- Add agency custom passenger fields and connect them to the editor.