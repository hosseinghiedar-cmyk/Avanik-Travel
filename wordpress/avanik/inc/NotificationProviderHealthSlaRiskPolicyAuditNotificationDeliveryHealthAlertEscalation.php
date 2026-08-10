<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalation {
    private const OPTION = 'avanik_provider_sla_notification_health_alert_escalation';
    private const DEFAULT_THRESHOLD = 5;
    private const COOLDOWN = 3600;

    public static function register(): void {
        add_action('avanik_provider_sla_notification_health_alert', [self::class, 'on_alert'], 20, 4);
        add_action('avanik_provider_sla_notification_health_recovered', [self::class, 'on_recovery'], 20, 2);
        add_options_page('Provider SLA Health Escalation', 'Provider SLA Health Escalation', 'manage_options', 'avanik-provider-sla-health-escalation', [self::class, 'render']);
    }

    public static function on_alert(int $failures, string $provider = '', string $channel = '', string $errorCode = ''): void {
        $state = self::state();
        if ($failures < $state['threshold']) return;
        $now = time();
        if ($state['escalated_at'] && ($now - $state['escalated_at']) < self::COOLDOWN) return;
        $state['escalated_at'] = $now;
        $state['escalation_count']++;
        $state['last_failures'] = $failures;
        $state['last_provider'] = sanitize_key($provider);
        $state['last_channel'] = sanitize_key($channel);
        $state['last_error_code'] = sanitize_key($errorCode);
        update_option(self::OPTION, $state, false);
        do_action('avanik_provider_sla_notification_health_escalated', $failures, $provider, $channel, $errorCode);
    }

    public static function on_recovery(string $provider = '', string $channel = ''): void {
        $state = self::state();
        $state['escalated_at'] = 0;
        $state['last_recovery_at'] = time();
        update_option(self::OPTION, $state, false);
    }

    public static function state(): array {
        $defaults = [
            'threshold' => self::DEFAULT_THRESHOLD,
            'escalated_at' => 0,
            'last_recovery_at' => 0,
            'escalation_count' => 0,
            'last_failures' => 0,
            'last_provider' => '',
            'last_channel' => '',
            'last_error_code' => '',
        ];
        $stored = get_option(self::OPTION, []);
        return wp_parse_args(is_array($stored) ? $stored : [], $defaults);
    }

    public static function render(): void {
        if (!current_user_can('manage_options')) return;
        $s = self::state();
        echo '<div class="wrap"><h1>Provider SLA Health Escalation</h1><table class="widefat striped"><tbody>';
        $rows = [
            'Escalation threshold' => (int)$s['threshold'].' consecutive failures',
            'Escalated now' => $s['escalated_at'] ? 'Yes' : 'No',
            'Escalation count' => (int)$s['escalation_count'],
            'Last failures' => (int)$s['last_failures'],
            'Last provider' => $s['last_provider'] ?: '—',
            'Last channel' => $s['last_channel'] ?: '—',
            'Last error code' => $s['last_error_code'] ?: '—',
            'Last recovery' => $s['last_recovery_at'] ? wp_date('Y-m-d H:i:s', (int)$s['last_recovery_at']) : '—',
        ];
        foreach ($rows as $key => $value) echo '<tr><th>'.esc_html($key).'</th><td>'.esc_html((string)$value).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
