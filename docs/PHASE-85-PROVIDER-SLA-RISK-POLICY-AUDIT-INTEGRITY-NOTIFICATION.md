# Phase 85 — Provider SLA Risk Policy Audit Integrity Notification

Phase 85 connects the Phase 83 integrity failure event to the existing NotificationCenter.

## Scope
- Sends an internal and email notification to WordPress administrators when the audit integrity monitor detects a non-legacy failure.
- Uses the existing NotificationCenter queue, recipient resolution, channel policy, and delivery worker.
- Deduplicates notifications for the same failure timestamp.
- Does not send anything for legacy-only audit state.

## Event
`provider_sla_risk_policy_audit_integrity_failed`

## Safety
The notification contains only integrity metadata: check time, failure time, incident count, and failed status. No provider credentials, tokens, request/response bodies, or secrets are included.
