# Phase 32 — Refund Approval & Settlement

Implemented:
- Refund moderation screen under **Tools → Avanik Refunds**.
- Admin approve/reject workflow with WordPress nonce and capability checks.
- Refund state transitions: `requested → approved → processing → completed` (with rejection paths).
- Refund audit-log table and recording service.
- Payment-method refund adapter interface.
- Manual card-to-card adapter that explicitly requires an administrator to perform the bank transfer.
- ZarinPal adapter boundary that stays disabled until the production refund API contract is confirmed.

Safety:
- No automatic bank transfer is performed by the manual adapter.
- No unsupported ZarinPal API call is invented.
- Admin-only state changes.
- Invalid state transitions are rejected.

Remaining for the next phase:
- Persist audit records from every state transition.
- Store actual settlement reference and payout destination securely.
- Idempotency key for settlement execution.
- Reverse agency commission in the commission ledger.
- Customer-facing refund status and notification.
