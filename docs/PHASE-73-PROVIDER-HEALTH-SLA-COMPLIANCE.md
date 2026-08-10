# Phase 73 — Provider Health SLA Compliance

## Goal
Add a historical SLA compliance report by provider without changing incident state or notification behavior.

## Scope
- Admin-only report under WordPress Settings.
- Period selector: 7, 30, 90, or 365 days.
- Provider-level incident count and SLA checks.
- Compliance percentage.
- Breach counts by acknowledgement, resolution, and downtime.
- Uses the effective per-provider SLA policy from Phase 71.

## Compliance
For enabled SLA checks:

`Compliance = (checks - breaches) / checks * 100`

A policy value of `0` disables that check. Incidents are limited to the selected opened-at period.

## Security
The report contains operational metadata only. It does not expose credentials, tokens, request bodies, or provider response bodies.

## Runtime
`NotificationProviderHealthSlaCompliance::register()` is loaded from `functions.php`.
