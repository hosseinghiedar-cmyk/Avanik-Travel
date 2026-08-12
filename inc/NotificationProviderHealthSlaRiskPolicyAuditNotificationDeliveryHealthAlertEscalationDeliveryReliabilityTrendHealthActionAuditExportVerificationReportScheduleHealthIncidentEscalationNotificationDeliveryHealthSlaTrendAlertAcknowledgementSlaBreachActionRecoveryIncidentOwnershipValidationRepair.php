<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidationRepair {
    private const OPTION = 'avanik_sla_incident_ownership_validation_repair';
    private const CAPABILITY = 'manage_options';

    public static function register(): void {
        add_options_page('SLA Incident Ownership Validation Repair', 'SLA Incident Ownership Validation Repair', self::CAPABILITY, 'avanik-sla-incident-ownership-validation-repair', [self::class, 'render']);
    }

    public static function evaluate(): array {
        $validation = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidation::evaluate();
        $previous = get_option(self::OPTION, []);
        $previous = is_array($previous) ? $previous : [];
        $state = (string)$validation['state'];
        $repair_needed = $state === 'invalid';
        $transition = $repair_needed && empty($previous['repair_needed']) ? 'opened' : (!$repair_needed && !empty($previous['repair_needed']) ? 'resolved' : 'steady');
        $result = [
            'validation_state' => $state,
            'owner_id' => (int)$validation['owner_id'],
            'repair_needed' => $repair_needed,
            'transition' => $transition,
            'action' => $repair_needed ? 'owner_reassignment_required' : 'none',
            'evaluated_at' => time(),
        ];
        update_option(self::OPTION, $result, false);
        return $result;
    }

    public static function state(): array {
        $s = get_option(self::OPTION, []);
        return is_array($s) ? $s : [];
    }

    public static function render(): void {
        if (!current_user_can(self::CAPABILITY)) return;
        $s = self::evaluate();
        echo '<div class="wrap"><h1>SLA Incident Ownership Validation Repair</h1><p>Phase 123 detects invalid incident ownership and exposes the required reassignment state.</p><table class="widefat striped"><tbody>';
        foreach ([
            'Validation state'=>strtoupper($s['validation_state']),
            'Owner user ID'=>$s['owner_id'],
            'Repair needed'=>$s['repair_needed'] ? 'YES' : 'NO',
            'Transition'=>strtoupper($s['transition']),
            'Action'=>strtoupper(str_replace('_',' ',$s['action'])),
            'Evaluated at'=>wp_date('Y-m-d H:i:s',$s['evaluated_at']),
        ] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
