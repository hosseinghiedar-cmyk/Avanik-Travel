<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignmentReviewEscalationAcknowledgementEscalationPolicyAuditVerificationEvidenceRetentionExpiryCheckPostExpiryActionReviewDecisionExecutionGuardAuthorizationAuditChangeAlertAcknowledgementAcknowledgeActionVerificationAuditReviewDecisionVerificationClosureReadinessClosureAuthorizationClosureExecutionGuardManualApprovalVerificationExecutionUnlockGuardReleaseGuardReleaseVerificationGuardReleaseApprovalVerificationGuardReleaseAuditGuardReleaseAuditVerificationGuardReleaseDecision {
    private const OPTION='avanik_sla_drift_guard_release_decision';
    private const CAPABILITY='manage_options';
    public static function register(): void { add_options_page('SLA Drift Guard Release Decision','SLA Drift Guard Release Decision',self::CAPABILITY,'avanik-sla-drift-guard-release-decision',[self::class,'render']); }
    public static function evaluate(): array {
        $v=get_option('avanik_sla_drift_guard_release_audit_verification',[]); $v=is_array($v)?$v:[];
        $valid=($v['verification_status']??'')==='verified' && ($v['audit_status']??'')==='verified';
        $result=['decision_status'=>$valid?'eligible_for_review_decision':'blocked','decision'=>'pending_review','verification_status'=>(string)($v['verification_status']??'failed'),'audit_status'=>(string)($v['audit_status']??'unknown'),'guard_release'=>false,'execution_allowed'=>false,'reason'=>$valid?'verified_audit_is_ready_for_explicit_review_decision':'audit_verification_is_not_valid_for_decision','evaluated_at'=>time()];
        update_option(self::OPTION,$result,false); return $result;
    }
    public static function render(): void { if(!current_user_can(self::CAPABILITY)) return; $s=self::evaluate(); echo '<div class="wrap"><h1>SLA Drift Guard Release Decision</h1><p>Phase 169 separates review decision from execution and keeps guard release disabled.</p><table class="widefat striped"><tbody>'; foreach(['Decision status'=>strtoupper(str_replace('_',' ',$s['decision_status'])),'Decision'=>strtoupper(str_replace('_',' ',$s['decision'])),'Verification status'=>strtoupper($s['verification_status']),'Audit status'=>strtoupper($s['audit_status']),'Guard release'=>$s['guard_release']?'YES':'NO','Execution allowed'=>$s['execution_allowed']?'YES':'NO','Reason'=>str_replace('_',' ',$s['reason']),'Evaluated at'=>wp_date('Y-m-d H:i:s',$s['evaluated_at'])] as $k=>$val) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$val).'</td></tr>'; echo '</tbody></table></div>'; }
}
