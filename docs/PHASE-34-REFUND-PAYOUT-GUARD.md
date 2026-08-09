# Phase 34 — Refund Payout Guard

Implemented:
- Strict gateway execution boundary: only `approved` refunds may invoke a refund gateway.
- Settlement idempotency claim before gateway execution.
- Gateway result validation before state transition.
- Atomic status transition using the previous status in the database update condition.
- Completion requires a non-empty settlement/provider reference.
- Audit, notification and agency commission reversal hooks remain attached to successful completion.
- Existing manual card-to-card and future ZarinPal adapters remain compatible with the gateway boundary.

The system does not invent or call an undocumented ZarinPal refund API. Real payment movement still requires a configured production gateway adapter.

Recommended next phase:
- customer/agency refund dashboard and notifications;
- settlement reconciliation/reporting;
- explicit agency commission reversal ledger entries;
- automated tests for duplicate gateway execution and concurrent state transitions.