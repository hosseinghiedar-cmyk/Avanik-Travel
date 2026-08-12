<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlert {
    private const OPTION = 'avanik_sla_notification_delivery_health_sla_trend_alert';
    private const CAPABILITY = 'manage_options';
    private const WINDOW = 3;

    public static function register(): void {
        add_options_page('SLA Delivery Trend Alert', 'SLA Delivery Trend Alert', self::CAPABILITY, 'avanik-sla-delivery-trend-alert', [self::class, 'render']);
    }

    public static function evaluate(): array {
        $points = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrend::state();
        $recent = array_slice($points, -self::WINDOW);
        $rates = array_map(static fn($p) => (float)($p['rate'] ?? 100), $recent);
        $declining = count($rates) >= 2 && $rates[count($rates)-1] < $rates[0];
        $alert = $declining && $rates[count($rates)-1] < 99.0;
        $state = ['alert'=>$alert,'direction'=>$declining ? 'declining' : 'stable','window'=>count($rates),'latest_rate'=>$rates ? end($rates) : 100.0,'evaluated_at'=>time()];
        update_option(self::OPTION, $state, false);
        return $state;
    }

    public static function render(): void {
        if (!current_user_can(self::CAPABILITY)) return;
        $s = self::evaluate();
        echo '<div class="wrap"><h1>SLA Delivery Trend Alert</h1><p>Detects a short-window decline in notification delivery SLA performance.</p><table class="widefat striped"><tbody>';
        foreach (['Alert'=>$s['alert'] ? 'YES' : 'NO','Direction'=>strtoupper($s['direction']),'Window points'=>$s['window'],'Latest success rate'=>$s['latest_rate'].'%','Evaluated at'=>wp_date('Y-m-d H:i:s',$s['evaluated_at'])] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
