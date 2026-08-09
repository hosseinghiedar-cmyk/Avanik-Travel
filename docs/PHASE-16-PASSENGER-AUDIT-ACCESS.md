# Phase 16 — Passenger Audit & Access Policy

Implemented:
- Dedicated passenger audit table foundation.
- Audit records capture user, booking, passenger, action, accessed fields, IP and timestamp.
- Passenger access policy distinguishes administrator, booking customer and supplier contexts.
- Non-privileged passenger views mask passport and national ID values.

Security note: this is the audit/access foundation. Before production, connect audit calls to every read/write of sensitive passenger data, add retention rules, and enforce encrypted persistence for passport data. Do not store encryption keys in the repository.

Next: connect the policy to agency/customer booking screens, add audit events on sensitive reads/updates, and complete encrypted persistence migration.