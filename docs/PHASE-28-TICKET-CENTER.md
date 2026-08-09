# Phase 28 — Customer & Agency Ticket Center

Implemented:
- Added `[avanik_ticket_center]` shortcode.
- Displays tickets associated with the current user's bookings.
- Shows PNR, ticket number, voucher reference, status and issued time.
- Separates access context for admin, agency and customer users.
- Uses the existing TicketRepository and booking ownership model rather than exposing tickets globally.

Usage:
1. Create a WordPress page for customer/agency ticket center.
2. Put `[avanik_ticket_center]` in the page content.
3. Restrict the page through the existing account/role access controls.

Next hardening:
- Replace role inference with the project's canonical agency capability when the agency role API is finalized.
- Add ticket PDF/voucher generation and secure download tokens.
- Add ticket detail view, cancellation action and notification history.