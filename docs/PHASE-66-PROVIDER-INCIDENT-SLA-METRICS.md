# Phase 66 — Provider Incident SLA Metrics

Phase 66 adds operational metrics over the provider health incident lifecycle.

## Metrics
- Total incidents
- Open incidents
- Resolved incidents
- Total downtime
- Average downtime for resolved incidents
- Average acknowledgement time for acknowledged incidents
- Average resolution time for resolved incidents
- Incident count by provider

Metrics are calculated from the existing incident lifecycle records and do not store credentials or provider request/response payloads.

## Admin dashboard
The Provider Health page now shows summary metric cards above the provider and incident tables.

Durations are formatted as hours/minutes/seconds. Open incidents contribute downtime from their opening time through the current time; resolved incidents contribute from opened to resolved.
