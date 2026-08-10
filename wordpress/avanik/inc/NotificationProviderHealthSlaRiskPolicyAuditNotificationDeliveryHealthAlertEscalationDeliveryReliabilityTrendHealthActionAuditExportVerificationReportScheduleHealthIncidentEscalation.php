<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalation {
    private const OPTION = 'avanik_sla_verification_schedule_health_incident_escalation';
    private const CAPABILITY = 'manage_options';

    public static function register(): void {
        add_options_page('SLA Schedule Incident Escalation', 'SLA Schedule Incident Escalation', self::CAPABILITY, 'avanik-sla-schedule-incident-escalation', [self::class, 'render']);
        NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotification::register();
    }

    public static function state(): array {
        $incident = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncident::state();
        $previous = get_option(self::OPTION, []);
        $previous = is_array($previous) ? $previous : [];
        $status = (string)($incident['status'] ?? 'healthy');
        $transition = (string)($incident['transition'] ?? 'steady');
        $opened_at = $status === 'attention' ? (int)($previous['opened_at'] ?? ($transition === 'opened' ? $incident['at'] : 0)) : 0;
        $age = $opened_at > 0 ? max(0, time() - $opened_at) : 0;
        $level = $status !== 'attention' ? 'none' : ($age >= 7200 ? 'critical' : ($age >= 3600 ? 'high' : 'warning'));
        $state = ['status'=>$status,'level'=>$level,'transition'=>$transition,'opened_at'=>$opened_at,'age'=>$age,'next_run'=>$incident['next_run'],'last_refresh'=>$incident['last_refresh'],'at'=>time()];
        update_option(self::OPTION, $state, false);
        return $state;
    }

    public static function render(): void {
        if (!current_user_can(self::CAPABILITY)) return;
        $s = self::state();
        NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotification::notify_if_needed();
        echo '<div class="wrap"><h1>SLA Schedule Incident Escalation</h1><p>Escalation is derived from the existing Phase 108 scheduler health incident state.</p><table class="widefat striped"><tbody>';
        foreach (['Status'=>strtoupper($s['status']),'Level'=>strtoupper($s['level']),'Transition'=>strtoupper($s['transition']),'Incident age (sec)'=>$s['age'],'Next run'=>$s['next_run'] ? wp_date('Y-m-d H:i:s',$s['next_run']) : '—','Last refresh'=>$s['last_refresh'] ? wp_date('Y-m-d H:i:s',$s['last_refresh']) : '—'] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
