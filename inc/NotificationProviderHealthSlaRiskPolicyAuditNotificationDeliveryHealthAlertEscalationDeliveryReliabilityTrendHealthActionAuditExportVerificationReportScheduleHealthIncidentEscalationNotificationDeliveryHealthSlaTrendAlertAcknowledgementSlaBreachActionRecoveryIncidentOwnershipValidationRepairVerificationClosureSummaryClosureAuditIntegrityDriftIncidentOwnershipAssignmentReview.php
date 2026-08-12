<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignmentReview {
    private const OPTION = 'avanik_sla_drift_incident_ownership_assignment_review';
    private const CAPABILITY = 'manage_options';

    public static function register(): void {
        add_options_page('SLA Drift Incident Ownership Assignment Review', 'SLA Drift Incident Ownership Assignment Review', self::CAPABILITY, 'avanik-sla-drift-incident-ownership-assignment-review', [self::class, 'render']);
    }

    public static function evaluate(): array {
        $assignment = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignment::evaluate();
        $owner_id = (int)$assignment['owner_id'];
        $user = $owner_id > 0 ? get_user_by('id', $owner_id) : false;
        $capability_ok = $user !== false && user_can($user, self::CAPABILITY);
        $previous = get_option(self::OPTION, []);
        $previous = is_array($previous) ? $previous : [];
        $review_required = (bool)$assignment['incident_active'] && !$capability_ok;
        $state = [
            'incident_active'=>(bool)$assignment['incident_active'],
            'owner_id'=>$owner_id,
            'user_exists'=>$user !== false,
            'capability_valid'=>$capability_ok,
            'review_required'=>$review_required,
            'status'=>$review_required ? 'review_required' : ($assignment['incident_active'] ? 'reviewed' : 'closed'),
            'transition'=>$review_required && empty($previous['review_required']) ? 'opened' : ($review_required ? 'steady' : (!empty($previous['review_required']) ? 'resolved' : 'none')),
            'evaluated_at'=>time(),
        ];
        update_option(self::OPTION, $state, false);
        return $state;
    }

    public static function render(): void {
        if (!current_user_can(self::CAPABILITY)) return;
        $s=self::evaluate();
        echo '<div class="wrap"><h1>SLA Drift Incident Ownership Assignment Review</h1><p>Phase 133 reviews whether the assigned owner exists and retains the required administrator capability.</p><table class="widefat striped"><tbody>';
        foreach (['Incident active'=>$s['incident_active']?'YES':'NO','Owner user ID'=>$s['owner_id'],'User exists'=>$s['user_exists']?'YES':'NO','Capability valid'=>$s['capability_valid']?'YES':'NO','Review required'=>$s['review_required']?'YES':'NO','Status'=>strtoupper(str_replace('_',' ',$s['status'])),'Transition'=>strtoupper($s['transition']),'Evaluated at'=>wp_date('Y-m-d H:i:s',$s['evaluated_at'])] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
