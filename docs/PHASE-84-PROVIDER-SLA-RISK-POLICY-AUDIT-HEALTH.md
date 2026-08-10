# Phase 84 — Provider SLA Risk Policy Audit Health

Phase 84 adds a read-only health dashboard for the SLA Risk Policy audit integrity monitor.

## Scope
- Shows the latest integrity state, last check, last failure, and incident count.
- Allows an administrator to trigger an on-demand integrity check.
- Uses the Phase 83 monitor as the single source of truth.
- Does not modify audit records or repair the hash chain automatically.

## Access
WordPress administrators with `manage_options` can access:
Settings → Provider SLA Risk Audit Health.
