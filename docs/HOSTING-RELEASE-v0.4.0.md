# Avanik Travel v0.4.0 — cPanel Hosting Release

## Package status
A cPanel-oriented hosting release branch is prepared from `main`.

## Deployment target
`wordpress/avanik` → `wp-content/themes/avanik`

## cPanel / Windows path compatibility
The deepest Windows-hostile PHP filename in the notification SLA audit chain was replaced with the short path:

`inc/sla_escalation_notification_delivery_health.php`

The PHP class name is intentionally unchanged, so runtime references remain compatible. The parent notification class now explicitly loads the shortened file path.

## Version
Theme metadata is `0.4.0`.

## Phase runtime
`inc/PhaseLoader.php` loads and registers Phases 203–227 from the theme runtime.

## Verification boundary
This release is structurally prepared for cPanel installation. A real cPanel deployment, PHP runtime test, database migration, booking test, payment test, supplier test, and production monitoring still require the actual hosting environment.

## Production safety
The package does not enable production supplier/payment/ticket integrations automatically. Production authorization remains an evidence-based operator decision.

## Hosting installation
1. Install WordPress on cPanel.
2. Create the production/staging database and database user.
3. Upload the Avanik theme directory to `wp-content/themes/`.
4. Activate Avanik from WordPress.
5. Verify PHP version/extensions and WordPress REST/Cron.
6. Run the staging smoke test before production use.

Do not upload the repository root as a WordPress theme.
