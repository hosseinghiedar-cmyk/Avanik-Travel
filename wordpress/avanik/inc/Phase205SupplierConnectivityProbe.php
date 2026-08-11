<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class Phase205SupplierConnectivityProbe {
    private const OPTION = 'avanik_phase_205_supplier_connectivity_probe';
    private const ENDPOINT_OPTION = 'avanik_supplier_api_url';
    private const CAPABILITY = 'manage_options';

    public static function register(): void {
        add_options_page(
            'Avanik Supplier Connectivity Probe',
            'Supplier Connectivity Probe',
            self::CAPABILITY,
            'avanik-phase-205-supplier-connectivity-probe',
            [self::class, 'render']
        );
    }

    public static function probe(): array {
        $endpoint = trim((string) get_option(self::ENDPOINT_OPTION, ''));
        $result = [
            'status' => 'blocked',
            'endpoint_configured' => $endpoint !== '',
            'http_status' => 0,
            'response_time_ms' => 0,
            'event' => 'supplier_connectivity_probe_completed',
            'reason' => 'supplier_endpoint_not_configured',
            'probed_at' => time(),
        ];

        if ($endpoint === '') {
            update_option(self::OPTION, $result, false);
            return $result;
        }

        if (!wp_http_validate_url($endpoint)) {
            $result['reason'] = 'supplier_endpoint_invalid';
            update_option(self::OPTION, $result, false);
            return $result;
        }

        $started = microtime(true);
        $response = wp_remote_get($endpoint, [
            'timeout' => 8,
            'redirection' => 2,
            'sslverify' => true,
            'headers' => [
                'Accept' => 'application/json,text/plain;q=0.8,*/*;q=0.5',
                'X-Avanik-Probe' => 'phase-205',
            ],
        ]);
        $result['response_time_ms'] = (int) round((microtime(true) - $started) * 1000);

        if (is_wp_error($response)) {
            $result['reason'] = 'supplier_connectivity_error';
            update_option(self::OPTION, $result, false);
            return $result;
        }

        $result['http_status'] = (int) wp_remote_retrieve_response_code($response);
        $result['status'] = ($result['http_status'] >= 200 && $result['http_status'] < 500) ? 'reachable' : 'failed';
        $result['reason'] = $result['status'] === 'reachable' ? 'supplier_endpoint_reachable' : 'supplier_endpoint_unreachable';
        update_option(self::OPTION, $result, false);
        return $result;
    }

    public static function render(): void {
        if (!current_user_can(self::CAPABILITY)) return;
        $r = self::probe();
        echo '<div class="wrap"><h1>Avanik Supplier Connectivity Probe</h1>';
        echo '<p>This phase performs a safe connectivity probe only. It does not search, book, ticket, pay, or execute a supplier transaction.</p>';
        echo '<table class="widefat striped"><tbody>';
        foreach ([
            'Status' => strtoupper((string) $r['status']),
            'Endpoint configured' => $r['endpoint_configured'] ? 'YES' : 'NO',
            'HTTP status' => (string) $r['http_status'],
            'Response time' => $r['response_time_ms'] . ' ms',
            'Event' => str_replace('_', ' ', $r['event']),
            'Reason' => str_replace('_', ' ', $r['reason']),
            'Probed at' => wp_date('Y-m-d H:i:s', (int) $r['probed_at']),
        ] as $key => $value) {
            echo '<tr><th>' . esc_html($key) . '</th><td>' . esc_html((string) $value) . '</td></tr>';
        }
        echo '</tbody></table></div>';
    }
}
