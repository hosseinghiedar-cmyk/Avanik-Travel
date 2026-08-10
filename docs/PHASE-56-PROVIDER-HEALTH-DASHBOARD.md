# Phase 56 — Provider Health Dashboard

Adds an administrator dashboard at **Settings → Provider Health**.

The dashboard summarizes each configured notification provider:
- enabled/disabled state
- configured channels
- whether encrypted credentials exist
- latest connection-test result
- latest recorded response duration

The dashboard is read-only and does not expose credentials or provider response bodies.

The implementation intentionally tolerates the absence of test-log data so the dashboard remains usable during upgrades and before the first connection test.
