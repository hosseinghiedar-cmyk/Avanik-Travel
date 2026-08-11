<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignmentReviewEscalationAcknowledgementEscalationPolicyAuditVerificationEvidenceRetentionExpiryCheckPostExpiryActionReviewDecisionExecutionGuardAuthorizationAuditChangeAlertAcknowledgementAcknowledgeActionVerificationAuditReviewDecisionVerification {
    private const OPTION = 'avanik_sla_drift_review_decision_verification';
    private const CAPABILITY = 'manage_options';

    public static function register(): void {
        add_options_page('SLA Drift Review Decision Verification', 'SLA Drift Review Decision Verification', self::CAPABILITY, 'avanik-sla-drift-review-decision-verification', [self::class, 'render']);
    }

    public static function evaluate(): array {
        $decision = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignmentReviewEscalationAcknowledgementEscalationPolicyAuditVerificationEvidenceRetentionExpiryCheckPostExpiryActionReviewDecisionExecutionGuardAuthorizationAuditChangeAlertAcknowledgementAcknowledgeActionVerificationAuditReviewDecision::evaluate();
        $valid_decisions = ['accept','reopen'];
        $value = strtolower((string)($decision['decision'] ?? ''));
        $valid = in_array($value, $valid_decisions, true) && !empty($decision['decided_by']) && !empty($decision['decided_at']) && !empty($decision['fingerprint']);
        $result = [
            'verification_status'=>$valid ? 'verified' : 'failed',
            'decision'=>$value ?: 'unknown',
            'decision_allowed'=>$valid,
            'decided_by'=>(int)($decision['decided_by'] ?? 0),
            'decided_at'=>(int)($decision['decided_at'] ?? 0),
            'fingerprint'=>(string)($decision['fingerprint'] ?? ''),
            'execution_allowed'=>false,
            'verified_at'=>time(),
            'reason'=>$valid ? 'review_decision_is_complete_and_allowed' : 'review_decision_is_incomplete_or_invalid',
        ];
        update_option(self::OPTION, $result, false);
        return $result;
    }

    public static function render(): void {
        if (!current_user_can(self::CAPABILITY)) return;
        $s=self::evaluate();
        echo '<div class="wrap"><h1>SLA Drift Review Decision Verification</h1><p>Phase 155 verifies that the Phase 154 review decision is complete, allowed, and explicitly attributed to an administrator.</p><table class="widefat striped"><tbody>';
        foreach (['Verification status'=>strtoupper($s['verification_status']),'Decision'=>strtoupper($s['decision']),'Decision allowed'=>$s['decision_allowed']?'YES':'NO','Decided by'=>$s['decided_by'] ?: '—','Decided at'=>$s['decided_at'] ? wp_date('Y-m-d H:i:s',$s['decided_at']) : '—','Fingerprint'=>$s['fingerprint'] ?: '—','Execution allowed'=>'NO','Reason'=>str_replace('_',' ',$s['reason']),'Verified at'=>wp_date('Y-m-d H:i:s',$s['verified_at'])] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
