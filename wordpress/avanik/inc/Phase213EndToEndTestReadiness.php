<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class Phase213EndToEndTestReadiness {
    private const OPTION='avanik_phase_213_end_to_end_test_readiness';
    private const CAPABILITY='manage_options';

    public static function register(): void {
        add_options_page('Avanik E2E Test Readiness','E2E Test Readiness',self::CAPABILITY,'avanik-phase-213-e2e-test-readiness',[self::class,'render']);
    }

    public static function evaluate(): array {
        $checks=[
            'booking_flow_present'=>class_exists(__NAMESPACE__.'\\Booking'),
            'payment_boundary_present'=>class_exists(__NAMESPACE__.'\\PaymentGateway') || interface_exists(__NAMESPACE__.'\\PaymentGateway'),
            'supplier_boundary_present'=>class_exists(__NAMESPACE__.'\\FlightProvider') || interface_exists(__NAMESPACE__.'\\FlightProvider'),
            'ticket_boundary_present'=>class_exists(__NAMESPACE__.'\\TicketingBoundary') || interface_exists(__NAMESPACE__.'\\TicketingBoundary'),
            'refund_boundary_present'=>class_exists(__NAMESPACE__.'\\Refund'),
            'notification_boundary_present'=>class_exists(__NAMESPACE__.'\\Notification'),
        ];
        $baseline= !in_array(false,$checks,true);
        $r=[
            'status'=>$baseline?'baseline_ready_for_e2e':'blocked_missing_application_boundary',
            'checks'=>$checks,
            'e2e_execution_performed'=>false,
            'external_booking_allowed'=>false,
            'external_payment_allowed'=>false,
            'ticket_issuance_allowed'=>false,
            'event'=>'e2e_test_readiness_evaluated',
            'reason'=>$baseline?'application_boundaries_present_external_e2e_execution_requires_test_environment':'one_or_more_application_boundaries_are_missing',
            'evaluated_at'=>time(),
        ];
        update_option(self::OPTION,$r,false);
        return $r;
    }

    public static function render(): void {
        if(!current_user_can(self::CAPABILITY)) return;
        $s=self::evaluate();
        echo '<div class="wrap"><h1>Avanik End-to-End Test Readiness</h1><p>Phase 213 verifies that the major application boundaries exist before E2E testing. It does not execute external bookings, payments, or ticket issuance.</p><table class="widefat striped"><tbody>';
        echo '<tr><th>Status</th><td>'.esc_html(strtoupper(str_replace('_',' ',$s['status']))).'</td></tr>';
        foreach($s['checks'] as $k=>$v) echo '<tr><th>'.esc_html(ucwords(str_replace('_',' ',$k))).'</th><td>'.($v?'YES':'NO').'</td></tr>';
        foreach(['e2e_execution_performed'=>'E2E execution performed','external_booking_allowed'=>'External booking allowed','external_payment_allowed'=>'External payment allowed','ticket_issuance_allowed'=>'Ticket issuance allowed'] as $k=>$label) echo '<tr><th>'.esc_html($label).'</th><td>'.($s[$k]?'YES':'NO').'</td></tr>';
        echo '<tr><th>Reason</th><td>'.esc_html(str_replace('_',' ',$s['reason'])).'</td></tr><tr><th>Evaluated at</th><td>'.esc_html(wp_date('Y-m-d H:i:s',$s['evaluated_at'])).'</td></tr></tbody></table></div>';
    }
}
