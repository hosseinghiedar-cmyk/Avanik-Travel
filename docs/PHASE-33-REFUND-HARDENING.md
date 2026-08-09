# Phase 33 — Refund Hardening & Financial Controls

Implemented:
- Idempotency guard for refund state transitions.
- Durable settlement reference and customer ownership fields on refund records.
- Audit logging and status-change notification hooks from every successful transition.
- Agency commission reversal hook when a refund reaches `completed`.
- Customer-facing refund status helper with ownership checks.
- Refund infrastructure registration updated.

Refund lifecycle:
`requested → approved → processing → completed`

Rejected paths remain controlled and repeated transition attempts are blocked by idempotency keys.

Important production note:
- The agency reversal is currently a domain hook, not a blind accounting write; the existing commission ledger implementation must be connected to the hook with the project's final commission semantics before production settlement.
- Customer status exposes only non-sensitive refund fields.
- Actual money movement remains behind the configured refund gateway; no undocumented ZarinPal API is called.
