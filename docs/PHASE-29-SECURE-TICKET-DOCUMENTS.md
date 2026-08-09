# Phase 29 — Secure Ticket Documents

Implemented:
- Short-lived, user-bound download tokens using WordPress transients.
- One-time token consumption to reduce replay risk.
- Safe ticket PDF filename generation.
- Authenticated endpoint registration for ticket document downloads.
- Documentation of the separation between access control and the future PDF renderer/storage adapter.

Security model:
1. Logged-in user requests a ticket document.
2. Server creates a token bound to that WordPress user and ticket ID.
3. Token expires after 10 minutes.
4. Download endpoint validates login and consumes the token.
5. The actual PDF renderer/storage adapter will only be called after validation.

Important: Phase 29 deliberately does not ship a fake PDF or expose a direct filesystem URL. A real PDF renderer/storage implementation must be connected in the next phase after choosing the project's document library and private storage strategy.