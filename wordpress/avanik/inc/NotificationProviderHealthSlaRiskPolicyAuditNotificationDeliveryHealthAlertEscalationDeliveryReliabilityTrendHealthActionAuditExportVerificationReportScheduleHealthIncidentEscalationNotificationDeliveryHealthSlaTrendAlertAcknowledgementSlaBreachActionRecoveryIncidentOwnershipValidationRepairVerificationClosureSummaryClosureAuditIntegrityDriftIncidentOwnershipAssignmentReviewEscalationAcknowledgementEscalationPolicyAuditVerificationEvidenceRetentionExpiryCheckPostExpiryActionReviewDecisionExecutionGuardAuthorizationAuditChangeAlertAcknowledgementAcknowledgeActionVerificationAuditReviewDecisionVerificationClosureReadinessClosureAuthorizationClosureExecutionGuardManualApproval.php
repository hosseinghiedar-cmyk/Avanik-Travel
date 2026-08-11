<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignmentReviewEscalationAcknowledgementEscalationPolicyAuditVerificationEvidenceRetentionExpiryCheckPostExpiryActionReviewDecisionExecutionGuardAuthorizationAuditChangeAlertAcknowledgementAcknowledgeActionVerificationAuditReviewDecisionVerificationClosureReadinessClosureAuthorizationClosureExecutionGuardManualApproval {
    private const OPTION='avanik_sla_drift_closure_manual_approval';
    private const CAPABILITY='manage_options';
    public static function register(): void { add_options_page('SLA Drift Closure Manual Approval','SLA Drift Closure Manual Approval',self::CAPABILITY,'avanik-sla-drift-closure-manual-approval',[self::class,'render']); }
    public static function approve(): array {
        if(!current_user_can(self::CAPABILITY)) return ['success'=>false,'reason'=>'forbidden'];
        $guard=get_option('avanik_sla_drift_closure_execution_guard',[]); $guard=is_array($guard)?$guard:[];
        $eligible=($guard['authorization_status']??'')==='eligible' && ($guard['guard_status']??'')==='blocked_pending_manual_approval';
        if(!$eligible) return ['success'=>false,'reason'=>'manual_approval_not_available'];
        $state=['approval_status'=>'approved','approved_by'=>get_current_user_id(),'approved_at'=>time(),'execution_allowed'=>false,'reason'=>'approval_recorded_but_execution_remains_guarded'];
        update_option(self::OPTION,$state,false); return ['success'=>true,'state'=>$state];
    }
    public static function render(): void { if(!current_user_can(self::CAPABILITY)) return; $r=self::approve(); echo '<div class="wrap"><h1>SLA Drift Closure Manual Approval</h1><p>Phase 160 records explicit administrator approval while preserving the execution guard.</p><pre>'.esc_html(wp_json_encode($r,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)).'</pre></div>'; }
}
