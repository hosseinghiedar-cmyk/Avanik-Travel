<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskHistory {
    private const OPTION = 'avanik_provider_health_sla_risk_history';
    private const MAX_POINTS = 365;

    public static function register(): void {
        add_action('avanik_provider_health_sla_check', [self::class, 'capture']);
        if (!wp_next_scheduled('avanik_provider_health_sla_risk_snapshot')) {
            wp_schedule_event(time() + 300, 'hourly', 'avanik_provider_health_sla_risk_snapshot');
        }
        add_action('avanik_provider_health_sla_risk_snapshot', [self::class, 'capture']);
        add_options_page('Provider Health SLA Risk History', 'Provider SLA Risk History', 'manage_options', 'avanik-provider-health-sla-risk-history', [self::class, 'render']);
    }

    public static function capture(): void {
        $data = NotificationProviderHealthSlaRisk::assess(30);
        $history = get_option(self::OPTION, []);
        if (!is_array($history)) $history = [];
        $history[] = ['timestamp' => time(), 'providers' => $data['providers'] ?? []];
        if (count($history) > self::MAX_POINTS) $history = array_slice($history, -self::MAX_POINTS);
        update_option(self::OPTION, $history, false);
    }

    public static function history(): array {
        $history = get_option(self::OPTION, []);
        return is_array($history) ? $history : [];
    }

    public static function render(): void {
        if (!current_user_can('manage_options')) return;
        $history = self::history();
        $providers = [];
        foreach ($history as $point) {
            foreach (($point['providers'] ?? []) as $row) {
                $id = (string) ($row['provider'] ?? '');
                if ($id === '') continue;
                $providers[$id] = (string) ($row['name'] ?? $id);
            }
        }
        echo '<div class="wrap"><h1>Provider Health SLA Risk History</h1>';
        echo '<p>Hourly snapshots of Provider SLA risk. This is a read-only operational history.</p>';
        if (!$history) { echo '<p>No snapshots captured yet.</p></div>'; return; }
        echo '<table class="widefat striped"><thead><tr><th>Time</th>';
        foreach ($providers as $name) echo '<th>' . esc_html($name) . '</th>';
        echo '</tr></thead><tbody>';
        foreach (array_slice($history, -48) as $point) {
            echo '<tr><td>' . esc_html(wp_date('Y-m-d H:i', (int) ($point['timestamp'] ?? 0))) . '</td>';
            $map = [];
            foreach (($point['providers'] ?? []) as $row) $map[(string) ($row['provider'] ?? '')] = $row;
            foreach ($providers as $id => $_name) {
                $risk = (string) ($map[$id]['risk'] ?? 'unknown');
                $score = $map[$id]['score'] ?? null;
                $value = $score === null ? '—' : number_format_i18n((float) $score, 1) . ' / ' . strtoupper($risk);
                echo '<td>' . esc_html($value) . '</td>';
            }
            echo '</tr>';
        }
        echo '</tbody></table></div>';
    }
}
