<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class Phase222FinalProductionReadiness {
    private const OPTION='avanik_phase_222_final_production_readiness';
    private const CAPABILITY='manage_options';

    public static function register(): void {
        add_options_page('Avanik Final Production Readiness','Final Production Readiness',self::CAPABILITY,'avanik-phase-222-final-production-readiness',[self::class,'render']);
    }

    public static function evaluate(): array {
        $checks=[
            'security_baseline'=>class_exists(__NAMESPACE__.'\\Phase212SecurityHardeningReadiness'),
            'e2e_execution'=>class_exists(__NAMESPACE__.'\\Phase214E2ETestExecution'),
            'load_stress_boundary'=>class_exists(__NAMESPACE__.'\\Phase216ControlledLoadStressTest'),
            'monitoring_baseline'=>class_exists(__NAMESPACE__.'\\Phase217MonitoringAlertingReadiness'),
            'backup_restore'=>class_exists(__NAMESPACE__.'\\Phase218BackupRestoreReadiness'),
            'rollback_recovery'=>class_exists(__NAMESPACE__.'\\Phase219RollbackRecoveryReadiness'),
            'staging_readiness'=>class_exists(__NAMESPACE__.'\\Phase220StagingDeploymentReadiness'),
            'release_candidate'=>class_exists(__NAMESPACE__.'\\Phase221ReleaseCandidate'),
        ];
        $baseline=!in_array(false,$checks,true);
        $evidence=[
            'rc_artifact_hash'=>false,
            'staging_validation'=>false,
            'e2e_evidence'=>false,
            'load_test_evidence'=>false,
            'monitoring_alert_evidence'=>false,
            'backup_restore_evidence'=>false,
            'rollback_evidence'=>false,
            'security_signoff'=>false,
        ];
        $allEvidence=true;
        foreach($evidence as $v){if(!$v){$allEvidence=false;break;}}
        $approved=$baseline && $allEvidence;
        $r=[
            'status'=>$approved?'production_ready_pending_manual_release':'production_gate_blocked',
            'checks'=>$checks,
            'evidence'=>$evidence,
            'production_release_approved'=>$approved,
            'production_deployment_executed'=>false,
            'external_supplier_calls'=>false,
            'external_payment_calls'=>false,
            'ticket_issuance'=>false,
            'event'=>'final_production_readiness_evaluated',
            'reason'=>$approved?'all_required_evidence_present_release_requires_explicit_authorization':'one_or_more_required_evidence_items_are_missing',
            'evaluated_at'=>time(),
        ];
        update_option(self::OPTION,$r,false);
        return $r;
    }

    public static function render(): void {
        if(!current_user_can(self::CAPABILITY)) return;
        $s=self::evaluate();
        echo '<div class="wrap"><h1>Avanik Final Production Readiness</h1><p>Phase 222 is the final evidence gate. It never deploys production automatically and remains blocked until every required evidence item is explicitly recorded.</p><table class="widefat striped"><tbody>';
        echo '<tr><th>Status</th><td>'.esc_html(strtoupper(str_replace('_',' ',$s['status']))).'</td></tr>';
        echo '<tr><th>Production release approved</th><td>'.($s['production_release_approved']?'YES':'NO').'</td></tr><tr><th>Production deployment executed</th><td>NO</td></tr>';
        foreach($s['checks'] as $k=>$v) echo '<tr><th>'.esc_html(ucwords(str_replace('_',' ',$k))).'</th><td>'.($v?'YES':'NO').'</td></tr>';
        foreach($s['evidence'] as $k=>$v) echo '<tr><th>'.esc_html('Evidence: '.ucwords(str_replace('_',' ',$k))).'</th><td>'.($v?'RECORDED':'MISSING').'</td></tr>';
        echo '<tr><th>Reason</th><td>'.esc_html(str_replace('_',' ',$s['reason'])).'</td></tr><tr><th>Evaluated at</th><td>'.esc_html(wp_date('Y-m-d H:i:s',$s['evaluated_at'])).'</td></tr></tbody></table></div>';
    }
}
