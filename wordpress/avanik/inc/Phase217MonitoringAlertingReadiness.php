<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class Phase217MonitoringAlertingReadiness {
    private const OPTION='avanik_phase_217_monitoring_alerting_readiness';
    private const CAPABILITY='manage_options';

    public static function register(): void {
        add_options_page('Avanik Monitoring & Alerting','Monitoring & Alerting',self::CAPABILITY,'avanik-phase-217-monitoring-alerting',[self::class,'render']);
    }

    public static function evaluate(): array {
        $checks=[
            'wordpress_debug_api'=>defined('WP_DEBUG'),
            'error_logging_available'=>function_exists('error_log'),
            'cron_available'=>function_exists('wp_schedule_event'),
            'rest_api_available'=>function_exists('register_rest_route'),
            'health_check_available'=>function_exists('wp_get_environment_type'),
        ];
        $baseline=!in_array(false,$checks,true);
        $r=[
            'status'=>$baseline?'monitoring_baseline_ready':'monitoring_baseline_incomplete',
            'checks'=>$checks,
            'alert_channels_configured'=>false,
            'metrics_collection_enabled'=>false,
            'external_monitoring_enabled'=>false,
            'monitoring_test_executed'=>false,
            'production_release_allowed'=>false,
            'event'=>'monitoring_alerting_readiness_evaluated',
            'reason'=>$baseline?'baseline_apis_available_but_alert_channel_and_metric_destination_are_pending':'required_monitoring_apis_are_missing',
            'evaluated_at'=>time(),
        ];
        update_option(self::OPTION,$r,false);
        return $r;
    }

    public static function render(): void {
        if(!current_user_can(self::CAPABILITY)) return;
        $s=self::evaluate();
        echo '<div class="wrap"><h1>Avanik Monitoring & Alerting Readiness</h1><p>Phase 217 establishes the monitoring baseline. It does not enable external alerts or production release.</p><table class="widefat striped"><tbody>';
        echo '<tr><th>Status</th><td>'.esc_html(strtoupper(str_replace('_',' ',$s['status']))).'</td></tr>';
        foreach($s['checks'] as $k=>$v) echo '<tr><th>'.esc_html(ucwords(str_replace('_',' ',$k))).'</th><td>'.($v?'YES':'NO').'</td></tr>';
        foreach(['alert_channels_configured'=>'Alert channels configured','metrics_collection_enabled'=>'Metrics collection enabled','external_monitoring_enabled'=>'External monitoring enabled','monitoring_test_executed'=>'Monitoring test executed','production_release_allowed'=>'Production release allowed'] as $k=>$label) echo '<tr><th>'.esc_html($label).'</th><td>'.($s[$k]?'YES':'NO').'</td></tr>';
        echo '<tr><th>Reason</th><td>'.esc_html(str_replace('_',' ',$s['reason'])).'</td></tr><tr><th>Evaluated at</th><td>'.esc_html(wp_date('Y-m-d H:i:s',$s['evaluated_at'])).'</td></tr></tbody></table></div>';
    }
}
