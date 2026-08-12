# Phase 218 — Backup & Restore Readiness

Phase 218 establishes the backup and restore baseline for Avanik.

## Baseline checks
- WordPress filesystem API availability.
- WordPress database schema/export API availability.
- Scheduled-job API availability.
- WordPress content/uploads path availability.

## Current safety state
- No production backup is executed by this phase.
- No restore is executed by this phase.
- Backup destination is not configured here.
- Offsite backup verification is pending.
- Restore test verification is pending.
- Production release remains blocked.

## Required before production
- Define backup scope: database, uploads, plugin configuration and deployment artifacts.
- Configure encrypted primary and offsite destinations.
- Define retention and rotation.
- Verify backup integrity.
- Perform a restore test in an isolated environment and record RTO/RPO.
- Keep credentials outside source control.
