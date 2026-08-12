<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class Phase207SupplierSandboxProviderMapping {
    private const OPTION = 'avanik_phase_207_supplier_sandbox_provider_mapping';
    private const CAPABILITY = 'manage_options';

    public static function register(): void {
        add_options_page(
            'Avanik Supplier Sandbox Provider Mapping',
            'Supplier Sandbox Mapping',
            self::CAPABILITY,
            'avanik-phase-207-supplier-sandbox-provider-mapping',
            [self::class, 'render']
        );
    }

    public static function evaluate(): array {
        $contract = get_option('avanik_phase_206_supplier_api_contract_readiness', []);
        $contract = is_array($contract) ? $contract : [];

        $provider = (string) get_option('avanik_supplier_provider', '');
        $endpoint = (string) get_option('avanik_supplier_api_url', '');
        $sandbox = (string) get_option('avanik_supplier_sandbox_url', '');

        $contractReady = in_array(($contract['contract_status'] ?? ''), ['defined', 'ready_for_provider_mapping'], true);
        $providerConfigured = $provider !== '';
        $endpointConfigured = filter_var($endpoint, FILTER_VALIDATE_URL) !== false;
        $sandboxConfigured = filter_var($sandbox, FILTER_VALIDATE_URL) !== false;

        $mapping = [
            'search' => ['required' => true, 'mapped' => $contractReady],
            'availability' => ['required' => true, 'mapped' => $contractReady],
            'booking' => ['required' => true, 'mapped' => $contractReady],
            'ticket' => ['required' => true, 'mapped' => $contractReady],
            'cancel' => ['required' => true, 'mapped' => $contractReady],
        ];

        $ready = $contractReady && $providerConfigured && $endpointConfigured && $sandboxConfigured;

        $result = [
            'status' => $ready ? 'ready_for_sandbox_validation' : 'blocked',
            'contract_status' => (string) ($contract['contract_status'] ?? 'unknown'),
            'provider_configured' => $providerConfigured,
            'endpoint_configured' => $endpointConfigured,
            'sandbox_configured' => $sandboxConfigured,
            'provider' => $provider,
            'mapping' => $mapping,
            'sandbox_validation' => 'pending',
            'booking_execution_allowed' => false,
            'ticket_issuance_allowed' => false,
            'cancellation_execution_allowed' => false,
            'payment_execution_allowed' => false,
            'event' => 'supplier_sandbox_provider_mapping_evaluated',
            'reason' => $ready
                ? 'contract_and_provider_configuration_are_ready_for_sandbox_validation'
                : 'provider_mapping_requires_contract_provider_endpoint_and_sandbox_configuration',
            'evaluated_at' => time(),
        ];

        update_option(self::OPTION, $result, false);
        return $result;
    }

    public static function render(): void {
        if (!current_user_can(self::CAPABILITY)) {
            return;
        }

        $s = self::evaluate();
        echo '<div class="wrap"><h1>Avanik Supplier Sandbox Provider Mapping</h1>';
        echo '<p>This phase maps the provider-neutral Supplier contract to a configured sandbox without enabling production booking or ticket execution.</p>';
        echo '<table class="widefat striped"><tbody>';

        $rows = [
            'Status' => strtoupper(str_replace('_', ' ', $s['status'])),
            'Contract status' => strtoupper(str_replace('_', ' ', $s['contract_status'])),
            'Provider configured' => $s['provider_configured'] ? 'YES' : 'NO',
            'Endpoint configured' => $s['endpoint_configured'] ? 'YES' : 'NO',
            'Sandbox configured' => $s['sandbox_configured'] ? 'YES' : 'NO',
            'Provider' => $s['provider'] ?: 'NOT CONFIGURED',
            'Sandbox validation' => strtoupper(str_replace('_', ' ', $s['sandbox_validation'])),
            'Booking execution allowed' => $s['booking_execution_allowed'] ? 'YES' : 'NO',
            'Ticket issuance allowed' => $s['ticket_issuance_allowed'] ? 'YES' : 'NO',
            'Cancellation execution allowed' => $s['cancellation_execution_allowed'] ? 'YES' : 'NO',
            'Payment execution allowed' => $s['payment_execution_allowed'] ? 'YES' : 'NO',
            'Reason' => str_replace('_', ' ', $s['reason']),
            'Evaluated at' => wp_date('Y-m-d H:i:s', $s['evaluated_at']),
        ];

        foreach ($rows as $key => $value) {
            echo '<tr><th>' . esc_html($key) . '</th><td>' . esc_html((string) $value) . '</td></tr>';
        }

        echo '</tbody></table><h2>Contract Mapping</h2><table class="widefat striped"><thead><tr><th>Operation</th><th>Required</th><th>Mapped</th></tr></thead><tbody>';
        foreach ($s['mapping'] as $operation => $state) {
            echo '<tr><td>' . esc_html(ucfirst($operation)) . '</td><td>' . ($state['required'] ? 'YES' : 'NO') . '</td><td>' . ($state['mapped'] ? 'YES' : 'NO') . '</td></tr>';
        }
        echo '</tbody></table></div>';
    }
}
