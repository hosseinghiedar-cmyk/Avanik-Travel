<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class Phase215LoadStressTestReadiness {
    private const OPTION='avanik_phase_215_load_stress_test_readiness';
    private const CAPABILITY='manage_options';

    public static function register(): void {
        add_options_page('Avanik Load Stress Test Readiness','Load Stress Test Readiness',self::CAPABILITY,'avanik-phase-215-load-stress-test-readiness',[self::class,'render']);
    }

    public static function evaluate(): array {
        $checks=[
            'booking_class_present'=>class_exists(__NAMESPACE__.'\\Booking'),
            'supplier_boundary_present'=>class_exists(__NAMESPACE__.'\\FlightProvider') || interface_exists(__NAMESPACE__.'\\FlightProvider'),
            'payment_boundary_present'=>class_exists(__NAMESPACE__.'\\PaymentGateway') || interface_exists(__NAMESPACE__.'\\PaymentGateway'),
            'ticket_boundary_present'=>class_exists(__NAMESPACE__.'\\TicketingBoundary') || interface_exists(__NAMESPACE__.'\\TicketingBoundary'),
            'logging_available'=>function_exists('error_log'),
            'wp_cron_available'=>function_exists('wp_schedule_single_event'),
        ];
        $baseline=!in_array(false,$checks,true);
        $r=[
            'status'=>$baseline?'ready_for_controlled_load_test':'blocked_missing_load_test_baseline',
            'checks'=>$checks,
            'load_test_executed'=>false,
            'stress_test_executed'=>false,
            'external_supplier_calls'=>false,
            'external_payment_calls'=>false,
            'ticket_issuance_allowed'=>false,
            'production_traffic_allowed'=>false,
            'event'=>'load_stress_test_readiness_evaluated',
            'reason'=>$baseline?'baseline_ready_but_controlled_test_environment_and_limits_are_required':'one_or_more_required_application_boundaries_are_missing',
            'evaluated_at'=>time(),
        ];
        update_option(self::OPTION,$r,false);
        return $r;
    }

    public static function render(): void {
        if(!current_user_can(self::CAPABILITY)) return;
        $s=self::evaluate();
        echo '<div class="wrap"><h1>Avanik Load / Stress Test Readiness</h1><p>Phase 215 prepares controlled load testing. It never generates external supplier, payment, or ticket traffic.</p><table class="widefat striped"><tbody>';
        echo '<tr><th>Status</th><td>'.esc_html(strtoupper(str_replace('_',' ',$s['status']))).'</td></tr>';
        foreach($s['checks'] as $k=>$v) echo '<tr><th>'.esc_html(ucwords(str_replace('_',' ',$k))).'</th><td>'.($v?'YES':'NO').'</td></tr>';
        foreach(['load_test_executed'=>'Load test executed','stress_test_executed'=>'Stress test executed','external_supplier_calls'=>'External supplier calls','external_payment_calls'=>'External payment calls','ticket_issuance_allowed'=>'Ticket issuance allowed','production_traffic_allowed'=>'Production traffic allowed'] as $k=>$label) echo '<tr><th>'.esc_html($label).'</th><td>'.($s[$k]?'YES':'NO').'</td></tr>';
        echo '<tr><th>Reason</th><td>'.esc_html(str_replace('_',' ',$s['reason'])).'</td></tr><tr><th>Evaluated at</th><td>'.esc_html(wp_date('Y-m-d H:i:s',$s['evaluated_at'])).'</td></tr></tbody></table></div>';
    }
}
