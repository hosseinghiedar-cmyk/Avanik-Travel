# Phase 131 — SLA Drift Incident Ownership

Phase 131 assigns an ownership state to the Phase 130 integrity-drift incident.

## Behavior
- Reuses the existing Phase 130 incident lifecycle and Phase 120 ownership state.
- If the drift incident is active with no owner, reports `ownership_required`.
- If active with an owner, reports `owned`.
- If inactive, reports `closed`.
- Exposes opened, steady, and resolved ownership-required transitions.
- Does not automatically change ownership or create a duplicate notification stream.
