<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class Phase214E2ETestExecution {
    private const OPTION='avanik_phase_214_e2e_test_execution';
    private const CAPABILITY='manage_options';

    public static function register(): void {
        add_options_page('Avanik E2E Test Execution','E2E Test Execution',self::CAPABILITY,'avanik-phase-214-e2e-test-execution',[self::class,'render']);
    }

    public static function run_internal(): array {
        $checks=[
            'booking'=>class_exists(__NAMESPACE__.'\\Booking'),
            'payment'=>class_exists(__NAMESPACE__.'\\PaymentGateway') || interface_exists(__NAMESPACE__.'\\PaymentGateway'),
            'supplier'=>class_exists(__NAMESPACE__.'\\FlightProvider') || interface_exists(__NAMESPACE__.'\\FlightProvider'),
            'ticketing'=>class_exists(__NAMESPACE__.'\\TicketingBoundary') || interface_exists(__NAMESPACE__.'\\TicketingBoundary'),
            'refund'=>class_exists(__NAMESPACE__.'\\Refund'),
            'notification'=>class_exists(__NAMESPACE__.'\\Notification'),
        ];
        $all=!in_array(false,$checks,true);
        $r=[
            'execution_status'=>$all?'internal_boundaries_passed':'internal_boundaries_failed',
            'checks'=>$checks,
            'external_execution'=>'not_run',
            'external_booking'=>'blocked',
            'external_payment'=>'blocked',
            'ticket_issuance'=>'blocked',
            'test_environment_required'=>true,
            'event'=>'e2e_internal_boundary_test_executed',
            'reason'=>$all?'internal_application_boundaries_are_available_external_e2e_requires_controlled_test_environment':'one_or_more_internal_boundaries_are_missing',
            'executed_at'=>time(),
        ];
        update_option(self::OPTION,$r,false);
        return $r;
    }

    public static function render(): void {
        if(!current_user_can(self::CAPABILITY)) return;
        $s=self::run_internal();
        echo '<div class="wrap"><h1>Avanik E2E Test Execution</h1><p>This phase executes only a non-destructive internal boundary test. External supplier, payment and ticket operations remain blocked.</p><table class="widefat striped"><tbody>';
        echo '<tr><th>Execution status</th><td>'.esc_html(strtoupper(str_replace('_',' ',$s['execution_status']))).'</td></tr>';
        foreach($s['checks'] as $k=>$v) echo '<tr><th>'.esc_html(ucfirst($k).' boundary').'</th><td>'.($v?'PASS':'FAIL').'</td></tr>';
        foreach(['external_execution'=>'External E2E','external_booking'=>'External booking','external_payment'=>'External payment','ticket_issuance'=>'Ticket issuance'] as $k=>$label) echo '<tr><th>'.esc_html($label).'</th><td>'.esc_html(strtoupper(str_replace('_',' ',$s[$k]))).'</td></tr>';
        echo '<tr><th>Test environment required</th><td>YES</td></tr><tr><th>Reason</th><td>'.esc_html(str_replace('_',' ',$s['reason'])).'</td></tr><tr><th>Executed at</th><td>'.esc_html(wp_date('Y-m-d H:i:s',$s['executed_at'])).'</td></tr></tbody></table></div>';
    }
}
