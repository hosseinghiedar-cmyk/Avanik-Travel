<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignmentReviewEscalationAcknowledgementEscalationPolicyAuditVerificationEvidenceRetentionExpiryCheckPostExpiryActionReviewDecisionExecutionGuardAuthorization {
    private const OPTION = 'avanik_sla_drift_review_decision_execution_authorization';
    private const CAPABILITY = 'manage_options';
    private const ALLOWED = ['retain', 'archive', 'escalate'];

    public static function register(): void {
        add_options_page('SLA Drift Review Execution Authorization', 'SLA Drift Review Execution Authorization', self::CAPABILITY, 'avanik-sla-drift-review-execution-authorization', [self::class, 'render']);
    }

    public static function evaluate(): array {
        $guard = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignmentReviewEscalationAcknowledgementEscalationPolicyAuditVerificationEvidenceRetentionExpiryCheckPostExpiryActionReviewDecisionExecutionGuard::evaluate();
        $decision = strtolower((string)$guard['decision']);
        $authorized = current_user_can(self::CAPABILITY) && !empty($guard['execution_ready']) && in_array($decision, self::ALLOWED, true);
        $state = [
            'evidence_hash'=>(string)$guard['evidence_hash'],
            'decision'=>$decision ?: 'unreviewed',
            'authorized'=>$authorized,
            'authorization_status'=>$authorized ? 'authorized' : 'blocked',
            'execution_allowed'=>false,
            'authorization_reason'=>$authorized ? 'administrator_capability_and_valid_guard' : 'authorization_requirements_not_met',
            'evaluated_at'=>time(),
        ];
        update_option(self::OPTION, $state, false);
        return $state;
    }

    public static function render(): void {
        if (!current_user_can(self::CAPABILITY)) return;
        $s=self::evaluate();
        echo '<div class="wrap"><h1>SLA Drift Review Execution Authorization</h1><p>Phase 146 adds an authorization gate after the Phase 145 execution guard. Authorization never enables execution automatically.</p><table class="widefat striped"><tbody>';
        foreach (['Evidence hash'=>$s['evidence_hash'],'Decision'=>strtoupper($s['decision']),'Authorized'=>$s['authorized']?'YES':'NO','Authorization status'=>strtoupper($s['authorization_status']),'Execution allowed'=>$s['execution_allowed']?'YES':'NO','Reason'=>str_replace('_',' ',$s['authorization_reason']),'Evaluated at'=>wp_date('Y-m-d H:i:s',$s['evaluated_at'])] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
