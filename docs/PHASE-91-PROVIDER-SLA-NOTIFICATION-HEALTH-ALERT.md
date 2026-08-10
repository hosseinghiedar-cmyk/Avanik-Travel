# Phase 91 — Provider SLA Notification Delivery Health Alert

Phase 91 adds threshold-based operational alerting on top of the Phase 90 delivery health monitor.

## Behavior
- Three consecutive `failed`/`error` deliveries activate an alert.
- Repeated failures do not create duplicate alert events while the alert is active.
- A successful delivery resets the failure streak and emits a recovery hook when an alert was active.
- State is administrator-visible through a read-only Settings page.
- Alert state contains only operational metadata; no payloads or credentials are stored.

## Hooks
- `avanik_provider_sla_notification_health_alert`
- `avanik_provider_sla_notification_health_recovered`

Phase 91 prepares the system for a later notification/escalation adapter without coupling the health monitor directly to a provider.
