<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignmentReviewEscalationAcknowledgementEscalationPolicyAuditVerificationEvidenceRetentionExpiryCheckPostExpiryActionReviewDecisionExecutionGuardAuthorizationAuditChangeAlertAcknowledgementAcknowledgeActionVerificationAuditReviewDecision {
    private const OPTION = 'avanik_sla_drift_ack_verification_audit_review_decision';
    private const CAPABILITY = 'manage_options';
    private const ALLOWED = ['accept', 'reopen'];

    public static function register(): void {
        add_options_page('SLA Drift Verification Audit Review Decision', 'SLA Drift Verification Audit Review Decision', self::CAPABILITY, 'avanik-sla-drift-ack-audit-review-decision', [self::class, 'render']);
    }

    public static function decide(string $decision = ''): array {
        if (!current_user_can(self::CAPABILITY)) return ['success'=>false,'reason'=>'forbidden'];
        $review = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignmentReviewEscalationAcknowledgementEscalationPolicyAuditVerificationEvidenceRetentionExpiryCheckPostExpiryActionReviewDecisionExecutionGuardAuthorizationAuditChangeAlertAcknowledgementAcknowledgeActionVerificationAuditReview::evaluate();
        $decision = strtolower(trim($decision));
        if (!in_array($decision, self::ALLOWED, true)) return ['success'=>false,'reason'=>'invalid_review_decision','allowed'=>self::ALLOWED];
        if (empty($review['review_required']) && $decision === 'reopen') return ['success'=>false,'reason'=>'reopen_not_required'];
        $state = [
            'review_decision'=>$decision,
            'review_state'=>$decision === 'accept' ? 'accepted' : 'reopened',
            'review_required'=>(bool)$review['review_required'],
            'fingerprint'=>(string)$review['fingerprint'],
            'decided_by'=>get_current_user_id(),
            'decided_at'=>time(),
            'execution_allowed'=>false,
        ];
        update_option(self::OPTION, $state, false);
        return ['success'=>true,'state'=>$state];
    }

    public static function evaluate(): array {
        $stored = get_option(self::OPTION, []);
        return is_array($stored) ? $stored : [];
    }

    public static function render(): void {
        if (!current_user_can(self::CAPABILITY)) return;
        $s=self::evaluate();
        echo '<div class="wrap"><h1>SLA Drift Verification Audit Review Decision</h1><p>Phase 154 introduces an explicit administrator decision for the Phase 153 review. Use <code>accept</code> to close the review or <code>reopen</code> to return it to review attention. No operational execution is enabled.</p><table class="widefat striped"><tbody>';
        foreach (['Review decision'=>strtoupper((string)($s['review_decision'] ?? 'UNDECIDED')),'Review state'=>strtoupper((string)($s['review_state'] ?? 'UNDECIDED')),'Review required'=>!empty($s['review_required'])?'YES':'NO','Fingerprint'=>(string)($s['fingerprint'] ?? '—'),'Decided by'=>!empty($s['decided_by']) ? (int)$s['decided_by'] : '—','Decided at'=>!empty($s['decided_at']) ? wp_date('Y-m-d H:i:s',(int)$s['decided_at']) : '—','Execution allowed'=>!empty($s['execution_allowed'])?'YES':'NO'] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
