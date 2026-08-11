<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignmentReviewEscalationAcknowledgementEscalationPolicyAuditVerificationEvidenceRetentionExpiryCheckPostExpiryActionReviewDecisionExecutionGuardAuthorizationAuditChangeAlertAcknowledgementAcknowledgeActionVerificationAuditReviewDecisionVerificationClosureReadinessClosureAuthorizationClosureExecutionGuardManualApprovalVerification {
    private const OPTION='avanik_sla_drift_closure_manual_approval_verification';
    private const CAPABILITY='manage_options';

    public static function register(): void { add_options_page('SLA Drift Closure Manual Approval Verification','SLA Drift Closure Manual Approval Verification',self::CAPABILITY,'avanik-sla-drift-closure-manual-approval-verification',[self::class,'render']); }

    public static function evaluate(): array {
        $approval=get_option('avanik_sla_drift_closure_manual_approval',[]);
        $approval=is_array($approval)?$approval:[];
        $valid=($approval['approval_status']??'')==='approved' && !empty($approval['approved_by']) && !empty($approval['approved_at']) && ($approval['execution_allowed']??true)===false;
        $result=[
            'verification_status'=>$valid?'verified':'failed',
            'approval_status'=>(string)($approval['approval_status']??'unknown'),
            'approved_by'=>(int)($approval['approved_by']??0),
            'approved_at'=>(int)($approval['approved_at']??0),
            'execution_allowed'=>false,
            'reason'=>$valid?'manual_approval_is_complete_and_execution_remains_guarded':'manual_approval_state_is_incomplete_or_unsafe',
            'verified_at'=>time(),
        ];
        update_option(self::OPTION,$result,false); return $result;
    }

    public static function render(): void { if(!current_user_can(self::CAPABILITY)) return; $s=self::evaluate(); echo '<div class="wrap"><h1>SLA Drift Closure Manual Approval Verification</h1><p>Phase 161 verifies the Phase 160 administrator approval record without opening the closure execution guard.</p><table class="widefat striped"><tbody>'; foreach(['Verification status'=>strtoupper($s['verification_status']),'Approval status'=>strtoupper($s['approval_status']),'Approved by'=>$s['approved_by']?:'—','Approved at'=>$s['approved_at']?wp_date('Y-m-d H:i:s',$s['approved_at']):'—','Execution allowed'=>$s['execution_allowed']?'YES':'NO','Reason'=>str_replace('_',' ',$s['reason']),'Verified at'=>wp_date('Y-m-d H:i:s',$s['verified_at'])] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>'; echo '</tbody></table></div>'; }
}
