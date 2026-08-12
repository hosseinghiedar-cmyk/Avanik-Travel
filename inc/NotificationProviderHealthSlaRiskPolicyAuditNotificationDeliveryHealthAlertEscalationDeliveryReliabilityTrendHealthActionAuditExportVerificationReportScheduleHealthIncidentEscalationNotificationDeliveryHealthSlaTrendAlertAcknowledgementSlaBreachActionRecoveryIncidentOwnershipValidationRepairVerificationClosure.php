<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerificationClosure {
    private const OPTION = 'avanik_sla_ownership_repair_verification_closure';
    private const CAPABILITY = 'manage_options';

    public static function register(): void {
        add_options_page('SLA Ownership Verification Closure', 'SLA Ownership Verification Closure', self::CAPABILITY, 'avanik-sla-ownership-verification-closure', [self::class, 'render']);
    }

    public static function evaluate(): array {
        $verification = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerification::evaluate();
        $incident = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncident::evaluate();
        $previous = get_option(self::OPTION, []);
        $previous = is_array($previous) ? $previous : [];
        $closed = $verification['status'] === 'verified' && $incident['incident'] !== 'open';
        $transition = $closed && empty($previous['closed']) ? 'closed' : ($closed ? 'steady' : 'open');
        $state = ['closed'=>$closed,'transition'=>$transition,'verification_status'=>(string)$verification['status'],'incident'=>(string)$incident['incident'],'closed_at'=>$closed ? time() : (int)($previous['closed_at'] ?? 0),'evaluated_at'=>time()];
        update_option(self::OPTION, $state, false);
        return $state;
    }

    public static function render(): void {
        if (!current_user_can(self::CAPABILITY)) return;
        $s=self::evaluate();
        echo '<div class="wrap"><h1>SLA Ownership Verification Closure</h1><p>Phase 125 closes the ownership-repair lifecycle only after verification is successful and the incident is no longer open.</p><table class="widefat striped"><tbody>';
        foreach (['Closed'=>$s['closed']?'YES':'NO','Transition'=>strtoupper($s['transition']),'Verification status'=>strtoupper($s['verification_status']),'Incident'=>strtoupper($s['incident']),'Closed at'=>$s['closed_at'] ? wp_date('Y-m-d H:i:s',$s['closed_at']) : '—','Evaluated at'=>wp_date('Y-m-d H:i:s',$s['evaluated_at'])] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
