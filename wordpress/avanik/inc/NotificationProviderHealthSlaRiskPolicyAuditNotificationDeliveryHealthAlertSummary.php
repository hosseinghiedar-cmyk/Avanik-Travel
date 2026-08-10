<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertSummary {
    private const OPTION = 'avanik_provider_sla_notification_health_alert_log';

    public static function register(): void {
        add_options_page('Provider SLA Health Alert Summary', 'Provider SLA Health Alert Summary', 'manage_options', 'avanik-provider-sla-health-alert-summary', [self::class, 'render']);
    }

    public static function summarize(): array {
        $log = get_option(self::OPTION, []);
        $log = is_array($log) ? $log : [];
        $summary = [
            'total_events' => count($log),
            'alerts' => 0,
            'recoveries' => 0,
            'open_alerts' => 0,
            'latest_alert_at' => 0,
            'latest_recovery_at' => 0,
            'last_failure_count' => 0,
        ];
        foreach ($log as $row) {
            $type = sanitize_key((string)($row['type'] ?? ''));
            $at = (int)($row['at'] ?? 0);
            if ($type === 'alert') {
                $summary['alerts']++;
                $summary['latest_alert_at'] = max($summary['latest_alert_at'], $at);
                $summary['last_failure_count'] = max($summary['last_failure_count'], (int)($row['failures'] ?? 0));
            } elseif ($type === 'recovery') {
                $summary['recoveries']++;
                $summary['latest_recovery_at'] = max($summary['latest_recovery_at'], $at);
            }
        }
        $summary['open_alerts'] = $summary['latest_alert_at'] > $summary['latest_recovery_at'] ? 1 : 0;
        return $summary;
    }

    public static function render(): void {
        if (!current_user_can('manage_options')) return;
        $s = self::summarize();
        echo '<div class="wrap"><h1>Provider SLA Health Alert Summary</h1><div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px;margin:20px 0;">';
        $cards = [
            'Total Events' => $s['total_events'],
            'Alerts' => $s['alerts'],
            'Recoveries' => $s['recoveries'],
            'Open Alerts' => $s['open_alerts'],
            'Last Failure Count' => $s['last_failure_count'],
        ];
        foreach ($cards as $label => $value) echo '<div style="background:#fff;border:1px solid #ccd0d4;padding:16px;"><strong>'.esc_html($label).'</strong><div style="font-size:26px;margin-top:8px;">'.(int)$value.'</div></div>';
        echo '</div><table class="widefat striped"><tbody>';
        $rows = [
            'Latest alert' => $s['latest_alert_at'] ? wp_date('Y-m-d H:i:s', $s['latest_alert_at']) : '—',
            'Latest recovery' => $s['latest_recovery_at'] ? wp_date('Y-m-d H:i:s', $s['latest_recovery_at']) : '—',
            'Current state' => $s['open_alerts'] ? 'Alert Open' : 'Recovered / Normal',
        ];
        foreach ($rows as $key => $value) echo '<tr><th>'.esc_html($key).'</th><td>'.esc_html((string)$value).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
