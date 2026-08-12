<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignmentReviewEscalationAcknowledgementEscalationPolicyAuditVerificationEvidenceRetentionExpiryCheckPostExpiryActionReviewDecisionExecutionGuardAuthorizationAuditChangeAlertAcknowledgementAcknowledgeActionVerificationAuditReviewDecisionVerificationClosureReadiness {
    private const OPTION = 'avanik_sla_drift_review_closure_readiness';
    private const CAPABILITY = 'manage_options';

    public static function register(): void {
        add_options_page('SLA Drift Review Closure Readiness', 'SLA Drift Review Closure Readiness', self::CAPABILITY, 'avanik-sla-drift-review-closure-readiness', [self::class, 'render']);
    }

    public static function evaluate(): array {
        $closure = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignmentReviewEscalationAcknowledgementEscalationPolicyAuditVerificationEvidenceRetentionExpiryCheckPostExpiryActionReviewDecisionExecutionGuardAuthorizationAuditChangeAlertAcknowledgementAcknowledgeActionVerificationAuditReviewDecisionVerificationClosure::evaluate();
        $ready = ($closure['closure_status'] ?? '') === 'ready' && !empty($closure['decision']) && !empty($closure['fingerprint']);
        $result = [
            'readiness'=>$ready ? 'ready' : 'blocked',
            'closure_status'=>(string)($closure['closure_status'] ?? 'blocked'),
            'decision'=>(string)($closure['decision'] ?? ''),
            'verification_status'=>(string)($closure['verification_status'] ?? 'failed'),
            'fingerprint'=>(string)($closure['fingerprint'] ?? ''),
            'execution_allowed'=>false,
            'reason'=>$ready ? 'all_closure_prerequisites_present' : 'closure_prerequisites_missing',
            'evaluated_at'=>time(),
        ];
        update_option(self::OPTION, $result, false);
        return $result;
    }

    public static function render(): void {
        if (!current_user_can(self::CAPABILITY)) return;
        $s=self::evaluate();
        echo '<div class="wrap"><h1>SLA Drift Review Closure Readiness</h1><p>Phase 157 validates that every prerequisite required by the Phase 156 closure gate is present before any future closure action.</p><table class="widefat striped"><tbody>';
        foreach (['Readiness'=>strtoupper($s['readiness']),'Closure status'=>strtoupper($s['closure_status']),'Decision'=>strtoupper($s['decision'] ?: '—'),'Verification status'=>strtoupper($s['verification_status']),'Fingerprint'=>$s['fingerprint'] ?: '—','Execution allowed'=>$s['execution_allowed']?'YES':'NO','Reason'=>str_replace('_',' ',$s['reason']),'Evaluated at'=>wp_date('Y-m-d H:i:s',$s['evaluated_at'])] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
