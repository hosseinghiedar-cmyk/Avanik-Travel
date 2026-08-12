<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipEscalation {
    private const OPTION = 'avanik_sla_recovery_incident_ownership_escalation';
    private const CAPABILITY = 'manage_options';

    public static function register(): void {
        add_options_page('SLA Incident Ownership Escalation', 'SLA Incident Ownership Escalation', self::CAPABILITY, 'avanik-sla-incident-ownership-escalation', [self::class, 'render']);
    }

    public static function evaluate(): array {
        $incident = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncident::evaluate();
        $owner = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnership::state();
        $previous = get_option(self::OPTION, []);
        $previous = is_array($previous) ? $previous : [];
        $owner_id = (int)($owner['owner_id'] ?? 0);
        $escalated = $incident['incident'] === 'open' && $owner_id <= 0;
        $state = ['incident'=>$incident['incident'],'owner_id'=>$owner_id,'escalated'=>$escalated,'action'=>$escalated ? 'ownership_required' : 'none','evaluated_at'=>time(),'previous_escalated'=>!empty($previous['escalated'])];
        update_option(self::OPTION, $state, false);
        return $state;
    }

    public static function render(): void {
        if (!current_user_can(self::CAPABILITY)) return;
        $s = self::evaluate();
        echo '<div class="wrap"><h1>SLA Incident Ownership Escalation</h1><p>Phase 121 identifies open incidents that have no assigned owner.</p><table class="widefat striped"><tbody>';
        foreach (['Incident'=>strtoupper($s['incident']),'Owner user ID'=>$s['owner_id'],'Escalated'=>$s['escalated'] ? 'YES' : 'NO','Action'=>strtoupper(str_replace('_',' ',$s['action'])),'Previous escalated'=>$s['previous_escalated'] ? 'YES' : 'NO','Evaluated at'=>wp_date('Y-m-d H:i:s',$s['evaluated_at'])] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
