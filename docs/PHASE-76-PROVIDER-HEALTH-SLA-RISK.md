# Phase 76 — Provider Health SLA Risk

## Purpose

Add a read-only operational risk view on top of the existing Provider Health SLA metrics, compliance, trend and per-provider policies.

## Risk inputs

For the selected period (7, 30, 90 or 365 days), each provider is evaluated using:

- SLA compliance percentage
- Open incidents
- SLA breach count
- Total downtime

## Risk score

The score starts at the provider SLA compliance value. Penalties are applied as follows:

- 10 points per open incident
- 5 points per SLA breach
- Maximum combined penalty: 40 points

The score is clamped to zero.

## Classification

- Low: score >= 98
- Medium: score >= 95 and < 98
- High: score >= 90 and < 95
- Critical: score < 90
- Unknown: no applicable SLA checks

## Security

The page requires `manage_options` and is read-only. No credentials, tokens, request bodies or provider response bodies are exposed or stored.

## Integration

The risk engine uses the existing `NotificationProviderHealthSla::policy()` result, so Phase 71 per-provider overrides remain the single source of effective SLA policy.
