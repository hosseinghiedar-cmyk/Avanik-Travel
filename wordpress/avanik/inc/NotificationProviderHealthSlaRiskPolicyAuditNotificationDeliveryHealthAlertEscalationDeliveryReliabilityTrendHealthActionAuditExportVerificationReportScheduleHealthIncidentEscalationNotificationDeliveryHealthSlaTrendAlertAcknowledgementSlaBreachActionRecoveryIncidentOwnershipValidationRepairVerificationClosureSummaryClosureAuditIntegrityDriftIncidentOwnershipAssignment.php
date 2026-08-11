<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignment {
    private const OPTION = 'avanik_sla_drift_incident_ownership_assignment';
    private const CAPABILITY = 'manage_options';

    public static function register(): void {
        add_options_page('SLA Drift Incident Ownership Assignment', 'SLA Drift Incident Ownership Assignment', self::CAPABILITY, 'avanik-sla-drift-incident-ownership-assignment', [self::class, 'render']);
    }

    public static function evaluate(): array {
        $ownership = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnership::evaluate();
        $owner_id = (int)$ownership['owner_id'];
        $assignable = $owner_id > 0 && get_user_by('id', $owner_id) !== false;
        $previous = get_option(self::OPTION, []);
        $previous = is_array($previous) ? $previous : [];
        $state = [
            'incident_active'=>(bool)$ownership['incident_active'],
            'owner_id'=>$owner_id,
            'assignable'=>$assignable,
            'assignment_required'=>(bool)$ownership['ownership_required'],
            'status'=>$ownership['ownership_required'] ? 'assignment_required' : ($assignable ? 'assigned' : ($ownership['incident_active'] ? 'invalid_assignment' : 'closed')),
            'transition'=>$ownership['ownership_required'] && empty($previous['assignment_required']) ? 'opened' : ($ownership['ownership_required'] ? 'steady' : (!empty($previous['assignment_required']) ? 'resolved' : 'none')),
            'evaluated_at'=>time(),
        ];
        update_option(self::OPTION, $state, false);
        return $state;
    }

    public static function render(): void {
        if (!current_user_can(self::CAPABILITY)) return;
        $s=self::evaluate();
        echo '<div class="wrap"><h1>SLA Drift Incident Ownership Assignment</h1><p>Phase 132 validates whether the current drift-incident owner can actually be resolved as a WordPress user.</p><table class="widefat striped"><tbody>';
        foreach (['Incident active'=>$s['incident_active']?'YES':'NO','Owner user ID'=>$s['owner_id'],'Assignable'=>$s['assignable']?'YES':'NO','Assignment required'=>$s['assignment_required']?'YES':'NO','Status'=>strtoupper(str_replace('_',' ',$s['status'])),'Transition'=>strtoupper($s['transition']),'Evaluated at'=>wp_date('Y-m-d H:i:s',$s['evaluated_at'])] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
