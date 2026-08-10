# Phase 83 — Provider SLA Risk Policy Audit Monitor

Phase 83 adds scheduled integrity monitoring for the tamper-evident Provider SLA Risk Policy audit chain.

## Scope
- Runs an hourly WordPress cron integrity check.
- Stores the latest check state and failure count.
- Emits `avanik_provider_health_sla_risk_notification_policy_audit_integrity_failed` on a confirmed chain failure.
- Shows an administrator warning when the latest check reports a non-legacy integrity failure.
- Does not alter or repair audit records automatically.

## Safety
The monitor stores only operational integrity metadata. It does not store provider credentials, tokens, request/response bodies, or notification payloads.
