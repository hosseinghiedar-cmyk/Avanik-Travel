# Phase 44 — Notification Dashboard & Metrics

Implemented the operational dashboard for notification delivery.

## Admin dashboard

WordPress admin menu:
- Settings → Notification Dashboard
- Capability: `manage_options`

## Metrics

The dashboard reports, for a selectable 1/7/14/30/90 day period:
- queued/retry jobs
- failed jobs
- dead-letter jobs
- unread internal notifications
- queue status breakdown
- channel breakdown
- role breakdown

## Architecture

Metrics are read from the existing notification queue and internal notification tables. No provider-specific assumptions are introduced. Future SMS/WhatsApp providers can publish provider metrics through delivery hooks without changing the dashboard's core data model.

## Security

Only WordPress administrators with `manage_options` can access the dashboard. Date range is bounded to 1–90 days.

## Repository note

`NotificationDashboard.php` was already present in the repository from a later notification-operations implementation. Phase 44 completes the intended phase by registering that component in `functions.php` and documenting its operational role.