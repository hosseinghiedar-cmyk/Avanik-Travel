<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnershipValidation {
    private const OPTION = 'avanik_sla_recovery_incident_ownership_validation';
    private const CAPABILITY = 'manage_options';

    public static function register(): void {
        add_options_page('SLA Incident Ownership Validation', 'SLA Incident Ownership Validation', self::CAPABILITY, 'avanik-sla-incident-ownership-validation', [self::class, 'render']);
    }

    public static function evaluate(): array {
        $ownership = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncidentOwnership::state();
        $owner_id = (int)($ownership['owner_id'] ?? 0);
        $user = $owner_id > 0 ? get_user_by('id', $owner_id) : false;
        $valid = (bool)$user && user_can($user, self::CAPABILITY);
        $state = [
            'owner_id' => $owner_id,
            'valid' => $valid,
            'state' => $owner_id === 0 ? 'missing' : ($valid ? 'valid' : 'invalid'),
            'incident' => (string)($ownership['incident'] ?? 'none'),
            'evaluated_at' => time(),
        ];
        update_option(self::OPTION, $state, false);
        return $state;
    }

    public static function state(): array {
        $s = get_option(self::OPTION, []);
        return is_array($s) ? $s : [];
    }

    public static function render(): void {
        if (!current_user_can(self::CAPABILITY)) return;
        $s = self::evaluate();
        echo '<div class="wrap"><h1>SLA Incident Ownership Validation</h1><p>Validates that the Phase 120 incident owner exists and has the required administrator capability.</p><table class="widefat striped"><tbody>';
        foreach ([
            'Incident'=>strtoupper($s['incident']),
            'Owner user ID'=>$s['owner_id'],
            'Validation state'=>strtoupper($s['state']),
            'Valid owner'=>$s['valid'] ? 'YES' : 'NO',
            'Evaluated at'=>wp_date('Y-m-d H:i:s',$s['evaluated_at']),
        ] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
