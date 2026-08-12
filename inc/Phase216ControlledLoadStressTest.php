<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class Phase216ControlledLoadStressTest {
    private const OPTION='avanik_phase_216_controlled_load_stress_test';
    private const CAPABILITY='manage_options';

    public static function register(): void {
        add_options_page('Avanik Controlled Load Test','Controlled Load Test',self::CAPABILITY,'avanik-phase-216-controlled-load-test',[self::class,'render']);
    }

    public static function evaluate(): array {
        $requested=(int)get_option('avanik_phase_216_requested_iterations',0);
        $max=1000;
        $safe=$requested>0 && $requested<=$max;
        $r=[
            'status'=>$safe?'test_plan_valid':'test_plan_not_configured',
            'requested_iterations'=>$requested,
            'maximum_iterations'=>$max,
            'execution_performed'=>false,
            'synthetic_only'=>true,
            'external_supplier_calls'=>false,
            'external_payment_calls'=>false,
            'ticket_issuance'=>false,
            'production_traffic'=>false,
            'event'=>'controlled_load_stress_test_plan_evaluated',
            'reason'=>$safe?'plan_is_within_safe_iteration_limit_but_execution_requires_dedicated_test_environment':'configure_a_positive_iteration_count_up_to_the_safe_limit',
            'evaluated_at'=>time(),
        ];
        update_option(self::OPTION,$r,false);
        return $r;
    }

    public static function render(): void {
        if(!current_user_can(self::CAPABILITY)) return;
        $s=self::evaluate();
        echo '<div class="wrap"><h1>Avanik Controlled Load / Stress Test</h1><p>This phase validates a bounded synthetic test plan. It does not generate external supplier, payment, ticket, or production traffic.</p><table class="widefat striped"><tbody>';
        foreach([
            'Status'=>strtoupper(str_replace('_',' ',$s['status'])),
            'Requested iterations'=>$s['requested_iterations'],
            'Maximum iterations'=>$s['maximum_iterations'],
            'Execution performed'=>$s['execution_performed']?'YES':'NO',
            'Synthetic only'=>$s['synthetic_only']?'YES':'NO',
            'External supplier calls'=>$s['external_supplier_calls']?'YES':'NO',
            'External payment calls'=>$s['external_payment_calls']?'YES':'NO',
            'Ticket issuance'=>$s['ticket_issuance']?'YES':'NO',
            'Production traffic'=>$s['production_traffic']?'YES':'NO',
            'Reason'=>str_replace('_',' ',$s['reason']),
            'Evaluated at'=>wp_date('Y-m-d H:i:s',$s['evaluated_at']),
        ] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
