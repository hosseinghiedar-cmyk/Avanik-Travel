# Phase 31 — Cancellation & Refund Engine

Implemented:
- `RefundRepository` with a durable refund ledger.
- Refund breakdown fields: gross amount, provider fee, Avanik fee, agency adjustment and customer refund.
- `RefundService` calculation/request layer.
- `BookingRefundBridge` listens for successful ticket cancellation and creates a refund request from the related payment.
- Refund policy is filter-driven via `avanik_refund_policy`, so provider/agency rules can be changed without rewriting the core service.

Formula:
customer_refund = max(0, gross_amount - provider_fee - avanik_fee - agency_adjustment)

Important:
Phase 31 records and calculates the refund; it does NOT automatically transfer money to the customer. Actual payout must be connected to the selected payment method (card-to-card/manual bank transfer or a future ZarinPal/refund API) and approved through a controlled settlement workflow.

Next hardening:
- Add refund admin approval and audit trail.
- Add idempotent refund execution.
- Add payment-method-specific refund adapters.
- Add agency commission reversal/settlement.
- Add customer refund status in the booking dashboard.