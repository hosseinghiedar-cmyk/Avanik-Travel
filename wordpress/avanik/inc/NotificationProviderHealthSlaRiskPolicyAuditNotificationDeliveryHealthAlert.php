<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlert {
    private const OPTION = 'avanik_provider_sla_notification_health_alert';
    private const FAILURE_THRESHOLD = 3;
    private const EVENTS = [
        'provider_sla_risk_policy_audit_integrity_failed',
        'provider_sla_risk_policy_audit_integrity_recovered',
    ];

    public static function register(): void {
        add_action('avanik_notification_delivery_result', [self::class, 'evaluate'], 50, 10);
        add_options_page('Provider SLA Notification Health Alert', 'Provider SLA Notification Health Alert', 'manage_options', 'avanik-provider-sla-notification-health-alert', [self::class, 'render']);
    }

    public static function evaluate(int $notificationId, string $event, string $role, int $userId, string $channel, string $status, string $provider = '', string $providerMessage = '', string $errorCode = '', string $errorMessage = '', array $meta = []): void {
        if (!in_array($event, self::EVENTS, true)) return;
        $status = sanitize_key($status);
        $state = self::state();
        $state['last_at'] = time();
        $state['last_status'] = $status;
        if (in_array($status, ['failed', 'error'], true)) {
            $state['consecutive_failures']++;
            if ($state['consecutive_failures'] >= self::FAILURE_THRESHOLD && !$state['active']) {
                $state['active'] = true;
                $state['alerted_at'] = time();
                $state['alert_count']++;
                do_action('avanik_provider_sla_notification_health_alert', $state['consecutive_failures'], $provider, $channel, $errorCode);
            }
        } elseif ($status === 'sent' || $status === 'success') {
            $state['consecutive_failures'] = 0;
            if ($state['active']) {
                $state['active'] = false;
                $state['recovered_at'] = time();
                do_action('avanik_provider_sla_notification_health_recovered', $provider, $channel);
            }
        }
        update_option(self::OPTION, $state, false);
    }

    public static function state(): array {
        $defaults = ['active'=>false,'consecutive_failures'=>0,'alerted_at'=>0,'recovered_at'=>0,'last_at'=>0,'last_status'=>'','alert_count'=>0];
        $stored = get_option(self::OPTION, []);
        return wp_parse_args(is_array($stored) ? $stored : [], $defaults);
    }

    public static function render(): void {
        if (!current_user_can('manage_options')) return;
        $s = self::state();
        echo '<div class="wrap"><h1>Provider SLA Notification Health Alert</h1>';
        echo '<p><strong>Status:</strong> '.esc_html($s['active'] ? 'Alert Active' : 'Normal').' &nbsp; <strong>Threshold:</strong> '.self::FAILURE_THRESHOLD.' &nbsp; <strong>Consecutive failures:</strong> '.(int)$s['consecutive_failures'].'</p>';
        echo '<table class="widefat striped"><tbody>';
        $rows=['Last event'=>$s['last_at']?wp_date('Y-m-d H:i:s',(int)$s['last_at']):'—','Alerted at'=>$s['alerted_at']?wp_date('Y-m-d H:i:s',(int)$s['alerted_at']):'—','Recovered at'=>$s['recovered_at']?wp_date('Y-m-d H:i:s',(int)$s['recovered_at']):'—','Alert count'=>(int)$s['alert_count']];
        foreach($rows as $key=>$value) echo '<tr><th>'.esc_html($key).'</th><td>'.esc_html((string)$value).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
