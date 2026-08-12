<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class Phase225PostDeploymentSmokeTest {
    private const OPTION='avanik_phase_225_post_deployment_smoke_test';
    private const CAPABILITY='manage_options';

    public static function register(): void {
        add_options_page('Avanik Post Deployment Smoke Test','Post Deployment Smoke Test',self::CAPABILITY,'avanik-phase-225-smoke-test',[self::class,'render']);
    }

    public static function evaluate(): array {
        $checks=[
            'wordpress_loaded'=>function_exists('wp_get_environment_type'),
            'rest_api'=>function_exists('register_rest_route'),
            'cron'=>function_exists('wp_schedule_event'),
            'error_logging'=>function_exists('error_log'),
            'db_api'=>function_exists('get_option'),
        ];
        $baseline=!in_array(false,$checks,true);
        $r=[
            'status'=>$baseline?'smoke_test_plan_ready':'smoke_test_plan_incomplete',
            'checks'=>$checks,
            'execution_performed'=>false,
            'login_verified'=>false,
            'search_verified'=>false,
            'booking_verified'=>false,
            'api_verified'=>false,
            'database_verified'=>false,
            'error_handling_verified'=>false,
            'production_deployment_verified'=>false,
            'event'=>'post_deployment_smoke_test_evaluated',
            'reason'=>$baseline?'smoke_test_plan_ready_but_execution_requires_an_actual_deployed_target':'required_runtime_baseline_is_incomplete',
            'evaluated_at'=>time(),
        ];
        update_option(self::OPTION,$r,false);
        return $r;
    }

    public static function render(): void {
        if(!current_user_can(self::CAPABILITY)) return;
        $s=self::evaluate();
        echo '<div class="wrap"><h1>Avanik Post-Deployment Smoke Test</h1><p>Phase 225 defines the post-deployment smoke-test checklist. It does not claim a successful production deployment without a deployed target.</p><table class="widefat striped"><tbody>';
        foreach(['Status'=>strtoupper(str_replace('_',' ',$s['status'])),'Execution performed'=>$s['execution_performed']?'YES':'NO','Login verified'=>$s['login_verified']?'PASS':'PENDING','Search verified'=>$s['search_verified']?'PASS':'PENDING','Booking verified'=>$s['booking_verified']?'PASS':'PENDING','API verified'=>$s['api_verified']?'PASS':'PENDING','Database verified'=>$s['database_verified']?'PASS':'PENDING','Error handling verified'=>$s['error_handling_verified']?'PASS':'PENDING','Production deployment verified'=>$s['production_deployment_verified']?'YES':'NO','Reason'=>str_replace('_',' ',$s['reason']),'Evaluated at'=>wp_date('Y-m-d H:i:s',$s['evaluated_at'])] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        foreach($s['checks'] as $k=>$v) echo '<tr><th>'.esc_html(ucwords(str_replace('_',' ',$k))).'</th><td>'.($v?'YES':'NO').'</td></tr>';
        echo '</tbody></table></div>';
    }
}
