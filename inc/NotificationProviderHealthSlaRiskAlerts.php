<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskAlerts {
    private const OPTION = 'avanik_provider_health_sla_risk_alert_state';
    private const THRESHOLD = 90.0;
    private const EVENT = 'provider_sla_risk_alert';

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
            $previousBelow = !empty($previous['below_threshold']);
            $belowThreshold = $score !== null && $score < self::THRESHOLD;
            $riskTransition = $previousRisk !== 'unknown' && $previousRisk !== $risk;
            $thresholdTransition = $previousRisk !== 'unknown' && $previousBelow !== $belowThreshold;
            $shouldNotify = $riskTransition || $thresholdTransition;
            $state[$provider] = [
                'risk' => $risk,
                'score' => $score,
                'updated_at' => time(),
                'last_transition' => $shouldNotify ? time() : ($previous['last_transition'] ?? null),
                'below_threshold' => $belowThreshold,
                'last_alerted_at' => $shouldNotify && self::policy_allows($risk, $previous) ? time() : ($previous['last_alerted_at'] ?? null),
            ];
            if ($shouldNotify) self::queue_alert($row, $previous, $riskTransition, $thresholdTransition);
        }
        update_option(self::OPTION, $state, false);
        do_action('avanik_provider_health_sla_risk_alert_evaluated', $state);
    }

    private static function policy_allows(string $risk, array $previous): bool {
        if (!NotificationProviderHealthSlaRiskNotificationPolicy::allows($risk)) return false;
        $cooldown = NotificationProviderHealthSlaRiskNotificationPolicy::cooldown_minutes();
        if ($cooldown > 0 && !empty($previous['last_alerted_at']) && (time() - (int) $previous['last_alerted_at']) < ($cooldown * 60)) return false;
        return true;
    }

    private static function queue_alert(array $row, array $previous, bool $riskTransition, bool $thresholdTransition): void {
        if (!class_exists(NotificationCenter::class)) return;
        $risk = strtolower((string) ($row['risk'] ?? 'unknown'));
        if (!self::policy_allows($risk, $previous)) return;
        $payload = [
            'provider' => (string) ($row['provider'] ?? ''),
            'provider_name' => (string) ($row['name'] ?? ($row['provider'] ?? '')),
            'risk' => $risk,
            'previous_risk' => strtolower((string) ($previous['risk'] ?? 'unknown')),
            'score' => $row['score'] ?? null,
            'previous_score' => $previous['score'] ?? null,
            'threshold' => self::THRESHOLD,
            'below_threshold' => isset($row['score']) && (float) $row['score'] < self::THRESHOLD,
            'risk_transition' => $riskTransition,
            'threshold_transition' => $thresholdTransition,
            'escalation_role' => NotificationProviderHealthSlaRiskNotificationPolicy::role($risk),
            'evaluated_at' => current_time('mysql'),
        ];
        NotificationCenter::enqueue(self::EVENT, $payload);
    }

    public static function state(): array {
        $state = get_option(self::OPTION, []);
        return is_array($state) ? $state : [];
    }

    public static function render(): void {
        if (!current_user_can('manage_options')) return;
        $state = self::state();
        echo '<div class="wrap"><h1>Provider SLA Risk Alerts</h1><p>Read-only alert state. Notifications are queued according to the Provider SLA Risk Notification Policy.</p>';
        echo '<table class="widefat striped"><thead><tr><th>Provider</th><th>Risk</th><th>Score</th><th>Threshold</th><th>Last Transition</th><th>Last Alert</th><th>Status</th></tr></thead><tbody>';
        if (!$state) echo '<tr><td colspan="7">No risk evaluations yet.</td></tr>';
        foreach ($state as $provider => $row) {
            $score = $row['score'] ?? null;
            $status = !empty($row['below_threshold']) ? 'ALERT' : 'NORMAL';
            echo '<tr><td>' . esc_html($provider) . '</td><td>' . esc_html(strtoupper((string) ($row['risk'] ?? 'unknown'))) . '</td><td>' . esc_html($score === null ? '—' : number_format_i18n((float) $score, 1)) . '</td><td>' . esc_html(number_format_i18n(self::THRESHOLD, 1)) . '</td><td>' . esc_html(!empty($row['last_transition']) ? wp_date('Y-m-d H:i', (int) $row['last_transition']) : '—') . '</td><td>' . esc_html(!empty($row['last_alerted_at']) ? wp_date('Y-m-d H:i', (int) $row['last_alerted_at']) : '—') . '</td><td>' . esc_html($status) . '</td></tr>';
        }
        echo '</tbody></table></div>';
    }
}
