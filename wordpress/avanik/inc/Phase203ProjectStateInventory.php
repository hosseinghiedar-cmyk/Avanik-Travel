<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class Phase203ProjectStateInventory {
    private const OPTION='avanik_phase_203_project_state_inventory';
    private const CAPABILITY='manage_options';

    public static function register(): void {
        add_options_page('Avanik Project State Inventory','Project State Inventory',self::CAPABILITY,'avanik-phase-203-project-state-inventory',[self::class,'render']);
    }

    public static function inventory(): array {
        $items=[
            'core_wordpress_theme'=>['status'=>'implemented','evidence'=>'wordpress/avanik + frozen architecture'],
            'authentication_foundation'=>['status'=>'implemented','evidence'=>'README Sprint 020'],
            'booking_persistence'=>['status'=>'implemented','evidence'=>'BookingRepository / BookingSchema'],
            'booking_service_lifecycle'=>['status'=>'implemented','evidence'=>'BookingService / BookingLifecycle'],
            'payment_foundation'=>['status'=>'implemented','evidence'=>'PaymentRepository / PaymentService / PaymentLifecycle'],
            'payment_gateway_interface'=>['status'=>'implemented','evidence'=>'PaymentGatewayInterface'],
            'zarinpal_production_verification'=>['status'=>'missing','evidence'=>'ZarinPalGateway explicitly leaves API verification unimplemented'],
            'live_flight_supplier'=>['status'=>'missing','evidence'=>'NullFlightProvider is the safe placeholder; no live provider configured'],
            'provider_confirmation_boundary'=>['status'=>'implemented','evidence'=>'ProviderManager / ProviderConfirmationService'],
            'ticketing_boundary'=>['status'=>'implemented','evidence'=>'TicketingProviderInterface / TicketingService'],
            'refund_boundary'=>['status'=>'implemented','evidence'=>'RefundService / RefundRepository / settlement modules'],
            'notification_and_sla_foundation'=>['status'=>'implemented','evidence'=>'Notification and provider-health/SLA modules loaded by functions.php'],
            'production_secrets'=>['status'=>'missing','evidence'=>'Production credentials/configuration not committed and must be provisioned securely'],
            'security_audit'=>['status'=>'pending','evidence'=>'README lists security audit as still required'],
            'end_to_end_testing'=>['status'=>'pending','evidence'=>'README lists end-to-end testing as still required'],
            'load_testing'=>['status'=>'pending','evidence'=>'README lists load testing as still required'],
            'monitoring_validation'=>['status'=>'pending','evidence'=>'README lists monitoring as still required'],
            'backup_validation'=>['status'=>'pending','evidence'=>'README lists backups as still required'],
            'rollback_validation'=>['status'=>'pending','evidence'=>'README lists rollback validation as still required'],
            'production_deployment'=>['status'=>'pending','evidence'=>'Production deployment configuration remains required'],
        ];
        $counts=['implemented'=>0,'pending'=>0,'missing'=>0];
        foreach($items as $item){$status=$item['status'];if(isset($counts[$status]))$counts[$status]++;}
        $r=['phase'=>203,'inventory_status'=>'complete','counts'=>$counts,'items'=>$items,'next_workstream'=>'production_readiness','generated_at'=>time()];
        update_option(self::OPTION,$r,false);
        return $r;
    }

    public static function render(): void {
        if(!current_user_can(self::CAPABILITY)) return;
        $r=self::inventory();
        echo '<div class="wrap" dir="rtl"><h1>Avanik Project State Inventory — Phase 203</h1><p>این فاز به‌جای ادامه زنجیره Audit/Verification، وضعیت واقعی قابلیت‌های پروژه و موارد باقی‌مانده برای Production را مشخص می‌کند.</p>';
        echo '<p><strong>Implemented:</strong> '.esc_html((string)$r['counts']['implemented']).' &nbsp; <strong>Pending:</strong> '.esc_html((string)$r['counts']['pending']).' &nbsp; <strong>Missing:</strong> '.esc_html((string)$r['counts']['missing']).'</p>';
        echo '<table class="widefat striped"><thead><tr><th>Component</th><th>Status</th><th>Evidence</th></tr></thead><tbody>';
        foreach($r['items'] as $key=>$item) echo '<tr><td>'.esc_html(str_replace('_',' ',$key)).'</td><td>'.esc_html(strtoupper($item['status'])).'</td><td>'.esc_html($item['evidence']).'</td></tr>';
        echo '</tbody></table><p><strong>Next workstream:</strong> production readiness</p></div>';
    }
}
