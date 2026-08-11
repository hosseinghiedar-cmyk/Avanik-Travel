# Phase 130 — SLA Closure Integrity Drift Incident

Phase 130 promotes an active Phase 129 integrity drift into an explicit incident lifecycle.

## Behavior
- Reuses the Phase 129 drift detector.
- Creates an `opened` transition when drift first becomes active.
- Keeps the incident `steady` while drift persists.
- Marks it `resolved` when drift clears.
- Keeps severity at `warning` while active.
- Does not mutate ownership or the underlying closure state.
