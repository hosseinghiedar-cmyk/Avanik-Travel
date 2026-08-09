# Phase 35 — Refund Reconciliation & Settlement Reporting

Implemented:
- Settlement amount field on refund records.
- Reconciliation service comparing expected customer refund against settled amount.
- `matched` / `discrepancy` classification.
- Admin reconciliation action with nonce and capability checks.
- Reconciliation result written to the refund audit log.
- Refund status summary reporting helper.

Important:
The reconciliation layer does not invent a bank or ZarinPal settlement response. `settled_amount` must be populated from a verified settlement source or manual administrator entry.

Next phase:
- full customer and agency refund dashboard;
- notification delivery;
- commission ledger reversal entries;
- settlement CSV/export and accounting reports;
- automated tests and concurrency coverage.