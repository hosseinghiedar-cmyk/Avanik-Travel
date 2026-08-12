<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class Phase210PaymentGatewayVerificationProbe {
    private const OPTION='avanik_phase_210_payment_gateway_verification_probe';
    private const CAPABILITY='manage_options';

    public static function register(): void {
        add_options_page('Avanik Payment Gateway Verification Probe','Payment Gateway Verification Probe',self::CAPABILITY,'avanik-phase-210-payment-gateway-verification-probe',[self::class,'render']);
    }

    public static function evaluate(): array {
        $gateway=(string)get_option('avanik_payment_gateway','');
        $contract=interface_exists(__NAMESPACE__.'\\PaymentGateway') || class_exists(__NAMESPACE__.'\\PaymentGateway');
        $hasUrl=(string)get_option('avanik_payment_gateway_url','')!=='';
        $ready=$gateway!=='' && $contract && $hasUrl;
        $r=[
            'probe_status'=>$ready?'ready_for_operator_run':'blocked',
            'gateway_configured'=>$gateway!=='',
            'verification_contract_present'=>$contract,
            'verification_endpoint_configured'=>$hasUrl,
            'live_probe_executed'=>false,
            'verification_result'=>'not_run',
            'payment_execution_allowed'=>false,
            'ticket_issuance_allowed'=>false,
            'event'=>'payment_gateway_verification_probe_readiness_evaluated',
            'reason'=>$ready?'operator_credentials_and_sandbox_or_live_endpoint_required_for_probe':'gateway_contract_or_verification_endpoint_not_ready',
            'evaluated_at'=>time(),
        ];
        update_option(self::OPTION,$r,false);
        return $r;
    }

    public static function render(): void {
        if(!current_user_can(self::CAPABILITY)) return;
        $s=self::evaluate();
        echo '<div class="wrap"><h1>Avanik Payment Gateway Verification Probe</h1><p>Phase 210 prepares a safe operator-run payment verification probe. No payment request is sent automatically.</p><table class="widefat striped"><tbody>';
        foreach([
            'Probe status'=>strtoupper(str_replace('_',' ',$s['probe_status'])),
            'Gateway configured'=>$s['gateway_configured']?'YES':'NO',
            'Verification contract present'=>$s['verification_contract_present']?'YES':'NO',
            'Verification endpoint configured'=>$s['verification_endpoint_configured']?'YES':'NO',
            'Live probe executed'=>$s['live_probe_executed']?'YES':'NO',
            'Verification result'=>strtoupper(str_replace('_',' ',$s['verification_result'])),
            'Payment execution allowed'=>$s['payment_execution_allowed']?'YES':'NO',
            'Ticket issuance allowed'=>$s['ticket_issuance_allowed']?'YES':'NO',
            'Event'=>str_replace('_',' ',$s['event']),
            'Reason'=>str_replace('_',' ',$s['reason']),
            'Evaluated at'=>wp_date('Y-m-d H:i:s',$s['evaluated_at']),
        ] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
