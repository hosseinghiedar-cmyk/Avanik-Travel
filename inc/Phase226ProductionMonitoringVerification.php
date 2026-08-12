<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class Phase226ProductionMonitoringVerification {
    private const OPTION='avanik_phase_226_production_monitoring_verification';
    private const CAPABILITY='manage_options';

    public static function register(): void {
        add_options_page('Avanik Production Monitoring Verification','Production Monitoring Verification',self::CAPABILITY,'avanik-phase-226-monitoring',[self::class,'render']);
    }

    public static function evaluate(): array {
        $checks=[
            'error_logging'=>function_exists('error_log'),
            'cron'=>function_exists('wp_schedule_event'),
            'rest_api'=>function_exists('register_rest_route'),
            'health_environment'=>function_exists('wp_get_environment_type'),
            'persistent_options'=>function_exists('get_option') && function_exists('update_option'),
        ];
        $baseline=!in_array(false,$checks,true);
        $metrics=['availability'=>'','latency'=>'','error_rate'=>'','booking_failures'=>'','payment_failures'=>'','supplier_failures'=>'','ticket_failures'=>'','cron_failures'=>''];
        $alerts=['availability'=>'','latency'=>'','error_rate'=>'','booking'=>'','payment'=>'','supplier'=>'','ticket'=>'','cron'=>''];
        $metricsComplete=!in_array('',array_values($metrics),true);
        $alertsComplete=!in_array('',array_values($alerts),true);
        $verified=$baseline && $metricsComplete && $alertsComplete;
        $r=[
            'status'=>$verified?'monitoring_verified':'monitoring_evidence_pending',
            'checks'=>$checks,'metrics'=>$metrics,'alerts'=>$alerts,
            'verification_executed'=>false,'production_monitoring_confirmed'=>$verified,
            'incident_open'=>false,'event'=>'production_monitoring_verification_evaluated',
            'reason'=>$verified?'monitoring_evidence_complete':'production_monitoring_requires_recorded_metrics_and_alert_evidence',
            'evaluated_at'=>time(),
        ];
        update_option(self::OPTION,$r,false); return $r;
    }

    public static function render(): void {
        if(!current_user_can(self::CAPABILITY)) return;
        $s=self::evaluate();
        echo '<div class="wrap"><h1>Avanik Production Monitoring Verification</h1><p>Phase 226 records the evidence boundary for post-deployment monitoring. It does not fabricate production metrics or alert results.</p><table class="widefat striped"><tbody>';
        echo '<tr><th>Status</th><td>'.esc_html(strtoupper(str_replace('_',' ',$s['status']))).'</td></tr><tr><th>Verification executed</th><td>'.($s['verification_executed']?'YES':'NO').'</td></tr><tr><th>Production monitoring confirmed</th><td>'.($s['production_monitoring_confirmed']?'YES':'NO').'</td></tr>';
        foreach($s['metrics'] as $k=>$v) echo '<tr><th>Metric: '.esc_html(str_replace('_',' ',$k)).'</th><td>'.($v!==''?'PRESENT':'MISSING').'</td></tr>';
        foreach($s['alerts'] as $k=>$v) echo '<tr><th>Alert: '.esc_html(str_replace('_',' ',$k)).'</th><td>'.($v!==''?'PRESENT':'MISSING').'</td></tr>';
        echo '<tr><th>Incident open</th><td>'.($s['incident_open']?'YES':'NO').'</td></tr><tr><th>Reason</th><td>'.esc_html(str_replace('_',' ',$s['reason'])).'</td></tr><tr><th>Evaluated at</th><td>'.esc_html(wp_date('Y-m-d H:i:s',$s['evaluated_at'])).'</td></tr>';
        foreach($s['checks'] as $k=>$v) echo '<tr><th>Baseline: '.esc_html(str_replace('_',' ',$k)).'</th><td>'.($v?'YES':'NO').'</td></tr>';
        echo '</tbody></table></div>';
    }
}
