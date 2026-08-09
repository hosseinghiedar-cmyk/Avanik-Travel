# Phase 17 — Encrypted Passenger Persistence

Implemented:
- Sensitive national ID and passport values are encrypted before database persistence when `AVANIK_DATA_KEY` is configured.
- Encrypted values are decrypted only when passenger records are loaded through `BookingPassengers`.
- Custom passenger values are encrypted individually before persistence.
- Database columns for sensitive values are widened to text for ciphertext.
- Standardized passenger audit event helpers were added for create, view, update and delete actions.

Migration note:
- Existing plaintext passport/national-ID records are not automatically converted by this change. A controlled migration is required so plaintext is encrypted once, verified, and then removed from plaintext storage.
- If `AVANIK_DATA_KEY` is missing, the current compatibility behavior leaves values unchanged; production deployment must require the key and fail closed for new sensitive writes.

Next: connect audit events to actual customer/agency/admin reads and writes, add the controlled legacy-data migration, and enforce the production key requirement.