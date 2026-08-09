# Phase 25 — Ticket / Voucher Issuance

Implemented:
- `TicketingService` accepts only confirmed bookings.
- Provider ticket issuance is optional at adapter level; unsupported providers fail safely instead of causing a fatal method call.
- Successful issuance stores PNR, ticket number, voucher reference and issue time in the booking lifecycle metadata and transitions the booking to `ticketed`.
- `BookingTicketBridge` listens for `avanik_provider_confirmed` and attempts ticket issuance.
- Ticket failures emit `avanik_ticket_issue_failed` for retry and operations handling.
- Registered ticketing modules in the WordPress bootstrap.

The existing provider interface currently exposes `search()` and `book()`; ticket issuance is therefore capability-based until provider adapters are extended with a formal ticketing contract. This avoids breaking existing providers while preparing Sepehr360, Moghim24 and future Avanik-owned adapters.

Next: add a formal ticketing capability/interface, persistent ticket records, retry/idempotency for ticket requests, and customer/agency ticket & voucher download/notification flows.