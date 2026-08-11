# Phase 142 — SLA Drift Policy Evidence Post-Expiry Action

Phase 142 defines the safe action after evidence retention expires.

## Behavior
- Reuses the Phase 141 expiry check.
- Marks expired evidence as `pending_review`.
- Requests manual review rather than automatic deletion.
- Explicitly keeps automatic deletion disabled.
- Exposes opened, steady, and resolved post-expiry review transitions.
- Administrator-only management page.
