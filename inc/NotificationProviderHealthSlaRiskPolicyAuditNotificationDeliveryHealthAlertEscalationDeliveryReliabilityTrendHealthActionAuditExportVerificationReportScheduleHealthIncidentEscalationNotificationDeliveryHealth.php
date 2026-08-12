<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealth {
    private const OPTION = 'avanik_sla_schedule_escalation_notification_delivery_health';
    private const CAPABILITY = 'manage_options';

    public static function register(): void {
        add_options_page('SLA Escalation Notification Delivery Health', 'SLA Escalation Notification Delivery Health', self::CAPABILITY, 'avanik-sla-escalation-notification-delivery-health', [self::class, 'render']);
    }

    public static function record(bool $sent, string $level): void {
        $state = get_option(self::OPTION, []);
        $state = is_array($state) ? $state : [];
        $state['level'] = $level;
        $state['attempts'] = (int)($state['attempts'] ?? 0) + 1;
        $state['successes'] = (int)($state['successes'] ?? 0) + ($sent ? 1 : 0);
        $state['failures'] = (int)($state['failures'] ?? 0) + ($sent ? 0 : 1);
        $state['last_result'] = $sent ? 'sent' : 'failed';
        $state['last_attempt_at'] = time();
        update_option(self::OPTION, $state, false);
    }

    public static function state(): array {
        $state = get_option(self::OPTION, []);
        return is_array($state) ? $state : [];
    }

    public static function render(): void {
        if (!current_user_can(self::CAPABILITY)) return;
        $s = self::state();
        echo '<div class="wrap"><h1>SLA Escalation Notification Delivery Health</h1><p>Tracks only the result metadata of escalation notification attempts; message payloads and credentials are not stored.</p><table class="widefat striped"><tbody>';
        foreach (['Current level'=>strtoupper((string)($s['level'] ?? 'none')),'Attempts'=>(int)($s['attempts'] ?? 0),'Successful sends'=>(int)($s['successes'] ?? 0),'Failed sends'=>(int)($s['failures'] ?? 0),'Last result'=>strtoupper((string)($s['last_result'] ?? 'none')),'Last attempt'=>!empty($s['last_attempt_at']) ? wp_date('Y-m-d H:i:s',(int)$s['last_attempt_at']) : '—'] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
