<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignmentReviewEscalation {
    private const OPTION = 'avanik_sla_drift_incident_ownership_review_escalation';
    private const CAPABILITY = 'manage_options';

    public static function register(): void {
        add_options_page('SLA Drift Ownership Review Escalation', 'SLA Drift Ownership Review Escalation', self::CAPABILITY, 'avanik-sla-drift-ownership-review-escalation', [self::class, 'render']);
    }

    public static function evaluate(): array {
        $review = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignmentReview::evaluate();
        $previous = get_option(self::OPTION, []);
        $previous = is_array($previous) ? $previous : [];
        $required = !empty($review['review_required']);
        $transition = $required && empty($previous['escalation_required']) ? 'opened' : ($required ? 'steady' : (!empty($previous['escalation_required']) ? 'resolved' : 'none'));
        $state = [
            'incident_active'=>(bool)$review['incident_active'],
            'review_required'=>$required,
            'escalation_required'=>$required,
            'severity'=>$required ? 'warning' : 'none',
            'status'=>$required ? 'escalation_required' : ($review['incident_active'] ? 'normal' : 'closed'),
            'transition'=>$transition,
            'owner_id'=>(int)$review['owner_id'],
            'evaluated_at'=>time(),
        ];
        update_option(self::OPTION, $state, false);
        return $state;
    }

    public static function render(): void {
        if (!current_user_can(self::CAPABILITY)) return;
        $s=self::evaluate();
        echo '<div class="wrap"><h1>SLA Drift Ownership Review Escalation</h1><p>Phase 134 escalates an active drift incident when its ownership assignment still requires review.</p><table class="widefat striped"><tbody>';
        foreach (['Incident active'=>$s['incident_active']?'YES':'NO','Owner user ID'=>$s['owner_id'],'Review required'=>$s['review_required']?'YES':'NO','Escalation required'=>$s['escalation_required']?'YES':'NO','Severity'=>strtoupper($s['severity']),'Status'=>strtoupper(str_replace('_',' ',$s['status'])),'Transition'=>strtoupper($s['transition']),'Evaluated at'=>wp_date('Y-m-d H:i:s',$s['evaluated_at'])] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
