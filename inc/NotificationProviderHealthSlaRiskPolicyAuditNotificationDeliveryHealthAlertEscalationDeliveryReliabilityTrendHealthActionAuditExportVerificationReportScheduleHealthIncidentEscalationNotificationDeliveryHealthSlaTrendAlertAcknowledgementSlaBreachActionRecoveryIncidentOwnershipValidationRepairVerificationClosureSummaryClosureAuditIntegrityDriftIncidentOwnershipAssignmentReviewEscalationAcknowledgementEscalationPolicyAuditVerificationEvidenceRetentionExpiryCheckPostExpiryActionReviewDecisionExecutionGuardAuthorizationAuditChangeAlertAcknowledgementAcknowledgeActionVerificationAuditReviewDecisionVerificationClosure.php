<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignmentReviewEscalationAcknowledgementEscalationPolicyAuditVerificationEvidenceRetentionExpiryCheckPostExpiryActionReviewDecisionExecutionGuardAuthorizationAuditChangeAlertAcknowledgementAcknowledgeActionVerificationAuditReviewDecisionVerificationClosure {
    private const OPTION = 'avanik_sla_drift_review_decision_verification_closure';
    private const CAPABILITY = 'manage_options';

    public static function register(): void {
        add_options_page('SLA Drift Review Decision Verification Closure', 'SLA Drift Review Decision Verification Closure', self::CAPABILITY, 'avanik-sla-drift-review-decision-closure', [self::class, 'render']);
    }

    public static function evaluate(): array {
        $verification = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignmentReviewEscalationAcknowledgementEscalationPolicyAuditVerificationEvidenceRetentionExpiryCheckPostExpiryActionReviewDecisionExecutionGuardAuthorizationAuditChangeAlertAcknowledgementAcknowledgeActionVerificationAuditReviewDecisionVerification::evaluate();
        $status = (string)($verification['verification_status'] ?? 'failed');
        $decision = (string)($verification['decision'] ?? '');
        $closable = $status === 'verified' && in_array($decision, ['accept','reopen'], true);
        $previous = get_option(self::OPTION, []);
        $previous = is_array($previous) ? $previous : [];
        $state = [
            'closure_status'=>$closable ? 'ready' : 'blocked',
            'closed'=>$closable && !empty($previous['closure_status']) && $previous['closure_status'] === 'ready' ? true : false,
            'decision'=>$decision,
            'verification_status'=>$status,
            'fingerprint'=>(string)($verification['fingerprint'] ?? ''),
            'reason'=>$closable ? 'verification_is_complete_and_decision_is_valid' : 'verification_or_decision_is_not_closable',
            'evaluated_at'=>time(),
        ];
        update_option(self::OPTION, $state, false);
        return $state;
    }

    public static function render(): void {
        if (!current_user_can(self::CAPABILITY)) return;
        $s=self::evaluate();
        echo '<div class="wrap"><h1>SLA Drift Review Decision Verification Closure</h1><p>Phase 156 establishes a closure readiness gate after the Phase 155 decision verification.</p><table class="widefat striped"><tbody>';
        foreach (['Closure status'=>strtoupper($s['closure_status']),'Closed'=>$s['closed']?'YES':'NO','Decision'=>strtoupper($s['decision'] ?: '—'),'Verification status'=>strtoupper($s['verification_status']),'Fingerprint'=>$s['fingerprint'] ?: '—','Reason'=>str_replace('_',' ',$s['reason']),'Evaluated at'=>wp_date('Y-m-d H:i:s',$s['evaluated_at'])] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
