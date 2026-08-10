<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertLog {
    private const OPTION = 'avanik_provider_sla_notification_health_alert_log';
    private const MAX = 100;

    public static function register(): void {
        add_action('avanik_provider_sla_notification_health_alert', [self::class, 'record_alert'], 10, 4);
        add_action('avanik_provider_sla_notification_health_recovered', [self::class, 'record_recovery'], 10, 2);
        add_options_page('Provider SLA Health Alert Log', 'Provider SLA Health Alert Log', 'manage_options', 'avanik-provider-sla-health-alert-log', [self::class, 'render']);
    }

    public static function record_alert(int $failures, string $provider = '', string $channel = '', string $errorCode = ''): void {
        self::append([
            'type' => 'alert',
            'at' => time(),
            'failures' => $failures,
            'provider' => sanitize_key($provider),
            'channel' => sanitize_key($channel),
            'error_code' => sanitize_key($errorCode),
        ]);
    }

    public static function record_recovery(string $provider = '', string $channel = ''): void {
        self::append([
            'type' => 'recovery',
            'at' => time(),
            'failures' => 0,
            'provider' => sanitize_key($provider),
            'channel' => sanitize_key($channel),
            'error_code' => '',
        ]);
    }

    private static function append(array $entry): void {
        $log = get_option(self::OPTION, []);
        $log = is_array($log) ? $log : [];
        array_unshift($log, $entry);
        update_option(self::OPTION, array_slice($log, 0, self::MAX), false);
    }

    public static function render(): void {
        if (!current_user_can('manage_options')) return;
        $log = get_option(self::OPTION, []);
        $log = is_array($log) ? $log : [];
        echo '<div class="wrap"><h1>Provider SLA Health Alert Log</h1><table class="widefat striped"><thead><tr><th>Time</th><th>Type</th><th>Failures</th><th>Provider</th><th>Channel</th><th>Error Code</th></tr></thead><tbody>';
        foreach ($log as $row) {
            echo '<tr><td>'.esc_html($row['at'] ? wp_date('Y-m-d H:i:s', (int)$row['at']) : '—').'</td><td>'.esc_html($row['type'] ?? '').'</td><td>'.(int)($row['failures'] ?? 0).'</td><td>'.esc_html($row['provider'] ?? '').'</td><td>'.esc_html($row['channel'] ?? '').'</td><td>'.esc_html($row['error_code'] ?? '').'</td></tr>';
        }
        if (!$log) echo '<tr><td colspan="6">No alert events recorded.</td></tr>';
        echo '</tbody></table></div>';
    }
}
