# Phase 62 — Provider Health Alert Escalation

Phase 62 adds an escalation policy on top of the Phase 61 acknowledgement workflow.

## Policy
- Critical alerts are immediately eligible for escalation.
- Warning alerts become escalation-eligible after the configured consecutive occurrence threshold (default: 2).
- Info alerts are not escalated by default.
- A stable escalation key is generated per provider and alert code.
- A cooldown protects downstream notification channels from repeated escalation delivery.

## Integration
The evaluator is exposed through `avanik_notification_provider_health_alert_escalate`.

The phase intentionally separates deciding *whether* an alert should escalate from actually delivering an email, SMS, or inbox notification. That delivery decision belongs to the next notification-routing phase.

No credentials or provider request/response bodies are included in escalation payloads.
