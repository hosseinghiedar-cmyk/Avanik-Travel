# Phase 14 — Passenger Data Security & Agency Custom Fields

Implemented:
- Passenger data access guard for admin/customer contexts.
- Masking helpers for passport and national ID values in list/detail presentation.
- Optional AES-256-CBC encryption helpers controlled by `AVANIK_DATA_KEY` (minimum 32 characters).
- Agency-defined passenger custom fields per product.
- Supported custom field types: text, date, select.
- Required custom field validation.

Security note: encryption is opt-in until the production key-management policy is finalized. The key must never be stored in the repository or product metadata. Before production, encrypt sensitive columns at persistence time and add an audit log for privileged access.

Next: wire custom fields into Agency Product Editor and checkout persistence, add encrypted persistence migration, audit logging, and agency passenger-detail permissions.