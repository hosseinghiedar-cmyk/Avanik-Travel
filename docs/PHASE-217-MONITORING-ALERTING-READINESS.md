# Phase 217 — Monitoring & Alerting Readiness

Phase 217 establishes the monitoring and alerting baseline for Avanik.

## Baseline checks
- WordPress debug/environment API availability.
- PHP error logging availability.
- WordPress scheduled-event API availability.
- REST API availability.
- WordPress environment/health API availability.

## Current safety state
- Alert channels are not configured by this phase.
- Metrics collection is not enabled by this phase.
- External monitoring is not enabled by this phase.
- No monitoring test is executed automatically.
- Production release remains blocked.

## Required before production
- Define application metrics: request count, latency, error rate, booking failures, payment failures, supplier failures, ticket failures, queue/cron failures.
- Define alert thresholds and escalation channels.
- Configure external monitoring in staging first.
- Verify alert delivery and recovery notifications.
- Keep secrets outside source control.
