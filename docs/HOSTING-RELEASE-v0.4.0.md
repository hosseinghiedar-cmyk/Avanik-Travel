# Avanik Travel v0.4.0 — Hosting Release

## Package status
Hosting package boundary prepared and repository wiring completed.

## Deployment target
`wordpress/avanik` → `wp-content/themes/avanik`

## Important implementation change
`inc/PhaseLoader.php` now loads and registers Phases 203–227 from the theme runtime. This prevents the phase classes from existing only as repository files without being loaded by WordPress.

## Version
Theme metadata is `0.4.0`.

## Verification
`.github/workflows/hosting-release-verify.yml` performs PHP syntax linting and checks required Phase files and theme metadata.

## Production safety
The package does not enable production supplier/payment/ticket integrations automatically. Production authorization remains an evidence-based operator decision.

## Hosting installation
Upload the `avanik` theme directory to `wp-content/themes/` or package that directory as a WordPress theme ZIP. Do not upload the repository root as a WordPress theme.
