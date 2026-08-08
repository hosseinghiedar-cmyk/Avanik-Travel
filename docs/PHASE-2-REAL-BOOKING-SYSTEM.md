# Phase 2 — Real Booking System

## Goal
Move Avanik from foundation code toward a real Iran-first travel booking platform.

## Payment strategy
Phase 2 supports:
1. ZarinPal gateway
2. Manual card-to-card payment

International cards, cryptocurrency, and additional gateways are intentionally deferred to a later phase.

## Roadmap
- 021-025 Flight provider integration foundation
- 026-030 Search normalization and fare mapping
- 031-035 Booking / PNR lifecycle
- 036-040 Payment execution: ZarinPal + card-to-card
- 041-045 Ticket and voucher handling
- 046-050 Customer booking management

## Engineering rules
- Keep gateway implementations behind interfaces/services.
- Never store raw card information.
- Verify payment callbacks server-side.
- Keep provider credentials out of Git.
- Preserve Sprint 001-020 compatibility.
