<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignmentReviewEscalationAcknowledgementEscalationPolicyAuditVerificationEvidenceRetentionExpiryCheckPostExpiryActionReviewDecisionExecutionGuardAuthorizationAuditChangeAlert {
    private const OPTION = 'avanik_sla_drift_execution_authorization_audit_change_alert';
    private const CAPABILITY = 'manage_options';

    public static function register(): void {
        add_options_page('SLA Drift Authorization Audit Change Alert', 'SLA Drift Authorization Audit Change Alert', self::CAPABILITY, 'avanik-sla-drift-authorization-audit-alert', [self::class, 'render']);
    }

    public static function evaluate(): array {
        $audit = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignmentReviewEscalationAcknowledgementEscalationPolicyAuditVerificationEvidenceRetentionExpiryCheckPostExpiryActionReviewDecisionExecutionGuardAuthorizationAudit::evaluate();
        $previous = get_option(self::OPTION, []);
        $previous = is_array($previous) ? $previous : [];
        $changed = !empty($audit['audit_changed']);
        $state = [
            'fingerprint'=>(string)$audit['fingerprint'],
            'change_detected'=>$changed,
            'alert_status'=>$changed ? 'attention_required' : 'clear',
            'transition'=>$changed && empty($previous['change_detected']) ? 'opened' : ($changed ? 'steady' : (!empty($previous['change_detected']) ? 'resolved' : 'none')),
            'automatic_notification'=>false,
            'evaluated_at'=>time(),
        ];
        update_option(self::OPTION, $state, false);
        return $state;
    }

    public static function render(): void {
        if (!current_user_can(self::CAPABILITY)) return;
        $s=self::evaluate();
        echo '<div class="wrap"><h1>SLA Drift Authorization Audit Change Alert</h1><p>Phase 148 surfaces authorization-audit changes for administrator attention. No notification is sent automatically.</p><table class="widefat striped"><tbody>';
        foreach (['Fingerprint'=>$s['fingerprint'],'Change detected'=>$s['change_detected']?'YES':'NO','Alert status'=>strtoupper(str_replace('_',' ',$s['alert_status'])),'Transition'=>strtoupper($s['transition']),'Automatic notification'=>$s['automatic_notification']?'ENABLED':'DISABLED','Evaluated at'=>wp_date('Y-m-d H:i:s',$s['evaluated_at'])] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
