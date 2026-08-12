<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignmentReviewEscalationAcknowledgementEscalationPolicyAuditVerificationEvidenceRetentionExpiryCheckPostExpiryActionReviewDecisionExecutionGuard {
    private const OPTION = 'avanik_sla_drift_review_decision_execution_guard';
    private const CAPABILITY = 'manage_options';
    private const ALLOWED = ['retain', 'archive', 'escalate'];

    public static function register(): void {
        add_options_page('SLA Drift Review Decision Execution Guard', 'SLA Drift Review Decision Execution Guard', self::CAPABILITY, 'avanik-sla-drift-review-decision-guard', [self::class, 'render']);
    }

    public static function evaluate(): array {
        $decision = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignmentReviewEscalationAcknowledgementEscalationPolicyAuditVerificationEvidenceRetentionExpiryCheckPostExpiryActionReviewDecision::evaluate();
        $raw = strtolower((string)$decision['review_decision']);
        $valid = in_array($raw, self::ALLOWED, true);
        $ready = $valid && (string)$decision['review_status'] === 'decided';
        $state = [
            'evidence_hash'=>(string)$decision['evidence_hash'],
            'decision'=>$raw ?: 'unreviewed',
            'decision_valid'=>$valid,
            'execution_ready'=>$ready,
            'execution_allowed'=>false,
            'guard_status'=>$ready ? 'ready_for_controlled_execution' : 'blocked',
            'reason'=>$ready ? 'valid_decision_requires_explicit_execution_step' : 'review_decision_not_ready',
            'evaluated_at'=>time(),
        ];
        update_option(self::OPTION, $state, false);
        return $state;
    }

    public static function render(): void {
        if (!current_user_can(self::CAPABILITY)) return;
        $s=self::evaluate();
        echo '<div class="wrap"><h1>SLA Drift Review Decision Execution Guard</h1><p>Phase 145 validates the Phase 144 decision before any future execution. Execution remains disabled by default.</p><table class="widefat striped"><tbody>';
        foreach (['Evidence hash'=>$s['evidence_hash'],'Decision'=>strtoupper($s['decision']),'Decision valid'=>$s['decision_valid']?'YES':'NO','Execution ready'=>$s['execution_ready']?'YES':'NO','Execution allowed'=>$s['execution_allowed']?'YES':'NO','Guard status'=>strtoupper(str_replace('_',' ',$s['guard_status'])),'Reason'=>str_replace('_',' ',$s['reason']),'Evaluated at'=>wp_date('Y-m-d H:i:s',$s['evaluated_at'])] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
