<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepairVerification {
    private const OPTION = 'avanik_sla_ownership_repair_verification';
    private const CAPABILITY = 'manage_options';

    public static function register(): void {
        add_options_page('SLA Ownership Repair Verification', 'SLA Ownership Repair Verification', self::CAPABILITY, 'avanik-sla-ownership-repair-verification', [self::class, 'render']);
    }

    public static function evaluate(): array {
        $validation = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidation::evaluate();
        $repair = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepair::evaluate();
        $state = [
            'validation_state'=>(string)$validation['state'],
            'repair_needed'=>!empty($repair['repair_needed']),
            'verified'=>((string)$validation['state'] === 'valid'),
            'status'=>((string)$validation['state'] === 'valid' ? 'verified' : ((string)$validation['state'] === 'invalid' ? 'verification_required' : 'pending')),
            'evaluated_at'=>time(),
        ];
        update_option(self::OPTION, $state, false);
        return $state;
    }

    public static function render(): void {
        if (!current_user_can(self::CAPABILITY)) return;
        $s=self::evaluate();
        echo '<div class="wrap"><h1>SLA Ownership Repair Verification</h1><p>Phase 124 verifies the current incident-owner state after the Phase 123 repair layer.</p><table class="widefat striped"><tbody>';
        foreach (['Validation state'=>strtoupper($s['validation_state']),'Repair needed'=>$s['repair_needed']?'YES':'NO','Verified'=>$s['verified']?'YES':'NO','Status'=>strtoupper($s['status']),'Evaluated at'=>wp_date('Y-m-d H:i:s',$s['evaluated_at'])] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
