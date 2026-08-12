<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignmentReviewEscalationAcknowledgementEscalationPolicyAuditVerificationEvidenceRetentionExpiryCheckPostExpiryActionReviewDecisionExecutionGuardAuthorizationAuditChangeAlertAcknowledgementAcknowledgeActionVerificationAuditReviewDecisionVerificationClosureReadinessClosureAuthorization {
    private const OPTION = 'avanik_sla_drift_closure_authorization';
    private const CAPABILITY = 'manage_options';

    public static function register(): void {
        add_options_page('SLA Drift Review Closure Authorization', 'SLA Drift Review Closure Authorization', self::CAPABILITY, 'avanik-sla-drift-review-closure-authorization', [self::class, 'render']);
    }

    public static function evaluate(): array {
        $readiness = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignmentReviewEscalationAcknowledgementEscalationPolicyAuditVerificationEvidenceRetentionExpiryCheckPostExpiryActionReviewDecisionExecutionGuardAuthorizationAuditChangeAlertAcknowledgementAcknowledgeActionVerificationAuditReviewDecisionVerificationClosureReadiness::evaluate();
        $authorized = ($readiness['readiness'] ?? '') === 'ready' && ($readiness['execution_allowed'] ?? true) === false;
        $result = [
            'authorization_status'=>$authorized ? 'eligible' : 'blocked',
            'readiness'=>(string)($readiness['readiness'] ?? 'blocked'),
            'decision'=>(string)($readiness['decision'] ?? ''),
            'fingerprint'=>(string)($readiness['fingerprint'] ?? ''),
            'closure_execution_authorized'=>false,
            'manual_approval_required'=>true,
            'reason'=>$authorized ? 'closure_is_eligible_but_execution_remains_disabled' : 'closure_readiness_is_not_satisfied',
            'evaluated_at'=>time(),
        ];
        update_option(self::OPTION, $result, false);
        return $result;
    }

    public static function render(): void {
        if (!current_user_can(self::CAPABILITY)) return;
        $s=self::evaluate();
        echo '<div class="wrap"><h1>SLA Drift Review Closure Authorization</h1><p>Phase 158 separates closure eligibility from actual closure execution. Eligibility never grants automatic execution.</p><table class="widefat striped"><tbody>';
        foreach (['Authorization status'=>strtoupper($s['authorization_status']),'Readiness'=>strtoupper($s['readiness']),'Decision'=>strtoupper($s['decision'] ?: '—'),'Fingerprint'=>$s['fingerprint'] ?: '—','Closure execution authorized'=>$s['closure_execution_authorized']?'YES':'NO','Manual approval required'=>$s['manual_approval_required']?'YES':'NO','Reason'=>str_replace('_',' ',$s['reason']),'Evaluated at'=>wp_date('Y-m-d H:i:s',$s['evaluated_at'])] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
