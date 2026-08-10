<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaCompliance {
    public static function register(): void {
        add_action('admin_menu', [self::class, 'menu']);
    }

    public static function menu(): void {
        add_options_page(
            'Provider Health SLA Compliance',
            'Provider Health SLA Compliance',
            'manage_options',
            'avanik-provider-health-sla-compliance',
            [self::class, 'render']
        );
    }

    public static function rows(int $days = 30): array {
        $days = max(1, min(365, $days));
        $cutoff = time() - ($days * DAY_IN_SECONDS);
        $providers = NotificationProviderSettings::get();
        if (!$providers) {
            $providers = ['default' => ['name' => 'Default / Core', 'adapter' => 'core', 'enabled' => 1]];
        }
        $incidents = NotificationProviderHealthIncident::recent(500);
        $rows = [];

        foreach ($providers as $id => $provider) {
            $id = (string) $id;
            $policy = NotificationProviderHealthSla::policy($id);
            $total = 0; $checks = 0; $breaches = 0;
            $types = ['acknowledgement' => 0, 'resolution' => 0, 'downtime' => 0];

            foreach ($incidents as $incident) {
                if ((string)($incident['provider'] ?? '') !== $id) continue;
                $opened = (int)($incident['opened_at'] ?? 0);
                if (!$opened || $opened < $cutoff) continue;
                $total++;
                $now = time();
                $ack = (int)($incident['acknowledged_at'] ?? 0);
                $resolved = (int)($incident['resolved_at'] ?? 0);
                $ackSeconds = $ack ? max(0, $ack - $opened) : max(0, $now - $opened);
                $resolutionSeconds = $resolved ? max(0, $resolved - $opened) : max(0, $now - $opened);

                foreach (['acknowledgement' => $ackSeconds, 'resolution' => $resolutionSeconds, 'downtime' => $resolutionSeconds] as $type => $actual) {
                    $key = $type . '_seconds';
                    $threshold = (int)($policy[$key] ?? 0);
                    if ($threshold <= 0) continue;
                    $checks++;
                    if ($actual > $threshold) {
                        $breaches++;
                        $types[$type]++;
                    }
                }
            }

            $compliance = $checks > 0 ? round((($checks - $breaches) / $checks) * 100, 1) : null;
            $rows[] = [
                'provider' => $id,
                'name' => (string)($provider['name'] ?? $id),
                'enabled' => !empty($provider['enabled']),
                'incident_count' => $total,
                'checks' => $checks,
                'breaches' => $breaches,
                'compliance' => $compliance,
                'breaches_by_type' => $types,
                'policy' => $policy,
            ];
        }
        return $rows;
    }

    public static function render(): void {
        if (!current_user_can('manage_options')) return;
        $days = isset($_GET['days']) ? absint($_GET['days']) : 30;
        $days = max(1, min(365, $days));
        $rows = self::rows($days);
        echo '<div class="wrap"><h1>Provider Health SLA Compliance</h1>';
        echo '<p>Historical SLA compliance calculated from incidents opened in the selected period.</p>';
        echo '<form method="get"><input type="hidden" name="page" value="avanik-provider-health-sla-compliance">';
        echo '<label>Period: <select name="days">';
        foreach ([7, 30, 90, 365] as $option) {
            printf('<option value="%d" %s>%d days</option>', $option, selected($days, $option, false), $option);
        }
        echo '</select></label> <button class="button button-primary">Apply</button></form>';
        echo '<table class="widefat striped" style="margin-top:16px"><thead><tr><th>Provider</th><th>Incidents</th><th>SLA Checks</th><th>Breaches</th><th>Compliance</th><th>Ack</th><th>Resolution</th><th>Downtime</th></tr></thead><tbody>';
        foreach ($rows as $row) {
            $compliance = $row['compliance'] === null ? '—' : number_format_i18n((float)$row['compliance'], 1) . '%';
            printf('<tr><td><strong>%s</strong></td><td>%d</td><td>%d</td><td>%d</td><td>%s</td><td>%d</td><td>%d</td><td>%d</td></tr>', esc_html($row['name']), (int)$row['incident_count'], (int)$row['checks'], (int)$row['breaches'], esc_html($compliance), (int)$row['breaches_by_type']['acknowledgement'], (int)$row['breaches_by_type']['resolution'], (int)$row['breaches_by_type']['downtime']);
        }
        if (!$rows) echo '<tr><td colspan="8">No provider data available.</td></tr>';
        echo '</tbody></table></div>';
    }
}
