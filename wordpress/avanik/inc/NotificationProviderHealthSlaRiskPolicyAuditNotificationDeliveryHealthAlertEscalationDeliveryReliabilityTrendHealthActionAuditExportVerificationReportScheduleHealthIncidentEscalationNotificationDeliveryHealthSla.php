<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSla {
    private const OPTION = 'avanik_sla_escalation_notification_delivery_health_sla';
    private const CAPABILITY = 'manage_options';

    public static function register(): void {
        add_options_page('SLA Notification Delivery SLA', 'SLA Notification Delivery SLA', self::CAPABILITY, 'avanik-sla-notification-delivery-sla', [self::class, 'render']);
    }

    public static function evaluate(): array {
        $s = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealth::state();
        $attempts = (int)($s['attempts'] ?? 0);
        $successes = (int)($s['successes'] ?? 0);
        $failures = (int)($s['failures'] ?? 0);
        $rate = $attempts > 0 ? round(($successes / $attempts) * 100, 2) : 100.0;
        $status = $rate >= 99.0 ? 'healthy' : ($rate >= 95.0 ? 'warning' : 'critical');
        $result = ['status'=>$status,'success_rate'=>$rate,'attempts'=>$attempts,'successes'=>$successes,'failures'=>$failures,'evaluated_at'=>time()];
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
        echo '<div class="wrap"><h1>SLA Notification Delivery SLA</h1><p>Phase 112 evaluates delivery success against the notification delivery SLA using the existing Phase 111 counters.</p><table class="widefat striped"><tbody>';
        foreach (['Status'=>strtoupper($s['status']),'Success rate'=>$s['success_rate'].'%','Attempts'=>$s['attempts'],'Successful sends'=>$s['successes'],'Failed sends'=>$s['failures'],'Evaluated at'=>wp_date('Y-m-d H:i:s',$s['evaluated_at'])] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
