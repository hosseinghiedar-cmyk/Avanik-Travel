<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignmentReviewEscalationAcknowledgementEscalationPolicyAuditVerificationEvidenceRetentionExpiryCheckPostExpiryActionReviewDecisionExecutionGuardAuthorizationAuditChangeAlertAcknowledgementAcknowledgeActionVerificationAuditReviewDecisionVerificationClosureReadinessClosureAuthorizationClosureExecutionGuardManualApprovalVerificationExecutionUnlockGuardReleaseGuardReleaseVerificationGuardReleaseApprovalVerificationGuardReleaseAuditGuardReleaseAuditVerificationGuardReleaseDecisionGuardReleaseDecisionVerificationGuardReleaseDecisionAudit {
    private const OPTION='avanik_sla_drift_guard_release_decision_audit';
    private const CAPABILITY='manage_options';
    public static function register(): void { add_options_page('SLA Drift Guard Release Decision Audit','SLA Drift Guard Release Decision Audit',self::CAPABILITY,'avanik-sla-drift-guard-release-decision-audit',[self::class,'render']); }
    public static function evaluate(): array {
        $v=get_option('avanik_sla_drift_guard_release_decision_verification',[]); $v=is_array($v)?$v:[];
        $valid=($v['verification_status']??'')==='verified' && ($v['decision_status']??'')==='eligible_for_review_decision' && ($v['decision']??'')==='pending_review';
        $result=['audit_status'=>$valid?'verified':'blocked','source_verification'=>(string)($v['verification_status']??'failed'),'decision_status'=>(string)($v['decision_status']??'unknown'),'decision'=>(string)($v['decision']??'unknown'),'guard_release'=>false,'execution_allowed'=>false,'event'=>'guard_release_decision_verified','reason'=>$valid?'decision_verification_is_auditable_and_final_release_remains_pending':'decision_verification_is_not_valid_for_audit','audited_at'=>time()];
        update_option(self::OPTION,$result,false); return $result;
    }
    public static function render(): void { if(!current_user_can(self::CAPABILITY)) return; $s=self::evaluate(); echo '<div class="wrap"><h1>SLA Drift Guard Release Decision Audit</h1><p>Phase 171 records an auditable snapshot of the verified Phase 169 decision state while keeping final release and execution locked.</p><table class="widefat striped"><tbody>'; foreach(['Audit status'=>strtoupper($s['audit_status']),'Source verification'=>strtoupper($s['source_verification']),'Decision status'=>strtoupper(str_replace('_',' ',$s['decision_status'])),'Decision'=>strtoupper(str_replace('_',' ',$s['decision'])),'Guard release'=>$s['guard_release']?'YES':'NO','Execution allowed'=>$s['execution_allowed']?'YES':'NO','Event'=>str_replace('_',' ',$s['event']),'Reason'=>str_replace('_',' ',$s['reason']),'Audited at'=>wp_date('Y-m-d H:i:s',$s['audited_at'])] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>'; echo '</tbody></table></div>'; }
}
