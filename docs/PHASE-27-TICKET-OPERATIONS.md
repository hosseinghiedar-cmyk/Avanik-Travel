# Phase 27 — Ticket Operations

Implemented:
- `TicketOperations` service for authorized ticket listing and cancellation.
- Access checks for admin, booking customer and booking agency before exposing ticket data.
- Provider capability check before cancellation.
- `TicketAdmin` WordPress admin screen under Tools → Avanik Tickets.
- Ticket list shows Ticket ID, PNR, ticket number, voucher reference, status and issuance time.

Security:
- Ticket data is not exposed when the current user cannot access the booking.
- Cancellation is blocked when the provider does not support ticket cancellation.

Not yet implemented:
- PDF generation/storage/download.
- Customer and agency front-end ticket screens.
- Provider-specific cancellation adapters.
- Automated email/SMS/notification delivery.
- Production retry queue for ticket operations.