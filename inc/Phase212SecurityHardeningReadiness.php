<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class Phase212SecurityHardeningReadiness {
    private const OPTION='avanik_phase_212_security_hardening_readiness';
    private const CAPABILITY='manage_options';

    public static function register(): void {
        add_options_page('Avanik Security Hardening Readiness','Security Hardening Readiness',self::CAPABILITY,'avanik-phase-212-security-hardening-readiness',[self::class,'render']);
    }

    public static function evaluate(): array {
        $checks=[
            'admin_capability'=>self::CAPABILITY,
            'nonce_api_available'=>function_exists('wp_create_nonce'),
            'sanitize_api_available'=>function_exists('sanitize_text_field'),
            'escape_api_available'=>function_exists('esc_html'),
            'rest_api_available'=>function_exists('register_rest_route'),
            'ssl_available'=>function_exists('is_ssl'),
            'debug_display'=>defined('WP_DEBUG_DISPLAY')?(bool)WP_DEBUG_DISPLAY:false,
        ];
        $critical=($checks['nonce_api_available']&&$checks['sanitize_api_available']&&$checks['escape_api_available']&&$checks['rest_api_available']);
        $r=[
            'status'=>$critical?'baseline_controls_present':'blocked',
            'checks'=>$checks,
            'secrets_exposed_by_this_phase'=>false,
            'payment_execution_allowed'=>false,
            'ticket_issuance_allowed'=>false,
            'production_release_allowed'=>false,
            'event'=>'security_hardening_readiness_evaluated',
            'reason'=>$critical?'baseline_wordpress_security_controls_are_available_but_full_security_review_is_pending':'required_wordpress_security_controls_are_missing',
            'evaluated_at'=>time(),
        ];
        update_option(self::OPTION,$r,false);
        return $r;
    }

    public static function render(): void {
        if(!current_user_can(self::CAPABILITY)) return;
        $s=self::evaluate();
        echo '<div class="wrap"><h1>Avanik Security Hardening Readiness</h1><p>Phase 212 establishes the security baseline without exposing secrets or enabling production execution.</p><table class="widefat striped"><tbody>';
        foreach([
            'Status'=>strtoupper(str_replace('_',' ',$s['status'])),
            'Admin capability'=>$s['checks']['admin_capability'],
            'Nonce API available'=>$s['checks']['nonce_api_available']?'YES':'NO',
            'Sanitize API available'=>$s['checks']['sanitize_api_available']?'YES':'NO',
            'Escape API available'=>$s['checks']['escape_api_available']?'YES':'NO',
            'REST API available'=>$s['checks']['rest_api_available']?'YES':'NO',
            'SSL API available'=>$s['checks']['ssl_available']?'YES':'NO',
            'WP debug display'=>$s['checks']['debug_display']?'YES':'NO',
            'Secrets exposed by this phase'=>$s['secrets_exposed_by_this_phase']?'YES':'NO',
            'Payment execution allowed'=>$s['payment_execution_allowed']?'YES':'NO',
            'Ticket issuance allowed'=>$s['ticket_issuance_allowed']?'YES':'NO',
            'Production release allowed'=>$s['production_release_allowed']?'YES':'NO',
            'Reason'=>str_replace('_',' ',$s['reason']),
            'Evaluated at'=>wp_date('Y-m-d H:i:s',$s['evaluated_at']),
        ] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
