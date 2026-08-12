<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignmentReviewEscalationAcknowledgementEscalationPolicyAuditVerificationEvidenceRetentionExpiryCheckPostExpiryActionReviewDecisionExecutionGuardAuthorizationAuditChangeAlertAcknowledgementAcknowledgeActionVerificationAuditReviewDecisionVerificationClosureReadinessClosureAuthorizationClosureExecutionGuardManualApprovalVerificationExecutionUnlock {
    private const OPTION='avanik_sla_drift_manual_approval_execution_unlock';
    private const CAPABILITY='manage_options';
    public static function register(): void { add_options_page('SLA Drift Manual Approval Execution Unlock','SLA Drift Manual Approval Execution Unlock',self::CAPABILITY,'avanik-sla-drift-execution-unlock',[self::class,'render']); }
    public static function evaluate(): array {
        $v=get_option('avanik_sla_drift_closure_manual_approval_verification',[]); $v=is_array($v)?$v:[];
        $approved=($v['verification_status']??'')==='verified' && ($v['approval_status']??'')==='approved';
        $result=['unlock_status'=>$approved?'approved_pending_guard_release':'blocked','approval_status'=>(string)($v['approval_status']??'unknown'),'verification_status'=>(string)($v['verification_status']??'failed'),'execution_allowed'=>false,'guard_release_required'=>true,'reason'=>$approved?'approval_is_verified_but_guard_release_requires_separate_step':'approval_verification_is_not_valid','evaluated_at'=>time()];
        update_option(self::OPTION,$result,false); return $result;
    }
    public static function render(): void { if(!current_user_can(self::CAPABILITY)) return; $s=self::evaluate(); echo '<div class="wrap"><h1>SLA Drift Manual Approval Execution Unlock</h1><p>Phase 162 records verified approval eligibility while keeping guard release as a separate controlled step.</p><table class="widefat striped"><tbody>'; foreach(['Unlock status'=>strtoupper(str_replace('_',' ',$s['unlock_status'])),'Approval status'=>strtoupper($s['approval_status']),'Verification status'=>strtoupper($s['verification_status']),'Execution allowed'=>$s['execution_allowed']?'YES':'NO','Guard release required'=>$s['guard_release_required']?'YES':'NO','Reason'=>str_replace('_',' ',$s['reason']),'Evaluated at'=>wp_date('Y-m-d H:i:s',$s['evaluated_at'])] as $k=>$val) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$val).'</td></tr>'; echo '</tbody></table></div>'; }
}
