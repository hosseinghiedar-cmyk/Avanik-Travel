# Phase 30 — Ticket PDF & Private Storage

Implemented:
- `TicketPdfRenderer` abstraction with a safe HTML document fallback and a filter hook for a real PDF library.
- `TicketPrivateStorage` under a non-public uploads subdirectory.
- `.htaccess` deny rule and index guard created for the private ticket directory.
- Secure download endpoint now validates the one-time user-bound token, verifies booking access, loads or renders the document, stores it privately, and serves it through the authenticated endpoint.
- Bootstrap registration updated.

Important implementation note:
The current renderer returns an HTML document by default rather than pretending it is a real PDF. A production PDF engine (for example, a Composer-managed PDF library) should be connected through `avanik_render_ticket_pdf`. The secure storage/access architecture is already in place.

Production hardening:
- On Nginx/private object storage, enforce server-side deny rules because `.htaccess` is Apache-only.
- Add a real PDF renderer and QR code after the production document library is selected.
- Store document MIME type and checksum in the ticket table.
- Consider encrypted object storage for documents containing sensitive passenger information.