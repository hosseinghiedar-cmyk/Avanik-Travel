<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRisk {
    public static function register(): void {
        add_options_page(
            'Provider Health SLA Risk',
            'Provider Health SLA Risk',
            'manage_options',
            'avanik-provider-health-sla-risk',
            [self::class, 'render']
        );
    }

    public static function assess(int $days = 30): array {
        $days = in_array($days, [7, 30, 90, 365], true) ? $days : 30;
        $now = time();
        $from = $now - ($days * DAY_IN_SECONDS);
        $incidents = NotificationProviderHealthIncident::recent(500);
        $providers = NotificationProviderSettings::get();
        if (!$providers) {
            $providers = ['default' => ['name' => 'Default / Core', 'enabled' => 1]];
        }

        $rows = [];
        foreach ($providers as $providerId => $provider) {
            $policy = NotificationProviderHealthSla::policy((string) $providerId);
            $checks = 0;
            $breaches = 0;
            $open = 0;
            $downtime = 0;

            foreach ($incidents as $incident) {
                if ((string) ($incident['provider'] ?? '') !== (string) $providerId) continue;
                $opened = (int) ($incident['opened_at'] ?? 0);
                if ($opened < $from || $opened > $now) continue;

                $isOpen = (($incident['status'] ?? '') === 'open');
                if ($isOpen) $open++;

                $resolvedAt = (int) ($incident['resolved_at'] ?? 0);
                $end = $resolvedAt > 0 ? $resolvedAt : $now;
                $downtime += max(0, $end - $opened);

                $ack = (int) ($incident['acknowledged_at'] ?? 0);
                $ackSeconds = $ack > 0 ? max(0, $ack - $opened) : max(0, $now - $opened);
                $resolutionSeconds = max(0, $end - $opened);

                foreach ([
                    'acknowledgement' => $ackSeconds,
                    'resolution' => $resolutionSeconds,
                    'downtime' => $resolutionSeconds,
                ] as $type => $actual) {
                    $threshold = (int) ($policy[$type . '_seconds'] ?? 0);
                    if ($threshold <= 0) continue;
                    $checks++;
                    if ($actual > $threshold) $breaches++;
                }
            }

            $compliance = $checks > 0 ? round((($checks - $breaches) / $checks) * 100, 1) : null;
            $penalty = min(40, ($open * 10) + ($breaches * 5));
            $score = $compliance === null ? null : max(0, round($compliance - $penalty, 1));
            $risk = self::risk($score);

            $rows[] = [
                'provider' => (string) $providerId,
                'name' => (string) ($provider['name'] ?? $providerId),
                'checks' => $checks,
                'breaches' => $breaches,
                'open' => $open,
                'downtime' => $downtime,
                'compliance' => $compliance,
                'score' => $score,
                'risk' => $risk,
            ];
        }

        usort($rows, static function (array $a, array $b): int {
            $av = $a['score'] === null ? -1 : (float) $a['score'];
            $bv = $b['score'] === null ? -1 : (float) $b['score'];
            return $av <=> $bv;
        });

        return ['days' => $days, 'from' => $from, 'to' => $now, 'providers' => $rows];
    }

    private static function risk(?float $score): string {
        if ($score === null) return 'unknown';
        if ($score < 90) return 'critical';
        if ($score < 95) return 'high';
        if ($score < 98) return 'medium';
        return 'low';
    }

    private static function duration(int $seconds): string {
        $seconds = max(0, $seconds);
        $hours = intdiv($seconds, HOUR_IN_SECONDS);
        $seconds %= HOUR_IN_SECONDS;
        $minutes = intdiv($seconds, MINUTE_IN_SECONDS);
        if ($hours > 0) return sprintf('%dh %dm', $hours, $minutes);
        return sprintf('%dm', $minutes);
    }

    public static function render(): void {
        if (!current_user_can('manage_options')) return;
        $days = isset($_GET['days']) ? absint($_GET['days']) : 30;
        $data = self::assess($days);

        echo '<div class="wrap"><h1>Provider Health SLA Risk</h1>';
        echo '<p>Risk classification derived from SLA compliance, active incidents and breach volume. This view is read-only.</p>';
        echo '<form method="get"><input type="hidden" name="page" value="avanik-provider-health-sla-risk">';
        echo '<label>Period: <select name="days">';
        foreach ([7, 30, 90, 365] as $option) {
            printf('<option value="%d" %s>%d days</option>', $option, selected($data['days'], $option, false), $option);
        }
        echo '</select></label> <button class="button button-primary">Apply</button></form>';

        echo '<table class="widefat striped" style="margin-top:16px"><thead><tr><th>Provider</th><th>Compliance</th><th>Open</th><th>Breaches</th><th>Risk Score</th><th>Risk</th><th>Downtime</th></tr></thead><tbody>';
        foreach ($data['providers'] as $row) {
            $compliance = $row['compliance'] === null ? '—' : number_format_i18n((float) $row['compliance'], 1) . '%';
            $score = $row['score'] === null ? '—' : number_format_i18n((float) $row['score'], 1);
            printf(
                '<tr><td>%s</td><td>%s</td><td>%d</td><td>%d</td><td>%s</td><td><strong>%s</strong></td><td>%s</td></tr>',
                esc_html($row['name']), esc_html($compliance), (int) $row['open'], (int) $row['breaches'],
                esc_html($score), esc_html(strtoupper($row['risk'])), esc_html(self::duration((int) $row['downtime']))
            );
        }
        echo '</tbody></table>';
        echo '<p style="margin-top:12px">Risk score starts from SLA compliance, subtracts 10 points per open incident and 5 points per SLA breach, capped at a 40-point penalty. Classification: low ≥98, medium ≥95, high ≥90, critical &lt;90.</p>';
        echo '</div>';
    }
}
