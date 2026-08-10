<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryMetrics {
    private const OPTION = 'avanik_provider_sla_risk_policy_audit_notification_delivery_metrics';
    private const EVENTS = [
        'provider_sla_risk_policy_audit_integrity_failed',
        'provider_sla_risk_policy_audit_integrity_recovered',
    ];

    public static function register(): void {
        add_action('avanik_notification_delivery_result', [self::class, 'record'], 30, 10);
        add_options_page('Provider SLA Notification Metrics', 'Provider SLA Notification Metrics', 'manage_options', 'avanik-provider-sla-notification-metrics', [self::class, 'render']);
    }

    public static function record(int $notificationId, string $event, string $role, int $userId, string $channel, string $status, string $provider = '', string $providerMessage = '', string $errorCode = '', string $errorMessage = '', array $meta = []): void {
        if (!in_array($event, self::EVENTS, true)) return;
        $metrics = self::metrics();
        $channel = sanitize_key($channel);
        $status = sanitize_key($status);
        $provider = sanitize_key($provider);
        $metrics['total']++;
        $metrics['last_at'] = time();
        $metrics['events'][$event] = ($metrics['events'][$event] ?? 0) + 1;
        $metrics['statuses'][$status] = ($metrics['statuses'][$status] ?? 0) + 1;
        $metrics['channels'][$channel] = ($metrics['channels'][$channel] ?? 0) + 1;
        if ($provider !== '') $metrics['providers'][$provider] = ($metrics['providers'][$provider] ?? 0) + 1;
        if (in_array($status, ['failed', 'error'], true)) $metrics['failures']++;
        update_option(self::OPTION, $metrics, false);
    }

    public static function metrics(): array {
        $defaults = ['total'=>0,'failures'=>0,'last_at'=>0,'events'=>[],'statuses'=>[],'channels'=>[],'providers'=>[]];
        $stored = get_option(self::OPTION, []);
        return wp_parse_args(is_array($stored) ? $stored : [], $defaults);
    }

    public static function render(): void {
        if (!current_user_can('manage_options')) return;
        $m = self::metrics();
        echo '<div class="wrap"><h1>Provider SLA Notification Metrics</h1>';
        echo '<p><strong>Total:</strong> '.(int)$m['total'].' &nbsp; <strong>Failures:</strong> '.(int)$m['failures'].' &nbsp; <strong>Last event:</strong> '.esc_html($m['last_at'] ? wp_date('Y-m-d H:i:s', (int)$m['last_at']) : '—').'</p>';
        foreach (['events'=>'Events','statuses'=>'Statuses','channels'=>'Channels','providers'=>'Providers'] as $group=>$title) {
            echo '<h2>'.esc_html($title).'</h2><table class="widefat striped"><tbody>';
            foreach ($m[$group] as $key=>$value) echo '<tr><td>'.esc_html($key).'</td><td>'.(int)$value.'</td></tr>';
            echo '</tbody></table>';
        }
        echo '</div>';
    }
}
