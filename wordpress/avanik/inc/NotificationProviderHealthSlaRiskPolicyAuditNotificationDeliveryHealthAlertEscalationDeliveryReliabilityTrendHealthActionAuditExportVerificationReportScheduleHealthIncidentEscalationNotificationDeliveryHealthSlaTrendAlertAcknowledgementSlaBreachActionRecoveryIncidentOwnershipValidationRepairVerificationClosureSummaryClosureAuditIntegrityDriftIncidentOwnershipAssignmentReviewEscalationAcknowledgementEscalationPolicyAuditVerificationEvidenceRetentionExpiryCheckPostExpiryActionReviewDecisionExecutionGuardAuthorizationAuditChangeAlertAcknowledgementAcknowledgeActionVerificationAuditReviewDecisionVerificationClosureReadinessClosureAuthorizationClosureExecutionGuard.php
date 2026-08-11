<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignmentReviewEscalationAcknowledgementEscalationPolicyAuditVerificationEvidenceRetentionExpiryCheckPostExpiryActionReviewDecisionExecutionGuardAuthorizationAuditChangeAlertAcknowledgementAcknowledgeActionVerificationAuditReviewDecisionVerificationClosureReadinessClosureAuthorizationClosureExecutionGuard {
    private const OPTION='avanik_sla_drift_closure_execution_guard';
    private const CAPABILITY='manage_options';
    public static function register(): void { add_options_page('SLA Drift Closure Execution Guard','SLA Drift Closure Execution Guard',self::CAPABILITY,'avanik-sla-drift-closure-execution-guard',[self::class,'render']); }
    public static function evaluate(): array {
        $auth=get_option('avanik_sla_drift_review_closure_authorization',[]);
        $auth=is_array($auth)?$auth:[];
        $authorized=($auth['authorization_status']??'')==='eligible' && !empty($auth['manual_approval_required']);
        $result=['guard_status'=>$authorized?'blocked_pending_manual_approval':'blocked','authorization_status'=>(string)($auth['authorization_status']??'unknown'),'manual_approval_required'=>true,'execution_allowed'=>false,'reason'=>$authorized?'manual_approval_is_required_before_execution':'closure_authorization_is_not_eligible','evaluated_at'=>time()];
        update_option(self::OPTION,$result,false); return $result;
    }
    public static function render(): void { if(!current_user_can(self::CAPABILITY)) return; $s=self::evaluate(); echo '<div class="wrap"><h1>SLA Drift Closure Execution Guard</h1><p>Phase 159 adds a hard execution guard so closure eligibility can never directly execute a closure operation.</p><table class="widefat striped"><tbody>'; foreach(['Guard status'=>strtoupper(str_replace('_',' ',$s['guard_status'])),'Authorization status'=>strtoupper($s['authorization_status']),'Manual approval required'=>$s['manual_approval_required']?'YES':'NO','Execution allowed'=>$s['execution_allowed']?'YES':'NO','Reason'=>str_replace('_',' ',$s['reason']),'Evaluated at'=>wp_date('Y-m-d H:i:s',$s['evaluated_at'])] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>'; echo '</tbody></table></div>'; }
}
