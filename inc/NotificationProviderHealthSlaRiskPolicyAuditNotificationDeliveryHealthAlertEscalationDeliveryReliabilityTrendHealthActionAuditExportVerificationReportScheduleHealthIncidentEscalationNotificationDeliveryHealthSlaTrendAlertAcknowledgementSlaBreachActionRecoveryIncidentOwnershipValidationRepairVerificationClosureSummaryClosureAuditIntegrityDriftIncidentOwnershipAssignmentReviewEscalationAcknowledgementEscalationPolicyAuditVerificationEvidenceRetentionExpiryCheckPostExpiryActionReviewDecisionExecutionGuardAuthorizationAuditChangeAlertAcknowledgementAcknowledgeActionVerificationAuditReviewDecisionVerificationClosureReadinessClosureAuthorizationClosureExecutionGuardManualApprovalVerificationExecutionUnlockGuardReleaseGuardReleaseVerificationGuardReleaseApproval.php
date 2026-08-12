<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignmentReviewEscalationAcknowledgementEscalationPolicyAuditVerificationEvidenceRetentionExpiryCheckPostExpiryActionReviewDecisionExecutionGuardAuthorizationAuditChangeAlertAcknowledgementAcknowledgeActionVerificationAuditReviewDecisionVerificationClosureReadinessClosureAuthorizationClosureExecutionGuardManualApprovalVerificationExecutionUnlockGuardReleaseGuardReleaseVerificationGuardReleaseApproval {
    private const OPTION='avanik_sla_drift_guard_release_approval';
    private const CAPABILITY='manage_options';
    public static function register(): void { add_options_page('SLA Drift Guard Release Approval','SLA Drift Guard Release Approval',self::CAPABILITY,'avanik-sla-drift-guard-release-approval',[self::class,'render']); }
    public static function approve(): array {
        if(!current_user_can(self::CAPABILITY)) return ['approval_status'=>'denied','reason'=>'capability_required'];
        $v=get_option('avanik_sla_drift_guard_release_verification',[]); $v=is_array($v)?$v:[];
        $valid=($v['verification_status']??'')==='verified' && ($v['guard_release']??true)===false && ($v['execution_allowed']??true)===false;
        $result=['approval_status'=>$valid?'approved':'blocked','approved_by'=>$valid?wp_get_current_user()->user_login:'','approved_at'=>$valid?time():0,'guard_release'=>false,'execution_allowed'=>false,'reason'=>$valid?'guard_release_approved_as_separate_controlled_step':'guard_release_verification_is_not_valid'];
        update_option(self::OPTION,$result,false); return $result;
    }
    public static function evaluate(): array { $s=get_option(self::OPTION,[]); return is_array($s)?$s:[]; }
    public static function render(): void { if(!current_user_can(self::CAPABILITY)) return; $s=self::approve(); echo '<div class="wrap"><h1>SLA Drift Guard Release Approval</h1><p>Phase 165 records a separate administrator approval for guard release. The guard remains unreleased and execution remains disabled.</p><table class="widefat striped"><tbody>'; foreach(['Approval status'=>strtoupper($s['approval_status']??'unknown'),'Approved by'=>$s['approved_by']??'—','Approved at'=>!empty($s['approved_at'])?wp_date('Y-m-d H:i:s',$s['approved_at']):'—','Guard release'=>!empty($s['guard_release'])?'YES':'NO','Execution allowed'=>!empty($s['execution_allowed'])?'YES':'NO','Reason'=>str_replace('_',' ',$s['reason']??'') ] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>'; echo '</tbody></table></div>'; }
}
