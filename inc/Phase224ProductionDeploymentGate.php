<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class Phase224ProductionDeploymentGate {
    private const OPTION='avanik_phase_224_production_deployment_gate';
    private const CAPABILITY='manage_options';

    public static function register(): void {
        add_options_page('Avanik Production Deployment Gate','Production Deployment Gate',self::CAPABILITY,'avanik-phase-224-production-deployment',[self::class,'render']);
    }

    public static function evaluate(): array {
        $authorization=get_option('avanik_phase_223_production_release_authorization',[]);
        $authorized=!empty($authorization['authorization_granted']) && !empty($authorization['production_deployment_allowed']);
        $environment=function_exists('wp_get_environment_type')?wp_get_environment_type():'unknown';
        $isProduction=$environment==='production';
        $r=[
            'status'=>$authorized?'deployment_gate_authorized_but_manual':'deployment_gate_blocked',
            'authorization_present'=>$authorized,
            'environment'=>$environment,
            'production_environment_detected'=>$isProduction,
            'deployment_executed'=>false,
            'deployment_action_enabled'=>false,
            'automatic_deployment'=>false,
            'external_supplier_calls'=>false,
            'external_payment_calls'=>false,
            'ticket_issuance'=>false,
            'event'=>'production_deployment_gate_evaluated',
            'reason'=>$authorized?'authorization_exists_but_deployment_remains_manual_and_requires_operator_confirmation':'phase_223_production_authorization_is_required_before_deployment',
            'evaluated_at'=>time(),
        ];
        update_option(self::OPTION,$r,false);
        return $r;
    }

    public static function render(): void {
        if(!current_user_can(self::CAPABILITY)) return;
        $s=self::evaluate();
        echo '<div class="wrap"><h1>Avanik Production Deployment Gate</h1><p>Phase 224 creates the final deployment boundary. No deployment is executed automatically.</p><table class="widefat striped"><tbody>';
        foreach(['Status'=>strtoupper(str_replace('_',' ',$s['status'])),'Authorization present'=>$s['authorization_present']?'YES':'NO','Environment'=>$s['environment'],'Production environment detected'=>$s['production_environment_detected']?'YES':'NO','Deployment executed'=>$s['deployment_executed']?'YES':'NO','Automatic deployment'=>$s['automatic_deployment']?'YES':'NO','External supplier calls'=>$s['external_supplier_calls']?'YES':'NO','External payment calls'=>$s['external_payment_calls']?'YES':'NO','Ticket issuance'=>$s['ticket_issuance']?'YES':'NO','Reason'=>str_replace('_',' ',$s['reason']),'Evaluated at'=>wp_date('Y-m-d H:i:s',$s['evaluated_at'])] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
