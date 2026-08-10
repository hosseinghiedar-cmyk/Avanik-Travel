# Phase 51 — Notification Provider Configuration

Adds a WordPress admin configuration layer for notification providers.

## Admin
Settings → Notification Providers

Each provider can define:
- stable provider ID
- display name
- adapter name
- enabled/disabled state
- supported channels: email, SMS, WhatsApp, internal
- priority
- administrator notes

## Architecture
The configuration is intentionally separate from provider implementation. API credentials/secrets are not stored by this phase. Real integrations are expected to be supplied by adapter implementations, while the admin configuration determines which provider/channel combinations are enabled.

This keeps Avanik independent from any specific SMS, WhatsApp, email, or other external provider and prepares the next phase for dynamic provider resolution and safe credential handling.
