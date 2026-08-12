<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaExecutiveSummary {
    public static function register(): void {
        add_options_page(
            'Provider Health SLA Executive Summary',
            'Provider Health SLA Summary',
            'manage_options',
            'avanik-provider-health-sla-summary',
            [self::class, 'render']
        );
    }

    public static function summary(int $days = 30): array {
        $days = in_array($days, [7, 30, 90, 365], true) ? $days : 30;
        $now = time();
        $from = $now - ($days * DAY_IN_SECONDS);
        $incidents = NotificationProviderHealthIncident::recent(200);
        $providers = NotificationProviderSettings::get();
        if (!$providers) {
            $providers = ['default' => ['name' => 'Default / Core', 'enabled' => 1]];
        }

        $byProvider = [];
        $totalChecks = 0;
        $totalBreaches = 0;
        $open = 0;
        $resolved = 0;
        $downtime = 0;

        foreach ($providers as $providerId => $provider) {
            $policy = NotificationProviderHealthSla::policy((string)$providerId);
            $row = [
                'provider' => (string)$providerId,
                'name' => (string)($provider['name'] ?? $providerId),
                'checks' => 0,
                'breaches' => 0,
                'compliance' => null,
                'open' => 0,
                'resolved' => 0,
                'downtime' => 0,
            ];

            foreach ($incidents as $incident) {
                if ((string)($incident['provider'] ?? '') !== (string)$providerId) continue;
                $opened = (int)($incident['opened_at'] ?? 0);
                if ($opened < $from || $opened > $now) continue;

                if (($incident['status'] ?? '') === 'open') $row['open']++;
                else $row['resolved']++;

                $resolvedAt = (int)($incident['resolved_at'] ?? 0);
                $end = $resolvedAt > 0 ? $resolvedAt : $now;
                $row['downtime'] += max(0, $end - $opened);

                $ack = (int)($incident['acknowledged_at'] ?? 0);
                $ackSeconds = $ack > 0 ? max(0, $ack - $opened) : max(0, $now - $opened);
                $resolutionSeconds = max(0, $end - $opened);

                foreach ([
                    'acknowledgement' => $ackSeconds,
                    'resolution' => $resolutionSeconds,
                    'downtime' => $resolutionSeconds,
                ] as $type => $actual) {
                    $threshold = (int)($policy[$type . '_seconds'] ?? 0);
                    if ($threshold <= 0) continue;
                    $row['checks']++;
                    if ($actual > $threshold) $row['breaches']++;
                }
            }

            $row['compliance'] = $row['checks'] > 0
                ? round((($row['checks'] - $row['breaches']) / $row['checks']) * 100, 1)
                : null;

            $totalChecks += $row['checks'];
            $totalBreaches += $row['breaches'];
            $open += $row['open'];
            $resolved += $row['resolved'];
            $downtime += $row['downtime'];
            $byProvider[] = $row;
        }

        usort($byProvider, static function(array $a, array $b): int {
            $av = $a['compliance'] === null ? 101 : (float)$a['compliance'];
            $bv = $b['compliance'] === null ? 101 : (float)$b['compliance'];
            return $av <=> $bv;
        });

        return [
            'days' => $days,
            'from' => $from,
            'to' => $now,
            'checks' => $totalChecks,
            'breaches' => $totalBreaches,
            'compliance' => $totalChecks > 0 ? round((($totalChecks - $totalBreaches) / $totalChecks) * 100, 1) : null,
            'open' => $open,
            'resolved' => $resolved,
            'downtime' => $downtime,
            'providers' => $byProvider,
        ];
    }

    private static function duration(int $seconds): string {
        $seconds = max(0, $seconds);
        $days = intdiv($seconds, DAY_IN_SECONDS);
        $seconds %= DAY_IN_SECONDS;
        $hours = intdiv($seconds, HOUR_IN_SECONDS);
        $seconds %= HOUR_IN_SECONDS;
        $minutes = intdiv($seconds, MINUTE_IN_SECONDS);
        if ($days > 0) return sprintf('%dd %dh', $days, $hours);
        if ($hours > 0) return sprintf('%dh %dm', $hours, $minutes);
        return sprintf('%dm', $minutes);
    }

    public static function render(): void {
        if (!current_user_can('manage_options')) return;
        $days = isset($_GET['days']) ? absint($_GET['days']) : 30;
        $data = self::summary($days);

        echo '<div class="wrap"><h1>Provider Health SLA Executive Summary</h1>';
        echo '<p>Executive view of SLA compliance, breaches, open incidents and downtime.</p>';
        echo '<form method="get"><input type="hidden" name="page" value="avanik-provider-health-sla-summary">';
        echo '<label>Period: <select name="days">';
        foreach ([7, 30, 90, 365] as $option) {
            printf('<option value="%d" %s>%d days</option>', $option, selected($data['days'], $option, false), $option);
        }
        echo '</select></label> <button class="button button-primary">Apply</button></form>';

        echo '<div style="display:grid;grid-template-columns:repeat(4,minmax(150px,1fr));gap:12px;margin:16px 0">';
        $cards = [
            'SLA Compliance' => $data['compliance'] === null ? '—' : number_format_i18n((float)$data['compliance'], 1) . '%',
            'SLA Breaches' => (string)$data['breaches'],
            'Open Incidents' => (string)$data['open'],
            'Total Downtime' => self::duration((int)$data['downtime']),
        ];
        foreach ($cards as $label => $value) {
            echo '<div style="background:#fff;border:1px solid #ccd0d4;padding:16px">';
            echo '<strong>' . esc_html($label) . '</strong><div style="font-size:24px;margin-top:8px">' . esc_html($value) . '</div></div>';
        }
        echo '</div>';

        echo '<h2>Provider Ranking</h2>';
        echo '<table class="widefat striped"><thead><tr><th>Provider</th><th>SLA Checks</th><th>Breaches</th><th>Compliance</th><th>Open</th><th>Resolved</th><th>Downtime</th></tr></thead><tbody>';
        foreach ($data['providers'] as $row) {
            $compliance = $row['compliance'] === null ? '—' : number_format_i18n((float)$row['compliance'], 1) . '%';
            printf(
                '<tr><td>%s</td><td>%d</td><td>%d</td><td>%s</td><td>%d</td><td>%d</td><td>%s</td></tr>',
                esc_html($row['name']),
                (int)$row['checks'],
                (int)$row['breaches'],
                esc_html($compliance),
                (int)$row['open'],
                (int)$row['resolved'],
                esc_html(self::duration((int)$row['downtime']))
            );
        }
        echo '</tbody></table>';
        echo '<p style="margin-top:12px">Providers with no applicable SLA checks are shown as — and are not treated as 100% compliant.</p>';
        echo '</div>';
    }
}
