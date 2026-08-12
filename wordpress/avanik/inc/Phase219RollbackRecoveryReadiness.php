<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class Phase219RollbackRecoveryReadiness {
    private const OPTION='avanik_phase_219_rollback_recovery_readiness';
    private const CAPABILITY='manage_options';

    public static function register(): void {
        add_options_page('Avanik Rollback Recovery Readiness','Rollback & Recovery',self::CAPABILITY,'avanik-phase-219-rollback-recovery',[self::class,'render']);
    }

    public static function evaluate(): array {
        $checks=[
            'backup_readiness_class'=>class_exists(__NAMESPACE__.'\\Phase218BackupRestoreReadiness'),
            'option_storage_available'=>function_exists('get_option') && function_exists('update_option'),
            'maintenance_mode_api'=>function_exists('wp_maintenance'),
            'cron_available'=>function_exists('wp_schedule_event'),
            'error_logging_available'=>function_exists('error_log'),
        ];
        $baseline=!in_array(false,$checks,true);
        $r=[
            'status'=>$baseline?'rollback_baseline_ready':'rollback_baseline_incomplete',
            'checks'=>$checks,
            'rollback_executed'=>false,
            'recovery_executed'=>false,
            'last_known_good_release'=>'not_registered',
            'backup_verified'=>'not_verified',
            'maintenance_mode_enabled'=>false,
            'production_rollback_allowed'=>false,
            'event'=>'rollback_recovery_readiness_evaluated',
            'reason'=>$baseline?'rollback_plan_baseline_ready_but_last_known_good_release_and_verified_backup_are_pending':'required_recovery_controls_are_missing',
            'evaluated_at'=>time(),
        ];
        update_option(self::OPTION,$r,false);
        return $r;
    }

    public static function render(): void {
        if(!current_user_can(self::CAPABILITY)) return;
        $s=self::evaluate();
        echo '<div class="wrap"><h1>Avanik Rollback & Recovery Readiness</h1><p>Phase 219 establishes a controlled rollback/recovery boundary. No rollback or recovery operation is executed automatically.</p><table class="widefat striped"><tbody>';
        foreach([
            'Status'=>strtoupper(str_replace('_',' ',$s['status'])),
            'Backup readiness class'=>$s['checks']['backup_readiness_class']?'YES':'NO',
            'Option storage available'=>$s['checks']['option_storage_available']?'YES':'NO',
            'Maintenance mode API'=>$s['checks']['maintenance_mode_api']?'YES':'NO',
            'Cron available'=>$s['checks']['cron_available']?'YES':'NO',
            'Error logging available'=>$s['checks']['error_logging_available']?'YES':'NO',
            'Rollback executed'=>$s['rollback_executed']?'YES':'NO',
            'Recovery executed'=>$s['recovery_executed']?'YES':'NO',
            'Last known good release'=>$s['last_known_good_release'],
            'Backup verified'=>$s['backup_verified'],
            'Maintenance mode enabled'=>$s['maintenance_mode_enabled']?'YES':'NO',
            'Production rollback allowed'=>$s['production_rollback_allowed']?'YES':'NO',
            'Reason'=>str_replace('_',' ',$s['reason']),
            'Evaluated at'=>wp_date('Y-m-d H:i:s',$s['evaluated_at']),
        ] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
