<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnership {
    private const OPTION = 'avanik_sla_closure_integrity_drift_incident_ownership';
    private const CAPABILITY = 'manage_options';

    public static function register(): void {
        add_options_page('SLA Drift Incident Ownership', 'SLA Drift Incident Ownership', self::CAPABILITY, 'avanik-sla-drift-incident-ownership', [self::class, 'render']);
    }

    public static function evaluate(): array {
        $incident = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncident::evaluate();
        $ownership = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnership::state();
        $owner_id = (int)($ownership['owner_id'] ?? 0);
        $needs_owner = !empty($incident['active']) && $owner_id <= 0;
        $previous = get_option(self::OPTION, []);
        $previous = is_array($previous) ? $previous : [];
        $state = [
            'incident_active'=>!empty($incident['active']),
            'owner_id'=>$owner_id,
            'ownership_required'=>$needs_owner,
            'status'=>$needs_owner ? 'ownership_required' : (!empty($incident['active']) ? 'owned' : 'closed'),
            'transition'=>$needs_owner && empty($previous['ownership_required']) ? 'opened' : ($needs_owner ? 'steady' : (!empty($previous['ownership_required']) ? 'resolved' : 'none')),
            'evaluated_at'=>time(),
        ];
        update_option(self::OPTION, $state, false);
        return $state;
    }

    public static function render(): void {
        if (!current_user_can(self::CAPABILITY)) return;
        $s=self::evaluate();
        echo '<div class="wrap"><h1>SLA Drift Incident Ownership</h1><p>Phase 131 determines whether the integrity-drift incident has an assigned owner.</p><table class="widefat striped"><tbody>';
        foreach (['Incident active'=>$s['incident_active']?'YES':'NO','Owner user ID'=>$s['owner_id'],'Ownership required'=>$s['ownership_required']?'YES':'NO','Status'=>strtoupper(str_replace('_',' ',$s['status'])),'Transition'=>strtoupper($s['transition']),'Evaluated at'=>wp_date('Y-m-d H:i:s',$s['evaluated_at'])] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
