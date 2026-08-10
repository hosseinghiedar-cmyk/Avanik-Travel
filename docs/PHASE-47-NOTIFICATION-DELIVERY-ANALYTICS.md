# Phase 47 — Notification Delivery Analytics

Adds an admin-only analytics view on top of the append-oriented delivery history from Phase 46.

## Dashboard
WordPress: Settings → Delivery Analytics

Filters:
- date range (1–365 days)
- event
- channel
- role

Metrics:
- total delivery attempts
- sent attempts
- failed/dead attempts
- success rate

Breakdowns:
- channel performance
- event performance
- recent delivery history

The dashboard reads delivery history only; it does not alter queue state. Provider-specific fields remain generic so future email/SMS/WhatsApp providers can report message IDs and error metadata without changing the analytics layer.
