<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaTrend {
    public static function register(): void {
        add_options_page(
            'Provider Health SLA Trend',
            'Provider Health SLA Trend',
            'manage_options',
            'avanik-provider-health-sla-trend',
            [self::class, 'render']
        );
    }

    public static function buckets(int $days = 30): array {
        $days = max(7, min(365, $days));
        $incidents = NotificationProviderHealthIncident::recent(500);
        $providers = NotificationProviderSettings::get();
        if (!$providers) {
            $providers = ['default' => ['name' => 'Default / Core', 'enabled' => 1]];
        }

        $bucketCount = $days <= 31 ? $days : (int)ceil($days / 7);
        $bucketSize = $days <= 31 ? DAY_IN_SECONDS : 7 * DAY_IN_SECONDS;
        $end = time();
        $start = $end - ($bucketCount * $bucketSize);
        $rows = [];

        for ($i = 0; $i < $bucketCount; $i++) {
            $from = $start + ($i * $bucketSize);
            $to = min($end, $from + $bucketSize);
            $checks = 0;
            $breaches = 0;
            $incidentsCount = 0;

            foreach ($providers as $providerId => $provider) {
                $policy = NotificationProviderHealthSla::policy((string)$providerId);
                foreach ($incidents as $incident) {
                    if ((string)($incident['provider'] ?? '') !== (string)$providerId) continue;
                    $opened = (int)($incident['opened_at'] ?? 0);
                    if ($opened < $from || $opened >= $to) continue;
                    $incidentsCount++;
                    $now = time();
                    $ack = (int)($incident['acknowledged_at'] ?? 0);
                    $resolved = (int)($incident['resolved_at'] ?? 0);
                    $ackSeconds = $ack ? max(0, $ack - $opened) : max(0, $now - $opened);
                    $resolutionSeconds = $resolved ? max(0, $resolved - $opened) : max(0, $now - $opened);
                    foreach ([
                        'acknowledgement' => $ackSeconds,
                        'resolution' => $resolutionSeconds,
                        'downtime' => $resolutionSeconds,
                    ] as $type => $actual) {
                        $threshold = (int)($policy[$type . '_seconds'] ?? 0);
                        if ($threshold <= 0) continue;
                        $checks++;
                        if ($actual > $threshold) $breaches++;
                    }
                }
            }

            $rows[] = [
                'from' => $from,
                'to' => $to,
                'incidents' => $incidentsCount,
                'checks' => $checks,
                'breaches' => $breaches,
                'compliance' => $checks > 0 ? round((($checks - $breaches) / $checks) * 100, 1) : null,
            ];
        }
        return $rows;
    }

    public static function render(): void {
        if (!current_user_can('manage_options')) return;
        $days = isset($_GET['days']) ? absint($_GET['days']) : 30;
        $days = max(7, min(365, $days));
        $rows = self::buckets($days);

        echo '<div class="wrap"><h1>Provider Health SLA Trend</h1>';
        echo '<p>Trend of incidents, SLA checks, breaches and compliance for the selected period.</p>';
        echo '<form method="get"><input type="hidden" name="page" value="avanik-provider-health-sla-trend">';
        echo '<label>Period: <select name="days">';
        foreach ([7, 30, 90, 365] as $option) {
            printf('<option value="%d" %s>%d days</option>', $option, selected($days, $option, false), $option);
        }
        echo '</select></label> <button class="button button-primary">Apply</button></form>';

        echo '<table class="widefat striped" style="margin-top:16px"><thead><tr><th>Period</th><th>Incidents</th><th>SLA Checks</th><th>Breaches</th><th>Compliance</th></tr></thead><tbody>';
        foreach ($rows as $row) {
            $label = esc_html(wp_date(get_option('date_format'), $row['from']));
            $compliance = $row['compliance'] === null ? '—' : number_format_i18n((float)$row['compliance'], 1) . '%';
            printf('<tr><td>%s</td><td>%d</td><td>%d</td><td>%d</td><td>%s</td></tr>', $label, (int)$row['incidents'], (int)$row['checks'], (int)$row['breaches'], esc_html($compliance));
        }
        echo '</tbody></table></div>';
    }
}
