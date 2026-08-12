<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignmentReviewEscalationAcknowledgementEscalationPolicyAuditVerificationEvidenceRetentionExpiryCheckPostExpiryActionReviewDecisionExecutionGuardAuthorizationAuditChangeAlertAcknowledgementAcknowledgeAction {
    private const OPTION = 'avanik_sla_drift_authorization_alert_ack_action';
    private const CAPABILITY = 'manage_options';

    public static function register(): void {
        add_options_page('SLA Drift Alert Acknowledge Action', 'SLA Drift Alert Acknowledge Action', self::CAPABILITY, 'avanik-sla-drift-alert-ack-action', [self::class, 'render']);
    }

    public static function acknowledge(): array {
        if (!current_user_can(self::CAPABILITY)) return ['success'=>false,'reason'=>'forbidden'];
        $state = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignmentReviewEscalationAcknowledgementEscalationPolicyAuditVerificationEvidenceRetentionExpiryCheckPostExpiryActionReviewDecisionExecutionGuardAuthorizationAuditChangeAlertAcknowledgement::evaluate();
        if (empty($state['acknowledgement_required'])) return ['success'=>false,'reason'=>'acknowledgement_not_required'];
        $state['acknowledged']=true;
        $state['acknowledgement_status']='acknowledged';
        $state['transition']='acknowledged';
        $state['acknowledged_by']=get_current_user_id();
        $state['acknowledged_at']=time();
        update_option(self::OPTION,$state,false);
        return ['success'=>true,'state'=>$state];
    }

    public static function render(): void {
        if (!current_user_can(self::CAPABILITY)) return;
        $result=self::acknowledge();
        echo '<div class="wrap"><h1>SLA Drift Alert Acknowledge Action</h1><p>Phase 150 provides an explicit administrator acknowledgement action for a pending Phase 149 alert.</p><pre>'.esc_html(wp_json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)).'</pre></div>';
    }
}
