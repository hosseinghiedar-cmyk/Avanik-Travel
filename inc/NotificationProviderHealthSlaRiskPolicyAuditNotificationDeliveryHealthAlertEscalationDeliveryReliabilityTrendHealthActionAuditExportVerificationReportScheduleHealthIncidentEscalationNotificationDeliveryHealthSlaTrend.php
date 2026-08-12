<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrend {
    private const OPTION = 'avanik_sla_notification_delivery_health_sla_trend';
    private const CAPABILITY = 'manage_options';
    private const MAX_POINTS = 24;

    public static function register(): void {
        add_options_page('SLA Notification Delivery SLA Trend', 'SLA Notification Delivery SLA Trend', self::CAPABILITY, 'avanik-sla-notification-delivery-sla-trend', [self::class, 'render']);
    }

    public static function capture(): array {
        $sla = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSla::evaluate();
        $points = get_option(self::OPTION, []);
        $points = is_array($points) ? $points : [];
        $points[] = ['at'=>time(),'status'=>(string)$sla['status'],'rate'=>(float)$sla['success_rate'],'attempts'=>(int)$sla['attempts'],'failures'=>(int)$sla['failures']];
        $points = array_slice($points, -self::MAX_POINTS);
        update_option(self::OPTION, $points, false);
        return $points;
    }

    public static function state(): array {
        $p = get_option(self::OPTION, []);
        return is_array($p) ? $p : [];
    }

    public static function render(): void {
        if (!current_user_can(self::CAPABILITY)) return;
        $points = self::capture();
        echo '<div class="wrap"><h1>SLA Notification Delivery SLA Trend</h1><p>Last '.self::MAX_POINTS.' SLA evaluations, derived from Phase 112.</p><table class="widefat striped"><thead><tr><th>Time</th><th>Status</th><th>Success rate</th><th>Attempts</th><th>Failures</th></tr></thead><tbody>';
        foreach (array_reverse($points) as $p) echo '<tr><td>'.esc_html(wp_date('Y-m-d H:i:s',(int)$p['at'])).'</td><td>'.esc_html(strtoupper($p['status'])).'</td><td>'.esc_html((string)$p['rate']).'%</td><td>'.(int)$p['attempts'].'</td><td>'.(int)$p['failures'].'</td></tr>';
        echo '</tbody></table></div>';
    }
}
