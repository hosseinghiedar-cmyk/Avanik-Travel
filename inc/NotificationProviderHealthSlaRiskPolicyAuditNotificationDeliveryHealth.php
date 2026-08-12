<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealth {
    private const OPTION = 'avanik_provider_sla_risk_policy_audit_notification_delivery_health';
    private const EVENTS = [
        'provider_sla_risk_policy_audit_integrity_failed',
        'provider_sla_risk_policy_audit_integrity_recovered',
    ];

    public static function register(): void {
        add_action('avanik_notification_delivery_result', [self::class, 'record'], 40, 10);
        add_options_page('Provider SLA Notification Health', 'Provider SLA Notification Health', 'manage_options', 'avanik-provider-sla-notification-health', [self::class, 'render']);
    }

    public static function record(int $notificationId, string $event, string $role, int $userId, string $channel, string $status, string $provider = '', string $providerMessage = '', string $errorCode = '', string $errorMessage = '', array $meta = []): void {
        if (!in_array($event, self::EVENTS, true)) return;
        $health = self::health();
        $channel = sanitize_key($channel);
        $status = sanitize_key($status);
        $provider = sanitize_key($provider);
        $health['total']++;
        $health['last_at'] = time();
        $health['last_status'] = $status;
        $health['last_channel'] = $channel;
        $health['last_provider'] = $provider;
        if (in_array($status, ['failed', 'error'], true)) {
            $health['consecutive_failures']++;
            $health['last_failure_at'] = time();
        } else {
            $health['consecutive_failures'] = 0;
        }
        $health['healthy'] = $health['consecutive_failures'] === 0;
        update_option(self::OPTION, $health, false);
    }

    public static function health(): array {
        $defaults = [
            'total' => 0,
            'last_at' => 0,
            'last_status' => '',
            'last_channel' => '',
            'last_provider' => '',
            'consecutive_failures' => 0,
            'last_failure_at' => 0,
            'healthy' => true,
        ];
        $stored = get_option(self::OPTION, []);
        return wp_parse_args(is_array($stored) ? $stored : [], $defaults);
    }

    public static function render(): void {
        if (!current_user_can('manage_options')) return;
        $h = self::health();
        $label = $h['healthy'] ? 'Healthy' : 'Degraded';
        echo '<div class="wrap"><h1>Provider SLA Notification Health</h1>';
        echo '<p><strong>Status:</strong> '.esc_html($label).' &nbsp; <strong>Total:</strong> '.(int)$h['total'].' &nbsp; <strong>Consecutive failures:</strong> '.(int)$h['consecutive_failures'].'</p>';
        echo '<table class="widefat striped"><tbody>';
        $rows = [
            'Last event' => $h['last_at'] ? wp_date('Y-m-d H:i:s', (int)$h['last_at']) : '—',
            'Last status' => $h['last_status'] ?: '—',
            'Last channel' => $h['last_channel'] ?: '—',
            'Last provider' => $h['last_provider'] ?: '—',
            'Last failure' => $h['last_failure_at'] ? wp_date('Y-m-d H:i:s', (int)$h['last_failure_at']) : '—',
        ];
        foreach ($rows as $key => $value) echo '<tr><th>'.esc_html($key).'</th><td>'.esc_html((string)$value).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
