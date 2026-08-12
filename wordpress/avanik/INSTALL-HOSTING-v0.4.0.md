# Avanik Travel v0.4.0 — Hosting Installation

## Package target
This directory is the WordPress theme package. Upload the `avanik` theme directory under `wp-content/themes/` or use the WordPress theme ZIP installer.

## Requirements
- WordPress with standard theme support.
- PHP version compatible with the hosting WordPress installation.
- MySQL/MariaDB through WordPress.
- HTTPS recommended and required for real payment/provider integrations.
- WP-Cron or a real cron replacement for scheduled jobs.

## Installation
1. Take a full database and `wp-content` backup.
2. Upload the Avanik theme package to `wp-content/themes/`.
3. Activate **Avanik Travel** from Appearance → Themes.
4. Keep external supplier/payment/ticket credentials disabled until staging validation is complete.
5. Open WordPress admin and inspect the Avanik operational/readiness pages.
6. Configure staging-only provider credentials and run smoke/E2E validation.

## Important
Do not copy `wp-config.php` or any production secrets from another environment into the repository. WordPress deployment guidance also recommends keeping `wp-config.php` out of source control because it contains environment-specific sensitive settings. See WordPress/WooCommerce deployment guidance.

## Release state
Version: 0.4.0
Production authorization: not granted by this package.
Automatic production deployment: disabled.
