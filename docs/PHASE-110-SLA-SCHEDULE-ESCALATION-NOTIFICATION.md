# Phase 110 — SLA Schedule Escalation Notification

Phase 110 adds an administrator notification layer on top of the Phase 109 scheduler escalation state.

## Behavior
- Reads the existing escalation state.
- Sends an email to the WordPress `admin_email` only when the escalation level changes.
- Does not send repeated emails while the level remains unchanged.
- Uses WordPress `wp_mail()` and does not persist message payloads, credentials, or tokens.
- Adds an administrator-only status page showing current level and last notification time.

## Levels
`NONE`, `WARNING`, `HIGH`, `CRITICAL`.
