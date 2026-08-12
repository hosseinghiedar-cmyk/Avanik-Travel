<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class Phase220StagingDeploymentReadiness {
    private const OPTION='avanik_phase_220_staging_deployment_readiness';
    private const CAPABILITY='manage_options';

    public static function register(): void {
        add_options_page('Avanik Staging Deployment Readiness','Staging Deployment Readiness',self::CAPABILITY,'avanik-phase-220-staging-readiness',[self::class,'render']);
    }

    public static function evaluate(): array {
        $environment=function_exists('wp_get_environment_type')?wp_get_environment_type():'unknown';
        $checks=[
            'environment_api_available'=>function_exists('wp_get_environment_type'),
            'maintenance_api_available'=>function_exists('wp_maintenance'),
            'cron_api_available'=>function_exists('wp_schedule_event'),
            'rest_api_available'=>function_exists('register_rest_route'),
            'security_baseline_present'=>class_exists(__NAMESPACE__.'\\Phase212SecurityHardeningReadiness'),
            'backup_baseline_present'=>class_exists(__NAMESPACE__.'\\Phase218BackupRestoreReadiness'),
            'rollback_baseline_present'=>class_exists(__NAMESPACE__.'\\Phase219RollbackRecoveryReadiness'),
        ];
        $baseline=!in_array(false,$checks,true);
        $staging=$environment==='staging';
        $r=[
            'status'=>$baseline&&$staging?'staging_baseline_ready':($baseline?'staging_environment_not_selected':'staging_baseline_incomplete'),
            'environment'=>$environment,
            'checks'=>$checks,
            'deployment_executed'=>false,
            'production_deployment_allowed'=>false,
            'external_supplier_calls'=>false,
            'external_payment_calls'=>false,
            'ticket_issuance'=>false,
            'event'=>'staging_deployment_readiness_evaluated',
            'reason'=>$baseline&&$staging?'staging_baseline_ready_but_deployment_is_manual_and_pending':'select_staging_environment_and_complete_required_baselines_before_deployment',
            'evaluated_at'=>time(),
        ];
        update_option(self::OPTION,$r,false);
        return $r;
    }

    public static function render(): void {
        if(!current_user_can(self::CAPABILITY)) return;
        $s=self::evaluate();
        echo '<div class="wrap"><h1>Avanik Staging Deployment Readiness</h1><p>Phase 220 prepares a controlled staging deployment boundary. It never deploys automatically and never enables production traffic.</p><table class="widefat striped"><tbody>';
        foreach([
            'Status'=>strtoupper(str_replace('_',' ',$s['status'])),
            'Environment'=>$s['environment'],
            'Deployment executed'=>$s['deployment_executed']?'YES':'NO',
            'Production deployment allowed'=>$s['production_deployment_allowed']?'YES':'NO',
            'External supplier calls'=>$s['external_supplier_calls']?'YES':'NO',
            'External payment calls'=>$s['external_payment_calls']?'YES':'NO',
            'Ticket issuance'=>$s['ticket_issuance']?'YES':'NO',
            'Reason'=>str_replace('_',' ',$s['reason']),
            'Evaluated at'=>wp_date('Y-m-d H:i:s',$s['evaluated_at']),
        ] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        foreach($s['checks'] as $k=>$v) echo '<tr><th>'.esc_html(ucwords(str_replace('_',' ',$k))).'</th><td>'.($v?'YES':'NO').'</td></tr>';
        echo '</tbody></table></div>';
    }
}
