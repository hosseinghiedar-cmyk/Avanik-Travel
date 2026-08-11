<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignmentReviewEscalationAcknowledgement {
    private const OPTION = 'avanik_sla_drift_ownership_escalation_acknowledgement';
    private const CAPABILITY = 'manage_options';

    public static function register(): void {
        add_options_page('SLA Drift Ownership Escalation Acknowledgement', 'SLA Drift Ownership Escalation Acknowledgement', self::CAPABILITY, 'avanik-sla-drift-ownership-escalation-ack', [self::class, 'render']);
    }

    public static function evaluate(): array {
        $escalation = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosureSummaryClosureAuditIntegrityDriftIncidentOwnershipAssignmentReviewEscalation::evaluate();
        $previous = get_option(self::OPTION, []);
        $previous = is_array($previous) ? $previous : [];
        $required = !empty($escalation['escalation_required']);
        $ack = !empty($previous['acknowledged']);
        $state = [
            'escalation_required'=>$required,
            'acknowledged'=>$ack && $required,
            'acknowledgement_required'=>$required && !$ack,
            'status'=>$required ? ($ack ? 'acknowledged' : 'acknowledgement_required') : 'closed',
            'transition'=>$required && !$ack && empty($previous['acknowledgement_required']) ? 'opened' : ($required && !$ack ? 'steady' : (!$required && !empty($previous['acknowledgement_required']) ? 'resolved' : 'none')),
            'owner_id'=>(int)$escalation['owner_id'],
            'evaluated_at'=>time(),
        ];
        update_option(self::OPTION, $state, false);
        return $state;
    }

    public static function render(): void {
        if (!current_user_can(self::CAPABILITY)) return;
        $s=self::evaluate();
        echo '<div class="wrap"><h1>SLA Drift Ownership Escalation Acknowledgement</h1><p>Phase 135 tracks whether an active ownership-review escalation has been acknowledged.</p><table class="widefat striped"><tbody>';
        foreach (['Escalation required'=>$s['escalation_required']?'YES':'NO','Acknowledged'=>$s['acknowledged']?'YES':'NO','Acknowledgement required'=>$s['acknowledgement_required']?'YES':'NO','Status'=>strtoupper(str_replace('_',' ',$s['status'])),'Transition'=>strtoupper($s['transition']),'Owner user ID'=>$s['owner_id'],'Evaluated at'=>wp_date('Y-m-d H:i:s',$s['evaluated_at'])] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
