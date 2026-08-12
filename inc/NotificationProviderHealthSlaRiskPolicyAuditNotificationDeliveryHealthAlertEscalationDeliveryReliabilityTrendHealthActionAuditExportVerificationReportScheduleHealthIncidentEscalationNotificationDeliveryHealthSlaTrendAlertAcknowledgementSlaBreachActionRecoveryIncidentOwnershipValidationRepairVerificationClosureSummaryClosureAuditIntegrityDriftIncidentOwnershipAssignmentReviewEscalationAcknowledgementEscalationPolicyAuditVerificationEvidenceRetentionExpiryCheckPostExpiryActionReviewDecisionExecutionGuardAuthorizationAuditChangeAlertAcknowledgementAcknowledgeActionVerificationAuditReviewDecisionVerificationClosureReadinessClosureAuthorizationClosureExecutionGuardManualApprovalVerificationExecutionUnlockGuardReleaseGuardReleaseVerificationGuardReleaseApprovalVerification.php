<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignmentReviewEscalationAcknowledgementEscalationPolicyAuditVerificationEvidenceRetentionExpiryCheckPostExpiryActionReviewDecisionExecutionGuardAuthorizationAuditChangeAlertAcknowledgementAcknowledgeActionVerificationAuditReviewDecisionVerificationClosureReadinessClosureAuthorizationClosureExecutionGuardManualApprovalVerificationExecutionUnlockGuardReleaseGuardReleaseVerificationGuardReleaseApprovalVerification {
    private const OPTION='avanik_sla_drift_guard_release_approval_verification';
    private const CAPABILITY='manage_options';
    public static function register(): void { add_options_page('SLA Drift Guard Release Approval Verification','SLA Drift Guard Release Approval Verification',self::CAPABILITY,'avanik-sla-drift-guard-release-approval-verification',[self::class,'render']); }
    public static function evaluate(): array {
        $a=get_option('avanik_sla_drift_guard_release_approval',[]); $a=is_array($a)?$a:[];
        $valid=($a['approval_status']??'')==='approved' && !empty($a['approved_by']) && !empty($a['approved_at']) && ($a['guard_release']??true)===false && ($a['execution_allowed']??true)===false;
        $result=['verification_status'=>$valid?'verified':'failed','approval_status'=>(string)($a['approval_status']??'unknown'),'approved_by'=>(string)($a['approved_by']??''),'approved_at'=>(int)($a['approved_at']??0),'guard_release'=>false,'execution_allowed'=>false,'reason'=>$valid?'guard_release_approval_is_complete_and_execution_remains_locked':'guard_release_approval_is_missing_or_invalid','verified_at'=>time()];
        update_option(self::OPTION,$result,false); return $result;
    }
    public static function render(): void { if(!current_user_can(self::CAPABILITY)) return; $s=self::evaluate(); echo '<div class="wrap"><h1>SLA Drift Guard Release Approval Verification</h1><p>Phase 166 verifies the Phase 165 administrator approval without releasing the guard or enabling execution.</p><table class="widefat striped"><tbody>'; foreach(['Verification status'=>strtoupper($s['verification_status']),'Approval status'=>strtoupper($s['approval_status']),'Approved by'=>$s['approved_by']?:'—','Approved at'=>$s['approved_at']?wp_date('Y-m-d H:i:s',$s['approved_at']):'—','Guard release'=>$s['guard_release']?'YES':'NO','Execution allowed'=>$s['execution_allowed']?'YES':'NO','Reason'=>str_replace('_',' ',$s['reason']),'Verified at'=>wp_date('Y-m-d H:i:s',$s['verified_at'])] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>'; echo '</tbody></table></div>'; }
}
