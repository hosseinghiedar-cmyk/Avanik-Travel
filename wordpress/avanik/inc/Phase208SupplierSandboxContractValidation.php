<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class Phase208SupplierSandboxContractValidation {
    private const OPTION='avanik_phase_208_supplier_sandbox_contract_validation';
    private const CAPABILITY='manage_options';

    public static function register(): void {
        add_options_page('Avanik Supplier Sandbox Contract Validation','Supplier Sandbox Contract Validation',self::CAPABILITY,'avanik-phase-208-supplier-sandbox-contract-validation',[self::class,'render']);
    }

    public static function evaluate(): array {
        $mapping=get_option('avanik_phase_207_supplier_sandbox_provider_mapping',[]);
        $mapping=is_array($mapping)?$mapping:[];
        $required=['search','availability','booking','ticket','cancel'];
        $routes=is_array($mapping['routes']??null)?$mapping['routes']:[];
        $missing=[];
        foreach($required as $operation){
            if(empty($routes[$operation])) $missing[]=$operation;
        }
        $sandbox=!empty($mapping['sandbox_url']);
        $provider=!empty($mapping['provider']);
        $valid=$provider && $sandbox && !$missing;
        $result=[
            'validation_status'=>$valid?'ready_for_sandbox_test':'blocked',
            'provider_configured'=>$provider,
            'sandbox_configured'=>$sandbox,
            'contract_operations'=>$required,
            'missing_operations'=>$missing,
            'schema_validation'=>'pending',
            'sandbox_execution'=>'blocked',
            'booking_execution'=>'blocked',
            'ticket_issuance'=>'blocked',
            'payment_execution'=>'blocked',
            'event'=>'supplier_sandbox_contract_validation_completed',
            'reason'=>$valid?'sandbox_contract_structure_ready_for_non_production_validation':'sandbox_contract_mapping_incomplete',
            'validated_at'=>time(),
        ];
        update_option(self::OPTION,$result,false);
        return $result;
    }

    public static function render(): void {
        if(!current_user_can(self::CAPABILITY)) return;
        $r=self::evaluate();
        echo '<div class="wrap"><h1>Avanik Supplier Sandbox Contract Validation</h1><p>Validates the Phase 207 provider mapping structure without calling booking, ticketing, payment, or cancellation operations.</p><table class="widefat striped"><tbody>';
        foreach([
            'Validation status'=>strtoupper(str_replace('_',' ',$r['validation_status'])),
            'Provider configured'=>$r['provider_configured']?'YES':'NO',
            'Sandbox configured'=>$r['sandbox_configured']?'YES':'NO',
            'Contract operations'=>implode(', ',$r['contract_operations']),
            'Missing operations'=>implode(', ',$r['missing_operations'])?:'NONE',
            'Schema validation'=>strtoupper($r['schema_validation']),
            'Sandbox execution'=>strtoupper($r['sandbox_execution']),
            'Booking execution'=>strtoupper($r['booking_execution']),
            'Ticket issuance'=>strtoupper($r['ticket_issuance']),
            'Payment execution'=>strtoupper($r['payment_execution']),
            'Event'=>str_replace('_',' ',$r['event']),
            'Reason'=>str_replace('_',' ',$r['reason']),
            'Validated at'=>wp_date('Y-m-d H:i:s',$r['validated_at']),
        ] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
