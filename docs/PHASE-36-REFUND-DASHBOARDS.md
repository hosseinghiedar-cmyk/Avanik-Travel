# Phase 36 — Customer & Agency Refund Dashboards

Implemented:
- Customer refund dashboard shortcode: `[avanik_refunds]`.
- Agency refund dashboard shortcode: `[avanik_agency_refunds]`.
- Scoped repository queries so customers only see refunds assigned to their user account and agencies see refunds belonging to bookings assigned to their agency account.
- Dashboard output exposes only refund ID, booking ID, refund amount, currency, status and updated time.
- Refund dashboard registration added to the Avanik theme/plugin bootstrap.

Security boundary:
- Unauthenticated visitors receive a login prompt.
- Customer queries are filtered by `customer_user_id`.
- Agency queries are joined through the booking's `agency_user_id`.
- Sensitive payment/provider data is intentionally excluded from these customer-facing views.

Next phase:
- add customer/agency notification delivery;
- add detailed refund timeline/audit view with role-aware redaction;
- add admin reconciliation export and finance reports;
- test the dashboard against the actual BookingRepository schema before production deployment.