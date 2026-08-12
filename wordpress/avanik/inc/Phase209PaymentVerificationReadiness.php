<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class Phase209PaymentVerificationReadiness {
    private const OPTION='avanik_phase_209_payment_verification_readiness';
    private const CAPABILITY='manage_options';

    public static function register(): void {
        add_options_page('Avanik Payment Verification Readiness','Payment Verification Readiness',self::CAPABILITY,'avanik-phase-209-payment-verification-readiness',[self::class,'render']);
    }

    public static function evaluate(): array {
        $gateway=(string)get_option('avanik_payment_gateway','');
        $configured=$gateway!=='';
        $hasVerificationContract=interface_exists(__NAMESPACE__.'\\PaymentGateway') || class_exists(__NAMESPACE__.'\\PaymentGateway');
        $ready=$configured && $hasVerificationContract;
        $r=[
            'readiness_status'=>$ready?'ready_for_gateway_verification':'blocked',
            'gateway_configured'=>$configured,
            'verification_contract_present'=>$hasVerificationContract,
            'live_verification_tested'=>false,
            'payment_execution_allowed'=>false,
            'ticket_issuance_allowed'=>false,
            'event'=>'payment_verification_readiness_evaluated',
            'reason'=>$ready?'gateway_contract_ready_but_live_verification_pending':'payment_gateway_or_verification_contract_not_ready',
            'evaluated_at'=>time(),
        ];
        update_option(self::OPTION,$r,false);
        return $r;
    }

    public static function render(): void {
        if(!current_user_can(self::CAPABILITY)) return;
        $s=self::evaluate();
        echo '<div class="wrap"><h1>Avanik Payment Verification Readiness</h1><p>Phase 209 prepares the payment verification boundary. No live payment verification or ticket issuance is executed.</p><table class="widefat striped"><tbody>';
        foreach([
            'Readiness status'=>strtoupper(str_replace('_',' ',$s['readiness_status'])),
            'Gateway configured'=>$s['gateway_configured']?'YES':'NO',
            'Verification contract present'=>$s['verification_contract_present']?'YES':'NO',
            'Live verification tested'=>$s['live_verification_tested']?'YES':'NO',
            'Payment execution allowed'=>$s['payment_execution_allowed']?'YES':'NO',
            'Ticket issuance allowed'=>$s['ticket_issuance_allowed']?'YES':'NO',
            'Event'=>str_replace('_',' ',$s['event']),
            'Reason'=>str_replace('_',' ',$s['reason']),
            'Evaluated at'=>wp_date('Y-m-d H:i:s',$s['evaluated_at']),
        ] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
