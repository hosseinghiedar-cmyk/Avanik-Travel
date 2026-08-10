# Phase 43 — Role Notification Inboxes

Implemented:
- Role-aware internal notification records with `customer`, `agency`, and `admin` scopes.
- Customer inbox remains available through `[avanik_notifications]`.
- Agency inbox shortcode: `[avanik_agency_notifications]`.
- Admin inbox shortcode: `[avanik_admin_notifications]` and requires `manage_options`.
- Role-specific mark-as-read actions with ownership checks and nonces.
- Notification storage now includes `notification_role` and indexed role/user lookup.
- Internal delivery capture accepts the resolved recipient user ID and role.

Architecture:
- Agency and admin inboxes do not expose arbitrary users' messages.
- Admin access is capability-gated.
- Existing notification preferences and recipient resolver remain the source of channel/recipient decisions.

Next phase:
- role inbox dashboard counters (unread, queued, failed, dead);
- event-level notification preferences;
- agency notification filtering by agency ownership once the final agency relationship lookup is wired into Booking/Marketplace.