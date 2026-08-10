<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskAlerts {
    private const OPTION = 'avanik_provider_health_sla_risk_alert_state';
    private const THRESHOLD = 90.0;

    public static function register(): void {
        add_action('avanik_provider_health_sla_risk_snapshot', [self::class, 'evaluate']);
        add_options_page('Provider SLA Risk Alerts', 'Provider SLA Risk Alerts', 'manage_options', 'avanik-provider-sla-risk-alerts', [self::class, 'render']);
    }

    public static function evaluate(): void {
        $assessment = NotificationProviderHealthSlaRisk::assess(30);
        $state = get_option(self::OPTION, []);
        if (!is_array($state)) $state = [];
        foreach (($assessment['providers'] ?? []) as $row) {
            $provider = (string) ($row['provider'] ?? '');
            if ($provider === '') continue;
            $score = isset($row['score']) ? (float) $row['score'] : null;
            $risk = strtolower((string) ($row['risk'] ?? 'unknown'));
            $previous = $state[$provider] ?? [];
            $previousRisk = strtolower((string) ($previous['risk'] ?? 'unknown'));
            $transition = $previousRisk !== $risk;
            $state[$provider] = ['risk' => $risk, 'score' => $score, 'updated_at' => time(), 'last_transition' => $transition ? time() : ($previous['last_transition'] ?? null), 'below_threshold' => $score !== null && $score < self::THRESHOLD];
        }
        update_option(self::OPTION, $state, false);
        do_action('avanik_provider_health_sla_risk_alert_evaluated', $state);
    }

    public static function state(): array {
        $state = get_option(self::OPTION, []);
        return is_array($state) ? $state : [];
    }

    public static function render(): void {
        if (!current_user_can('manage_options')) return;
        $state = self::state();
        echo '<div class="wrap"><h1>Provider SLA Risk Alerts</h1><p>Read-only alert state for risk transitions and the 90-point risk threshold.</p>';
        echo '<table class="widefat striped"><thead><tr><th>Provider</th><th>Risk</th><th>Score</th><th>Threshold</th><th>Last Transition</th><th>Status</th></tr></thead><tbody>';
        if (!$state) echo '<tr><td colspan="6">No risk evaluations yet.</td></tr>';
        foreach ($state as $provider => $row) {
            $score = $row['score'] ?? null;
            $status = !empty($row['below_threshold']) ? 'ALERT' : 'NORMAL';
            echo '<tr><td>' . esc_html($provider) . '</td><td>' . esc_html(strtoupper((string) ($row['risk'] ?? 'unknown'))) . '</td><td>' . esc_html($score === null ? '—' : number_format_i18n((float) $score, 1)) . '</td><td>' . esc_html(number_format_i18n(self::THRESHOLD, 1)) . '</td><td>' . esc_html(!empty($row['last_transition']) ? wp_date('Y-m-d H:i', (int) $row['last_transition']) : '—') . '</td><td>' . esc_html($status) . '</td></tr>';
        }
        echo '</tbody></table></div>';
    }
}
