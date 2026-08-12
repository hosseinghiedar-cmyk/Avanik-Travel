<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class Phase218BackupRestoreReadiness {
    private const OPTION='avanik_phase_218_backup_restore_readiness';
    private const CAPABILITY='manage_options';

    public static function register(): void {
        add_options_page('Avanik Backup Restore Readiness','Backup & Restore',self::CAPABILITY,'avanik-phase-218-backup-restore',[self::class,'render']);
    }

    public static function evaluate(): array {
        $checks=[
            'wp_filesystem_api'=>class_exists('WP_Filesystem_Base'),
            'db_export_api'=>function_exists('wp_get_db_schema'),
            'cron_available'=>function_exists('wp_schedule_event'),
            'uploads_path_available'=>defined('WP_CONTENT_DIR') && is_dir(WP_CONTENT_DIR),
        ];
        $baseline=!in_array(false,$checks,true);
        $r=[
            'status'=>$baseline?'backup_baseline_ready':'backup_baseline_incomplete',
            'checks'=>$checks,
            'backup_executed'=>false,
            'restore_executed'=>false,
            'backup_destination_configured'=>false,
            'offsite_backup_verified'=>false,
            'restore_test_verified'=>false,
            'production_release_allowed'=>false,
            'event'=>'backup_restore_readiness_evaluated',
            'reason'=>$baseline?'backup_restore_apis_available_but_destination_and_restore_test_are_pending':'required_backup_restore_baseline_is_incomplete',
            'evaluated_at'=>time(),
        ];
        update_option(self::OPTION,$r,false);
        return $r;
    }

    public static function render(): void {
        if(!current_user_can(self::CAPABILITY)) return;
        $s=self::evaluate();
        echo '<div class="wrap"><h1>Avanik Backup & Restore Readiness</h1><p>Phase 218 establishes the backup and restore baseline without copying production data or executing a restore.</p><table class="widefat striped"><tbody>';
        echo '<tr><th>Status</th><td>'.esc_html(strtoupper(str_replace('_',' ',$s['status']))).'</td></tr>';
        foreach($s['checks'] as $k=>$v) echo '<tr><th>'.esc_html(ucwords(str_replace('_',' ',$k))).'</th><td>'.($v?'YES':'NO').'</td></tr>';
        foreach(['backup_executed'=>'Backup executed','restore_executed'=>'Restore executed','backup_destination_configured'=>'Backup destination configured','offsite_backup_verified'=>'Offsite backup verified','restore_test_verified'=>'Restore test verified','production_release_allowed'=>'Production release allowed'] as $k=>$label) echo '<tr><th>'.esc_html($label).'</th><td>'.($s[$k]?'YES':'NO').'</td></tr>';
        echo '<tr><th>Reason</th><td>'.esc_html(str_replace('_',' ',$s['reason'])).'</td></tr><tr><th>Evaluated at</th><td>'.esc_html(wp_date('Y-m-d H:i:s',$s['evaluated_at'])).'</td></tr></tbody></table></div>';
    }
}
