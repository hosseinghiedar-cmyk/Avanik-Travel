<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignmentReviewEscalationAcknowledgementEscalationPolicyAuditVerificationEvidenceRetentionExpiryCheckPostExpiryActionReviewDecisionExecutionGuardAuthorizationAuditChangeAlertAcknowledgementAcknowledgeActionVerificationAuditReviewDecisionVerificationClosureReadinessClosureAuthorizationClosureExecutionGuardManualApprovalVerificationExecutionUnlockGuardReleaseGuardReleaseVerificationGuardReleaseApprovalVerificationGuardReleaseAuditGuardReleaseAuditVerificationGuardReleaseDecisionGuardReleaseDecisionVerification {
    private const OPTION='avanik_sla_drift_guard_release_decision_verification';
    private const CAPABILITY='manage_options';
    public static function register(): void { add_options_page('SLA Drift Guard Release Decision Verification','SLA Drift Guard Release Decision Verification',self::CAPABILITY,'avanik-sla-drift-guard-release-decision-verification',[self::class,'render']); }
    public static function evaluate(): array {
        $d=get_option('avanik_sla_drift_guard_release_decision',[]); $d=is_array($d)?$d:[];
        $valid=($d['decision_status']??'')==='eligible_for_review_decision' && ($d['decision']??'')==='pending_review' && ($d['guard_release']??true)===false && ($d['execution_allowed']??true)===false;
        $result=['verification_status'=>$valid?'verified':'failed','decision_status'=>(string)($d['decision_status']??'unknown'),'decision'=>(string)($d['decision']??'unknown'),'guard_release'=>false,'execution_allowed'=>false,'reason'=>$valid?'review_decision_state_is_consistent_and_still_pending':'review_decision_state_is_invalid','verified_at'=>time()];
        update_option(self::OPTION,$result,false); return $result;
    }
    public static function render(): void { if(!current_user_can(self::CAPABILITY)) return; $s=self::evaluate(); echo '<div class="wrap"><h1>SLA Drift Guard Release Decision Verification</h1><p>Phase 170 verifies the Phase 169 decision state before a future review decision is recorded.</p><table class="widefat striped"><tbody>'; foreach(['Verification status'=>strtoupper($s['verification_status']),'Decision status'=>strtoupper(str_replace('_',' ',$s['decision_status'])),'Decision'=>strtoupper(str_replace('_',' ',$s['decision'])),'Guard release'=>$s['guard_release']?'YES':'NO','Execution allowed'=>$s['execution_allowed']?'YES':'NO','Reason'=>str_replace('_',' ',$s['reason']),'Verified at'=>wp_date('Y-m-d H:i:s',$s['verified_at'])] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>'; echo '</tbody></table></div>'; }
}
