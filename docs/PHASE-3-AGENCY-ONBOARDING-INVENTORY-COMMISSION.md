# Phase 3 — Agency Onboarding, Inventory & Commission Foundation

Implemented:
- Agency supplier approval statuses: pending / approved / rejected
- `can_sell()` gate before supplier sales are enabled
- Inventory publication states: draft / pending_review / published / rejected
- Commission ledger database foundation
- Commission calculation with capped 0–100% rate

Business flow:

Agency registration → verification → approval → inventory creation → moderation → publication → customer booking → payment → commission ledger → settlement.

Rules:
- Unapproved agencies cannot sell.
- Supplier inventory is not automatically published.
- Commission is calculated from gross booking amount.
- Raw card details are never stored.
- Settlement remains a later controlled accounting workflow.
