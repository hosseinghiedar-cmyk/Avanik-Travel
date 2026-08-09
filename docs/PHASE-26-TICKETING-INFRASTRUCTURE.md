# Phase 26 — Ticketing Infrastructure

Implemented:
- `TicketingProviderInterface` contract for issue/get/cancel operations.
- Dedicated `avanik_tickets` persistence with support for multiple tickets per booking.
- Ticket ID, provider reference, PNR, e-ticket number, voucher reference, status and issuance timestamp.
- `TicketingIdempotency` table to prevent duplicate ticket requests.
- Bootstrap registration and installation of the new ticketing infrastructure.

Compatibility:
- Existing flight-provider contract is not changed, so current Sepehr360/Moghim24 adapters are not broken.
- Ticketing remains provider-capability based until each real provider exposes an issuance API.

Production hardening still required:
- Wire each provider adapter to `TicketingProviderInterface`.
- Store only non-sensitive document references in the ticket table; keep generated files in controlled storage.
- Add retry/timeout handling and an admin ticket operations screen.
- Add PDF ticket/voucher generation and customer/agency notification after issuance.