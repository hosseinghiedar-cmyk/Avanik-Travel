<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncident {
    private const OPTION = 'avanik_sla_verification_schedule_health_incident';
    private const CAPABILITY = 'manage_options';

    public static function register(): void {
        add_options_page('SLA Schedule Health Incident', 'SLA Schedule Health Incident', self::CAPABILITY, 'avanik-sla-schedule-health-incident', [self::class, 'render']);
    }

    public static function state(): array {
        $health = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealth::state();
        $previous = get_option(self::OPTION, []);
        $previous = is_array($previous) ? $previous : [];
        $current = !$health['healthy'] ? 'attention' : 'healthy';
        $incident = $current === 'attention' && ($previous['status'] ?? 'healthy') !== 'attention';
        $state = ['status'=>$current,'transition'=>$incident ? 'opened' : ($current === 'healthy' && ($previous['status'] ?? '') === 'attention' ? 'resolved' : 'steady'),'at'=>time(),'next_run'=>$health['next_run'],'last_refresh'=>$health['last_refresh']];
        update_option(self::OPTION, $state, false);
        return $state;
    }

    public static function render(): void {
        if (!current_user_can(self::CAPABILITY)) return;
        $s = self::state();
        echo '<div class="wrap"><h1>SLA Schedule Health Incident</h1><p>Tracks only health-state transitions for the existing scheduler; it does not create a duplicate audit stream.</p><table class="widefat striped"><tbody>';
        foreach (['Status'=>strtoupper($s['status']),'Transition'=>strtoupper($s['transition']),'At'=>wp_date('Y-m-d H:i:s',$s['at']),'Next run'=>$s['next_run'] ? wp_date('Y-m-d H:i:s',$s['next_run']) : '—','Last refresh'=>$s['last_refresh'] ? wp_date('Y-m-d H:i:s',$s['last_refresh']) : '—'] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
