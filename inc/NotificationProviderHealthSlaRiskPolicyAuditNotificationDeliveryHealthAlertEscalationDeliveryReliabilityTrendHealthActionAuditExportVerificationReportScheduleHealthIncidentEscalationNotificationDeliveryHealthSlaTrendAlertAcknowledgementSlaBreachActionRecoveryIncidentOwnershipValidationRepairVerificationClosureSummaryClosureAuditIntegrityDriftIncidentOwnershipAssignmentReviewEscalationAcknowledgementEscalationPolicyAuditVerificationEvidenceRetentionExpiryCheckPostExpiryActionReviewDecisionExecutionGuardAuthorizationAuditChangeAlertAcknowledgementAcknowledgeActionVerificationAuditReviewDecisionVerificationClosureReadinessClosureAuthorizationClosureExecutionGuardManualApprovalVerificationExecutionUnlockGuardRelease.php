<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignmentReviewEscalationAcknowledgementEscalationPolicyAuditVerificationEvidenceRetentionExpiryCheckPostExpiryActionReviewDecisionExecutionGuardAuthorizationAuditChangeAlertAcknowledgementAcknowledgeActionVerificationAuditReviewDecisionVerificationClosureReadinessClosureAuthorizationClosureExecutionGuardManualApprovalVerificationExecutionUnlockGuardRelease {
    private const OPTION='avanik_sla_drift_guard_release';
    private const CAPABILITY='manage_options';
    public static function register(): void { add_options_page('SLA Drift Guard Release','SLA Drift Guard Release',self::CAPABILITY,'avanik-sla-drift-guard-release',[self::class,'render']); }
    public static function evaluate(): array {
        $u=get_option('avanik_sla_drift_manual_approval_execution_unlock',[]); $u=is_array($u)?$u:[];
        $eligible=($u['unlock_status']??'')==='approved_pending_guard_release' && ($u['execution_allowed']??true)===false;
        $result=['release_status'=>$eligible?'ready_for_guard_release':'blocked','unlock_status'=>(string)($u['unlock_status']??'unknown'),'guard_release'=>false,'execution_allowed'=>false,'manual_approval_required'=>true,'reason'=>$eligible?'verified_approval_is_ready_but_guard_release_is_not_automatic':'unlock_eligibility_is_not_valid','evaluated_at'=>time()];
        update_option(self::OPTION,$result,false); return $result;
    }
    public static function render(): void { if(!current_user_can(self::CAPABILITY)) return; $s=self::evaluate(); echo '<div class="wrap"><h1>SLA Drift Guard Release</h1><p>Phase 163 introduces a separate guard-release readiness state. It never releases the guard automatically.</p><table class="widefat striped"><tbody>'; foreach(['Release status'=>strtoupper(str_replace('_',' ',$s['release_status'])),'Unlock status'=>strtoupper(str_replace('_',' ',$s['unlock_status'])),'Guard release'=>$s['guard_release']?'YES':'NO','Execution allowed'=>$s['execution_allowed']?'YES':'NO','Manual approval required'=>$s['manual_approval_required']?'YES':'NO','Reason'=>str_replace('_',' ',$s['reason']),'Evaluated at'=>wp_date('Y-m-d H:i:s',$s['evaluated_at'])] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>'; echo '</tbody></table></div>'; }
}
