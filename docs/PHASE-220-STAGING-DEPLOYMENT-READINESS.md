# Phase 220 — Staging Deployment Readiness

Phase 220 establishes a controlled staging deployment boundary.

## Checks
- WordPress environment API.
- Maintenance mode capability.
- Cron capability.
- REST API capability.
- Security hardening baseline.
- Backup/restore baseline.
- Rollback/recovery baseline.

## Safety
This phase does not deploy automatically. Production deployment remains disabled. External supplier calls, external payment calls, and ticket issuance remain disabled until an explicitly configured staging environment is deployed and validated.

## Required before deployment
- Select/verify a dedicated staging environment.
- Deploy the exact release candidate artifact.
- Apply staging-only configuration and credentials.
- Confirm database backup and rollback point.
- Run smoke and E2E tests.
- Validate monitoring and alert delivery.
- Record deployment evidence before proceeding to release-candidate approval.
