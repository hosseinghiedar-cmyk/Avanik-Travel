<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignmentReviewEscalationAcknowledgementEscalationPolicy {
    private const OPTION = 'avanik_sla_drift_escalation_ack_policy';
    private const CAPABILITY = 'manage_options';

    public static function register(): void {
        add_options_page('SLA Drift Escalation Acknowledgement Policy', 'SLA Drift Escalation Acknowledgement Policy', self::CAPABILITY, 'avanik-sla-drift-escalation-ack-policy', [self::class, 'render']);
    }

    public static function evaluate(): array {
        $ack = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignmentReviewEscalationAcknowledgement::evaluate();
        $previous = get_option(self::OPTION, []);
        $previous = is_array($previous) ? $previous : [];
        $required = !empty($ack['acknowledgement_required']);
        $state = [
            'acknowledgement_required'=>$required,
            'policy'=>'single_owner_ack',
            'grace_state'=>$required ? 'awaiting_ack' : 'not_applicable',
            'transition'=>$required && empty($previous['acknowledgement_required']) ? 'opened' : ($required ? 'steady' : (!empty($previous['acknowledgement_required']) ? 'resolved' : 'none')),
            'evaluated_at'=>time(),
        ];
        update_option(self::OPTION, $state, false);
        return $state;
    }

    public static function render(): void {
        if (!current_user_can(self::CAPABILITY)) return;
        $s=self::evaluate();
        echo '<div class="wrap"><h1>SLA Drift Escalation Acknowledgement Policy</h1><p>Phase 136 defines the acknowledgement policy metadata without sending or scheduling notifications.</p><table class="widefat striped"><tbody>';
        foreach (['Acknowledgement required'=>$s['acknowledgement_required']?'YES':'NO','Policy'=>strtoupper(str_replace('_',' ',$s['policy'])),'Grace state'=>strtoupper(str_replace('_',' ',$s['grace_state'])),'Transition'=>strtoupper($s['transition']),'Evaluated at'=>wp_date('Y-m-d H:i:s',$s['evaluated_at'])] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
