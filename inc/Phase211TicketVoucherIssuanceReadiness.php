<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class Phase211TicketVoucherIssuanceReadiness {
    private const OPTION='avanik_phase_211_ticket_voucher_issuance_readiness';
    private const CAPABILITY='manage_options';

    public static function register(): void {
        add_options_page('Avanik Ticket Voucher Issuance Readiness','Ticket/Voucher Readiness',self::CAPABILITY,'avanik-phase-211-ticket-voucher-readiness',[self::class,'render']);
    }

    public static function evaluate(): array {
        $supplier=get_option('avanik_phase_207_supplier_sandbox_provider_mapping',[]);
        $supplier=is_array($supplier)?$supplier:[];
        $payment=get_option('avanik_phase_210_payment_gateway_verification_probe',[]);
        $payment=is_array($payment)?$payment:[];
        $supplierReady=($supplier['status']??'')==='ready_for_sandbox_validation' && ($supplier['sandbox_validation']??'')==='verified';
        $paymentReady=($payment['verification_result']??'')==='verified';
        $ready=$supplierReady && $paymentReady;
        $r=[
            'readiness_status'=>$ready?'ready_for_ticket_voucher_issuance_validation':'blocked',
            'supplier_validation_status'=>$supplier['sandbox_validation']??'unknown',
            'payment_verification_status'=>$payment['verification_result']??'not_run',
            'ticket_contract_present'=>false,
            'voucher_contract_present'=>false,
            'issuance_tested'=>false,
            'ticket_issuance_allowed'=>false,
            'voucher_issuance_allowed'=>false,
            'event'=>'ticket_voucher_issuance_readiness_evaluated',
            'reason'=>$ready?'supplier_and_payment_prerequisites_ready_but_issuance_contract_and_e2e_test_are_pending':'supplier_validation_and_payment_verification_must_succeed_first',
            'evaluated_at'=>time(),
        ];
        update_option(self::OPTION,$r,false);
        return $r;
    }

    public static function render(): void {
        if(!current_user_can(self::CAPABILITY)) return;
        $s=self::evaluate();
        echo '<div class="wrap"><h1>Avanik Ticket / Voucher Issuance Readiness</h1><p>Phase 211 verifies prerequisites for real ticket/voucher issuance without issuing anything.</p><table class="widefat striped"><tbody>';
        foreach([
            'Readiness status'=>strtoupper(str_replace('_',' ',$s['readiness_status'])),
            'Supplier validation status'=>strtoupper(str_replace('_',' ',$s['supplier_validation_status'])),
            'Payment verification status'=>strtoupper(str_replace('_',' ',$s['payment_verification_status'])),
            'Ticket contract present'=>$s['ticket_contract_present']?'YES':'NO',
            'Voucher contract present'=>$s['voucher_contract_present']?'YES':'NO',
            'Issuance tested'=>$s['issuance_tested']?'YES':'NO',
            'Ticket issuance allowed'=>$s['ticket_issuance_allowed']?'YES':'NO',
            'Voucher issuance allowed'=>$s['voucher_issuance_allowed']?'YES':'NO',
            'Event'=>str_replace('_',' ',$s['event']),
            'Reason'=>str_replace('_',' ',$s['reason']),
            'Evaluated at'=>wp_date('Y-m-d H:i:s',$s['evaluated_at']),
        ] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
