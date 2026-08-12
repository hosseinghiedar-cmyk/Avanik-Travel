<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class Phase206SupplierApiContractReadiness {
    private const OPTION = 'avanik_phase_206_supplier_api_contract_readiness';
    private const CAPABILITY = 'manage_options';

    public static function register(): void {
        add_options_page('Avanik Supplier API Contract Readiness','Supplier API Contract Readiness',self::CAPABILITY,'avanik-phase-206-supplier-api-contract-readiness',[self::class,'render']);
    }

    public static function evaluate(): array {
        $endpoint = trim((string)get_option('avanik_supplier_api_url',''));
        $provider = trim((string)get_option('avanik_supplier_provider',''));
        $contract = [
            'search' => ['method'=>'GET','path'=>'/search','required'=>true],
            'availability' => ['method'=>'GET','path'=>'/availability','required'=>true],
            'booking' => ['method'=>'POST','path'=>'/bookings','required'=>true],
            'ticket' => ['method'=>'POST','path'=>'/tickets','required'=>true],
            'cancel' => ['method'=>'POST','path'=>'/bookings/{id}/cancel','required'=>true],
        ];
        $configured = $provider !== '' && $endpoint !== '';
        $result = [
            'contract_status' => $configured ? 'defined_pending_provider_mapping' : 'blocked_missing_supplier_configuration',
            'provider_configured' => $provider !== '',
            'endpoint_configured' => $endpoint !== '',
            'live_connection_verified' => false,
            'booking_execution_allowed' => false,
            'contract' => $contract,
            'event' => 'supplier_api_contract_readiness_evaluated',
            'reason' => $configured ? 'contract_defined_but_provider_mapping_and_sandbox_validation_are_required' : 'supplier_provider_and_endpoint_are_required',
            'evaluated_at' => time(),
        ];
        update_option(self::OPTION,$result,false);
        return $result;
    }

    public static function render(): void {
        if (!current_user_can(self::CAPABILITY)) return;
        $r=self::evaluate();
        echo '<div class="wrap"><h1>Avanik Supplier API Contract Readiness</h1><p>Phase 206 defines the provider-neutral contract. No live booking or ticket operation is executed.</p><table class="widefat striped"><tbody>';
        foreach([
            'Contract status'=>strtoupper(str_replace('_',' ',$r['contract_status'])),
            'Provider configured'=>$r['provider_configured']?'YES':'NO',
            'Endpoint configured'=>$r['endpoint_configured']?'YES':'NO',
            'Live connection verified'=>$r['live_connection_verified']?'YES':'NO',
            'Booking execution allowed'=>$r['booking_execution_allowed']?'YES':'NO',
            'Event'=>str_replace('_',' ',$r['event']),
            'Reason'=>str_replace('_',' ',$r['reason']),
            'Evaluated at'=>wp_date('Y-m-d H:i:s',$r['evaluated_at']),
        ] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table><h2>Required Provider Operations</h2><table class="widefat striped"><thead><tr><th>Operation</th><th>Method</th><th>Path</th></tr></thead><tbody>';
        foreach($r['contract'] as $name=>$op) echo '<tr><td>'.esc_html($name).'</td><td>'.esc_html($op['method']).'</td><td><code>'.esc_html($op['path']).'</code></td></tr>';
        echo '</tbody></table></div>';
    }
}
