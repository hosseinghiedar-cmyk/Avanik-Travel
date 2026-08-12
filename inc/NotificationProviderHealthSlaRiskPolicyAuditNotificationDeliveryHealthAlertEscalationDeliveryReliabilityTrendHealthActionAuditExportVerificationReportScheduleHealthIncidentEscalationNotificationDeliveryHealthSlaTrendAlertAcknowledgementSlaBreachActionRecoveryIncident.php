<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecoveryIncident {
    private const OPTION = 'avanik_sla_breach_recovery_incident';
    private const CAPABILITY = 'manage_options';

    public static function register(): void {
        add_options_page('SLA Breach Recovery Incident', 'SLA Breach Recovery Incident', self::CAPABILITY, 'avanik-sla-breach-recovery-incident', [self::class, 'render']);
    }

    public static function evaluate(): array {
        $recovery = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecovery::evaluate();
        $previous = get_option(self::OPTION, []);
        $previous = is_array($previous) ? $previous : [];
        $transition = (string)$recovery['transition'];
        $incident = $transition === 'opened' ? 'open' : ($transition === 'resolved' ? 'resolved' : (string)($previous['incident'] ?? 'none'));
        if ($recovery['status'] !== 'breach') $incident = 'resolved';
        $state = ['incident'=>$incident,'transition'=>$transition,'action'=>$recovery['action'],'at'=>time(),'previous_incident'=>(string)($previous['incident'] ?? 'none')];
        update_option(self::OPTION, $state, false);
        return $state;
    }

    public static function render(): void {
        if (!current_user_can(self::CAPABILITY)) return;
        $s = self::evaluate();
        echo '<div class="wrap"><h1>SLA Breach Recovery Incident</h1><p>Phase 119 promotes the Phase 118 breach/recovery transition into an explicit incident state.</p><table class="widefat striped"><tbody>';
        foreach (['Incident'=>strtoupper($s['incident']),'Transition'=>strtoupper($s['transition']),'Action'=>strtoupper(str_replace('_',' ',$s['action'])),'Previous incident'=>strtoupper($s['previous_incident']),'Evaluated at'=>wp_date('Y-m-d H:i:s',$s['at'])] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
