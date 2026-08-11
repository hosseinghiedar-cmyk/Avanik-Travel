<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignmentReviewEscalationAcknowledgementEscalationPolicyAuditVerificationEvidenceRetentionExpiryCheckPostExpiryActionReviewDecisionExecutionGuardAuthorizationAuditChangeAlertAcknowledgementAcknowledgeActionVerificationAuditReviewDecisionVerificationClosureReadinessClosureAuthorizationClosureExecutionGuardManualApprovalVerificationExecutionUnlockGuardReleaseGuardReleaseVerificationGuardReleaseApprovalVerificationGuardReleaseAuditGuardReleaseAuditVerification {
    private const OPTION='avanik_sla_drift_guard_release_audit_verification';
    private const CAPABILITY='manage_options';
    public static function register(): void { add_options_page('SLA Drift Guard Release Audit Verification','SLA Drift Guard Release Audit Verification',self::CAPABILITY,'avanik-sla-drift-guard-release-audit-verification',[self::class,'render']); }
    public static function evaluate(): array {
        $a=get_option('avanik_sla_drift_guard_release_audit',[]); $a=is_array($a)?$a:[];
        $valid=($a['audit_status']??'')==='verified' && ($a['source_verification']??'')==='verified' && ($a['approval_status']??'')==='approved' && !empty($a['approved_by']) && ($a['guard_release']??true)===false && ($a['execution_allowed']??true)===false;
        $result=['verification_status'=>$valid?'verified':'failed','audit_status'=>(string)($a['audit_status']??'unknown'),'source_verification'=>(string)($a['source_verification']??'unknown'),'approval_status'=>(string)($a['approval_status']??'unknown'),'approved_by'=>(string)($a['approved_by']??''),'guard_release'=>false,'execution_allowed'=>false,'reason'=>$valid?'guard_release_audit_snapshot_is_consistent':'guard_release_audit_snapshot_is_invalid','verified_at'=>time()];
        update_option(self::OPTION,$result,false); return $result;
    }
    public static function render(): void { if(!current_user_can(self::CAPABILITY)) return; $s=self::evaluate(); echo '<div class="wrap"><h1>SLA Drift Guard Release Audit Verification</h1><p>Phase 168 verifies the Phase 167 audit snapshot before any future guard-release action.</p><table class="widefat striped"><tbody>'; foreach(['Verification status'=>strtoupper($s['verification_status']),'Audit status'=>strtoupper($s['audit_status']),'Source verification'=>strtoupper($s['source_verification']),'Approval status'=>strtoupper($s['approval_status']),'Approved by'=>$s['approved_by']?:'—','Guard release'=>$s['guard_release']?'YES':'NO','Execution allowed'=>$s['execution_allowed']?'YES':'NO','Reason'=>str_replace('_',' ',$s['reason']),'Verified at'=>wp_date('Y-m-d H:i:s',$s['verified_at'])] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>'; echo '</tbody></table></div>'; }
}
