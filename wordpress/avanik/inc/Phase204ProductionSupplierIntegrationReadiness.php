<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class Phase204ProductionSupplierIntegrationReadiness {
    private const OPTION='avanik_phase_204_production_supplier_integration_readiness';
    private const CAPABILITY='manage_options';

    public static function register(): void {
        add_options_page('Avanik Production Supplier Integration Readiness','Production Supplier Integration Readiness',self::CAPABILITY,'avanik-phase-204-supplier-readiness',[self::class,'render']);
    }

    public static function evaluate(): array {
        $endpoint=trim((string)getenv('AVANIK_SUPPLIER_API_URL'));
        $key=trim((string)getenv('AVANIK_SUPPLIER_API_KEY'));
        $provider=trim((string)getenv('AVANIK_SUPPLIER_PROVIDER'));
        $configured=$endpoint!=='' && $key!=='' && $provider!=='';
        $r=[
            'status'=>$configured?'ready_for_supplier_connection':'blocked_pending_supplier_configuration',
            'provider'=>$provider!==''?$provider:'not_configured',
            'endpoint_configured'=>$endpoint!=='',
            'credential_configured'=>$key!=='',
            'live_connection_tested'=>false,
            'execution_allowed'=>false,
            'booking_release_allowed'=>false,
            'event'=>'production_supplier_integration_readiness_evaluated',
            'reason'=>$configured?'supplier_configuration_present_live_connection_requires_explicit_test':'supplier_provider_endpoint_or_credential_missing',
            'evaluated_at'=>time(),
        ];
        update_option(self::OPTION,$r,false);
        return $r;
    }

    public static function render(): void {
        if(!current_user_can(self::CAPABILITY)) return;
        $r=self::evaluate();
        echo '<div class="wrap"><h1>Avanik Production Supplier Integration Readiness</h1><p>This phase prepares the production supplier boundary without pretending that a live supplier connection exists.</p><table class="widefat striped"><tbody>';
        foreach([
            'Status'=>strtoupper(str_replace('_',' ',$r['status'])),
            'Provider'=>strtoupper(str_replace('_',' ',$r['provider'])),
            'Endpoint configured'=>$r['endpoint_configured']?'YES':'NO',
            'Credential configured'=>$r['credential_configured']?'YES':'NO',
            'Live connection tested'=>$r['live_connection_tested']?'YES':'NO',
            'Execution allowed'=>$r['execution_allowed']?'YES':'NO',
            'Booking release allowed'=>$r['booking_release_allowed']?'YES':'NO',
            'Event'=>str_replace('_',' ',$r['event']),
            'Reason'=>str_replace('_',' ',$r['reason']),
            'Evaluated at'=>wp_date('Y-m-d H:i:s',$r['evaluated_at']),
        ] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
