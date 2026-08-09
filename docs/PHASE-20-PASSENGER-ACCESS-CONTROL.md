# Phase 20 — Passenger Access Control

Implemented:
- Central PassengerSecurityPolicy for authorized passenger reads and updates.
- Unauthorized reads return no passenger data.
- Non-privileged reads use existing masking policy.
- Authorized reads emit a passenger view audit event.
- Authorized updates emit an update audit event with affected field names.
- Customer/agency/admin access remains delegated to PassengerAccessPolicy so booking ownership is the source of truth.

Production note: existing booking screens must call this policy instead of reading passenger rows directly. The helper intentionally does not itself mutate database rows; persistence must remain in BookingPassengers/services after authorization.

Next: wire every customer and agency passenger-detail endpoint to PassengerSecurityPolicy and add automated authorization tests for cross-agency/cross-customer access.