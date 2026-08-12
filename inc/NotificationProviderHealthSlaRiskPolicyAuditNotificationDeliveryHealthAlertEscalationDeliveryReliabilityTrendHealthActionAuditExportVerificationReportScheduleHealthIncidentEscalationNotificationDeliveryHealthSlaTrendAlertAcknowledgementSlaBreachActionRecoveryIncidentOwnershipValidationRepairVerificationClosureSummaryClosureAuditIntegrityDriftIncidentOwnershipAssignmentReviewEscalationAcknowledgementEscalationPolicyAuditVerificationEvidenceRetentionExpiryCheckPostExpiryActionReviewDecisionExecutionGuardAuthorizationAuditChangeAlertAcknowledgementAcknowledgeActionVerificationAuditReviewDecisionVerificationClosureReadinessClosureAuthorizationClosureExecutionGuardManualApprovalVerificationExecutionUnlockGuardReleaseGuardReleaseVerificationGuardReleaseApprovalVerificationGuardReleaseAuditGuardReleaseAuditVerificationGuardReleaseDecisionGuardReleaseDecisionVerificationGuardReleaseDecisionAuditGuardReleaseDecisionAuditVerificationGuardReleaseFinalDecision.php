<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignmentReviewEscalationAcknowledgementEscalationPolicyAuditVerificationEvidenceRetentionExpiryCheckPostExpiryActionReviewDecisionExecutionGuardAuthorizationAuditChangeAlertAcknowledgementAcknowledgeActionVerificationAuditReviewDecisionVerificationClosureReadinessClosureAuthorizationClosureExecutionGuardManualApprovalVerificationExecutionUnlockGuardReleaseGuardReleaseVerificationGuardReleaseApprovalVerificationGuardReleaseAuditGuardReleaseAuditVerificationGuardReleaseDecisionGuardReleaseDecisionVerificationGuardReleaseDecisionAuditGuardReleaseDecisionAuditVerificationGuardReleaseFinalDecision {
    private const OPTION='avanik_sla_drift_guard_release_final_decision';
    private const CAPABILITY='manage_options';
    public static function register(): void { add_options_page('SLA Drift Guard Release Final Decision','SLA Drift Guard Release Final Decision',self::CAPABILITY,'avanik-sla-drift-guard-release-final-decision',[self::class,'render']); }
    public static function evaluate(): array {
        $v=get_option('avanik_sla_drift_guard_release_decision_audit_verification',[]); $v=is_array($v)?$v:[];
        $eligible=($v['verification_status']??'')==='verified';
        $result=['decision_status'=>$eligible?'ready_for_final_decision':'blocked','decision'=>'pending_final_decision','source_verification'=>$eligible?'verified':'failed','guard_release'=>false,'execution_allowed'=>false,'reason'=>$eligible?'verified_decision_audit_is_ready_for_final_decision':'decision_audit_verification_is_not_valid','evaluated_at'=>time()];
        update_option(self::OPTION,$result,false); return $result;
    }
    public static function render(): void { if(!current_user_can(self::CAPABILITY)) return; $s=self::evaluate(); echo '<div class="wrap"><h1>SLA Drift Guard Release Final Decision</h1><p>Phase 173 creates the final-decision readiness gate. It does not release the guard or enable execution.</p><table class="widefat striped"><tbody>'; foreach(['Decision status'=>strtoupper(str_replace('_',' ',$s['decision_status'])),'Decision'=>strtoupper(str_replace('_',' ',$s['decision'])),'Source verification'=>strtoupper($s['source_verification']),'Guard release'=>$s['guard_release']?'YES':'NO','Execution allowed'=>$s['execution_allowed']?'YES':'NO','Reason'=>str_replace('_',' ',$s['reason']),'Evaluated at'=>wp_date('Y-m-d H:i:s',$s['evaluated_at'])] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>'; echo '</tbody></table></div>'; }
}
