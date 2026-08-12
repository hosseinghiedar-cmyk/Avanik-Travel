<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignmentReviewEscalationAcknowledgementEscalationPolicyAuditVerificationEvidenceRetentionExpiryCheckPostExpiryActionReviewDecisionExecutionGuardAuthorizationAuditChangeAlertAcknowledgementAcknowledgeActionVerificationAuditReviewDecisionVerificationClosureReadinessClosureAuthorizationClosureExecutionGuardManualApprovalVerificationExecutionUnlockGuardReleaseGuardReleaseVerificationGuardReleaseApprovalVerificationGuardReleaseAudit {
    private const OPTION='avanik_sla_drift_guard_release_audit';
    private const CAPABILITY='manage_options';
    public static function register(): void { add_options_page('SLA Drift Guard Release Audit','SLA Drift Guard Release Audit',self::CAPABILITY,'avanik-sla-drift-guard-release-audit',[self::class,'render']); }
    public static function evaluate(): array {
        $v=get_option('avanik_sla_drift_guard_release_approval_verification',[]); $v=is_array($v)?$v:[];
        $valid=($v['verification_status']??'')==='verified' && ($v['approval_status']??'')==='approved';
        $result=['audit_status'=>$valid?'verified':'blocked','source_verification'=>(string)($v['verification_status']??'failed'),'approval_status'=>(string)($v['approval_status']??'unknown'),'approved_by'=>(string)($v['approved_by']??''),'guard_release'=>false,'execution_allowed'=>false,'event'=>'guard_release_approval_verified','reason'=>$valid?'approval_verification_is_auditable_and_guard_remains_locked':'approval_verification_not_valid_for_audit','audited_at'=>time()];
        update_option(self::OPTION,$result,false); return $result;
    }
    public static function render(): void { if(!current_user_can(self::CAPABILITY)) return; $s=self::evaluate(); echo '<div class="wrap"><h1>SLA Drift Guard Release Audit</h1><p>Phase 167 records an auditable snapshot of the verified guard-release approval while keeping execution locked.</p><table class="widefat striped"><tbody>'; foreach(['Audit status'=>strtoupper($s['audit_status']),'Source verification'=>strtoupper($s['source_verification']),'Approval status'=>strtoupper($s['approval_status']),'Approved by'=>$s['approved_by']?:'—','Guard release'=>$s['guard_release']?'YES':'NO','Execution allowed'=>$s['execution_allowed']?'YES':'NO','Event'=>str_replace('_',' ',$s['event']),'Reason'=>str_replace('_',' ',$s['reason']),'Audited at'=>wp_date('Y-m-d H:i:s',$s['audited_at'])] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>'; echo '</tbody></table></div>'; }
}
