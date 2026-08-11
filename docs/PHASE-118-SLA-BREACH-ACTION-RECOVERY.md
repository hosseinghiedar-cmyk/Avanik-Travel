# Phase 118 — SLA Breach Action Recovery

Phase 118 adds explicit recovery-state tracking for the Phase 117 acknowledgement SLA breach action.

## Behavior
- Reuses the Phase 116 SLA evaluator.
- Detects `opened`, `steady`, and `resolved` transitions.
- Uses `administrator_attention` only while the SLA remains breached.
- Clears the action to `none` after recovery.
- Stores only state-transition metadata; no duplicate audit/event stream is created.
- Administrator-only management page.
