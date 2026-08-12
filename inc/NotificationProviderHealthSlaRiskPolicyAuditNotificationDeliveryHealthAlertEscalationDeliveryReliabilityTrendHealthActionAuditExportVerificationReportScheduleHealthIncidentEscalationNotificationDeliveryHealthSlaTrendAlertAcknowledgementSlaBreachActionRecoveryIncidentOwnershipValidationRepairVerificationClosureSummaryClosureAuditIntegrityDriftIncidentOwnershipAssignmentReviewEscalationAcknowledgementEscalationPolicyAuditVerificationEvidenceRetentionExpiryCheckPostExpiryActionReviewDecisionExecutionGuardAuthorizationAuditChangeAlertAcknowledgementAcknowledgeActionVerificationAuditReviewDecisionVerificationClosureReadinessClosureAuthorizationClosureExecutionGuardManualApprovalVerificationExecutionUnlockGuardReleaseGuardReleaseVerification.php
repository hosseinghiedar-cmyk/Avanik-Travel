<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignmentReviewEscalationAcknowledgementEscalationPolicyAuditVerificationEvidenceRetentionExpiryCheckPostExpiryActionReviewDecisionExecutionGuardAuthorizationAuditChangeAlertAcknowledgementAcknowledgeActionVerificationAuditReviewDecisionVerificationClosureReadinessClosureAuthorizationClosureExecutionGuardManualApprovalVerificationExecutionUnlockGuardReleaseGuardReleaseVerification {
    private const OPTION='avanik_sla_drift_guard_release_verification';
    private const CAPABILITY='manage_options';
    public static function register(): void { add_options_page('SLA Drift Guard Release Verification','SLA Drift Guard Release Verification',self::CAPABILITY,'avanik-sla-drift-guard-release-verification',[self::class,'render']); }
    public static function evaluate(): array {
        $r=get_option('avanik_sla_drift_guard_release',[]); $r=is_array($r)?$r:[];
        $valid=($r['release_status']??'')==='ready_for_guard_release' && ($r['guard_release']??true)===false && ($r['execution_allowed']??true)===false;
        $result=['verification_status'=>$valid?'verified':'failed','release_status'=>(string)($r['release_status']??'unknown'),'guard_release'=>(bool)($r['guard_release']??false),'execution_allowed'=>false,'reason'=>$valid?'guard_release_state_is_consistent_and_not_automatically_released':'guard_release_readiness_state_is_invalid','verified_at'=>time()];
        update_option(self::OPTION,$result,false); return $result;
    }
    public static function render(): void { if(!current_user_can(self::CAPABILITY)) return; $s=self::evaluate(); echo '<div class="wrap"><h1>SLA Drift Guard Release Verification</h1><p>Phase 164 verifies the Phase 163 release-readiness state before any separate guard-release action.</p><table class="widefat striped"><tbody>'; foreach(['Verification status'=>strtoupper($s['verification_status']),'Release status'=>strtoupper(str_replace('_',' ',$s['release_status'])),'Guard release'=>$s['guard_release']?'YES':'NO','Execution allowed'=>$s['execution_allowed']?'YES':'NO','Reason'=>str_replace('_',' ',$s['reason']),'Verified at'=>wp_date('Y-m-d H:i:s',$s['verified_at'])] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>'; echo '</tbody></table></div>'; }
}
