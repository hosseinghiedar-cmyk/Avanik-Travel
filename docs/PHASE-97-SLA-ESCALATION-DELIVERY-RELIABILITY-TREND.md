# Phase 97 — SLA Escalation Delivery Reliability Trend

Phase 97 adds a bounded trend snapshot over the Phase 96 reliability metrics.

## Scope
- Captures a snapshot whenever `avanik_provider_sla_notification_health_escalated` fires.
- Stores at most 30 snapshots.
- Records only operational counters and rates: attempts, sent, retry, dead, success rate, failure rate, retry rate, and failure count.
- Adds an administrator-only Settings page showing the newest snapshots first.
- Reuses Phase 96 metrics and does not create a delivery queue or persist notification payloads/credentials.
