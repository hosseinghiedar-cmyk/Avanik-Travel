<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignmentReviewEscalationAcknowledgementEscalationPolicyAuditVerificationEvidenceRetentionExpiryCheckPostExpiryActionReviewDecisionExecutionGuardAuthorizationAuditChangeAlertAcknowledgementAcknowledgeActionVerification {
    private const OPTION = 'avanik_sla_drift_ack_action_verification';
    private const CAPABILITY = 'manage_options';

    public static function register(): void {
        add_options_page('SLA Drift Acknowledgement Verification', 'SLA Drift Acknowledgement Verification', self::CAPABILITY, 'avanik-sla-drift-ack-verification', [self::class, 'render']);
    }

    public static function evaluate(): array {
        $ack = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignmentReviewEscalationAcknowledgementEscalationPolicyAuditVerificationEvidenceRetentionExpiryCheckPostExpiryActionReviewDecisionExecutionGuardAuthorizationAuditChangeAlertAcknowledgementAcknowledgeAction::acknowledge();
        $state = is_array($ack['state'] ?? null) ? $ack['state'] : [];
        $valid = !empty($ack['success']) && !empty($state['acknowledged']) && ($state['acknowledgement_status'] ?? '') === 'acknowledged' && !empty($state['acknowledged_by']) && !empty($state['acknowledged_at']);
        $result = [
            'verification_status'=>$valid ? 'verified' : 'failed',
            'acknowledged'=>$valid,
            'acknowledged_by'=>(int)($state['acknowledged_by'] ?? 0),
            'acknowledged_at'=>(int)($state['acknowledged_at'] ?? 0),
            'reason'=>$valid ? 'acknowledgement_state_is_complete' : 'acknowledgement_state_incomplete',
            'verified_at'=>time(),
        ];
        update_option(self::OPTION, $result, false);
        return $result;
    }

    public static function render(): void {
        if (!current_user_can(self::CAPABILITY)) return;
        $s=self::evaluate();
        echo '<div class="wrap"><h1>SLA Drift Acknowledgement Verification</h1><p>Phase 151 verifies that the Phase 150 acknowledgement action produced a complete acknowledgement state.</p><table class="widefat striped"><tbody>';
        foreach (['Verification status'=>strtoupper($s['verification_status']),'Acknowledged'=>$s['acknowledged']?'YES':'NO','Acknowledged by'=>$s['acknowledged_by'] ?: '—','Acknowledged at'=>$s['acknowledged_at'] ? wp_date('Y-m-d H:i:s',$s['acknowledged_at']) : '—','Reason'=>str_replace('_',' ',$s['reason']),'Verified at'=>wp_date('Y-m-d H:i:s',$s['verified_at'])] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
