<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignmentReviewEscalationAcknowledgementEscalationPolicyAuditVerificationEvidenceRetentionExpiryCheckPostExpiryActionReviewDecisionExecutionGuardAuthorizationAuditChangeAlertAcknowledgement {
    private const OPTION = 'avanik_sla_drift_authorization_audit_alert_acknowledgement';
    private const CAPABILITY = 'manage_options';

    public static function register(): void {
        add_options_page('SLA Drift Authorization Audit Alert Acknowledgement', 'SLA Drift Authorization Audit Alert Acknowledgement', self::CAPABILITY, 'avanik-sla-drift-authorization-audit-alert-ack', [self::class, 'render']);
    }

    public static function evaluate(): array {
        $alert = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignmentReviewEscalationAcknowledgementEscalationPolicyAuditVerificationEvidenceRetentionExpiryCheckPostExpiryActionReviewDecisionExecutionGuardAuthorizationAuditChangeAlert::evaluate();
        $previous = get_option(self::OPTION, []);
        $previous = is_array($previous) ? $previous : [];
        $attention = $alert['alert_status'] === 'attention_required';
        $state = [
            'fingerprint'=>(string)$alert['fingerprint'],
            'acknowledgement_required'=>$attention,
            'acknowledgement_status'=>$attention ? 'pending' : 'not_required',
            'acknowledged'=>false,
            'transition'=>$attention && empty($previous['acknowledgement_required']) ? 'opened' : ($attention ? 'steady' : (!empty($previous['acknowledgement_required']) ? 'resolved' : 'none')),
            'evaluated_at'=>time(),
        ];
        update_option(self::OPTION, $state, false);
        return $state;
    }

    public static function render(): void {
        if (!current_user_can(self::CAPABILITY)) return;
        $s=self::evaluate();
        echo '<div class="wrap"><h1>SLA Drift Authorization Audit Alert Acknowledgement</h1><p>Phase 149 creates an explicit acknowledgement state for Phase 148 administrator alerts. It does not send notifications or execute actions.</p><table class="widefat striped"><tbody>';
        foreach (['Fingerprint'=>$s['fingerprint'],'Acknowledgement required'=>$s['acknowledgement_required']?'YES':'NO','Acknowledgement status'=>strtoupper($s['acknowledgement_status']),'Acknowledged'=>$s['acknowledged']?'YES':'NO','Transition'=>strtoupper($s['transition']),'Evaluated at'=>wp_date('Y-m-d H:i:s',$s['evaluated_at'])] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
