<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class Phase223ProductionReleaseAuthorization {
    private const OPTION='avanik_phase_223_production_release_authorization';
    private const CAPABILITY='manage_options';

    public static function register(): void {
        add_options_page('Avanik Production Release Authorization','Production Release Authorization',self::CAPABILITY,'avanik-phase-223-release-authorization',[self::class,'render']);
    }

    public static function evaluate(): array {
        $checks=[
            'final_readiness_present'=>class_exists(__NAMESPACE__.'\\Phase222FinalProductionReadiness'),
            'release_candidate_present'=>class_exists(__NAMESPACE__.'\\Phase221ReleaseCandidate'),
            'staging_present'=>class_exists(__NAMESPACE__.'\\Phase220StagingDeploymentReadiness'),
            'rollback_present'=>class_exists(__NAMESPACE__.'\\Phase219RollbackRecoveryReadiness'),
            'backup_present'=>class_exists(__NAMESPACE__.'\\Phase218BackupRestoreReadiness'),
            'monitoring_present'=>class_exists(__NAMESPACE__.'\\Phase217MonitoringAlertingReadiness'),
        ];
        $baseline=!in_array(false,$checks,true);
        $evidence=[
            'rc_hash'=>'', 'staging_validation'=>'', 'e2e'=>'', 'load_stress'=>'',
            'monitoring'=>'', 'backup_restore'=>'', 'rollback'=>'', 'security_signoff'=>'',
        ];
        $evidenceComplete=true;
        foreach($evidence as $v) if(trim($v)==='') {$evidenceComplete=false;break;}
        $authorized=$baseline && $evidenceComplete;
        $r=[
            'status'=>$authorized?'authorized':'blocked_missing_release_evidence',
            'checks'=>$checks,
            'evidence'=>$evidence,
            'authorization_granted'=>$authorized,
            'production_deployment_allowed'=>$authorized,
            'deployment_executed'=>false,
            'event'=>'production_release_authorization_evaluated',
            'reason'=>$authorized?'all_required_release_evidence_present':'production_authorization_requires_complete_release_evidence',
            'evaluated_at'=>time(),
        ];
        update_option(self::OPTION,$r,false);
        return $r;
    }

    public static function render(): void {
        if(!current_user_can(self::CAPABILITY)) return;
        $s=self::evaluate();
        echo '<div class="wrap"><h1>Avanik Production Release Authorization</h1><p>Phase 223 is an evidence gate. Production authorization is granted only when every required evidence field is populated.</p><table class="widefat striped"><tbody>';
        echo '<tr><th>Status</th><td>'.esc_html(strtoupper(str_replace('_',' ',$s['status']))).'</td></tr>';
        foreach($s['evidence'] as $k=>$v) echo '<tr><th>'.esc_html(ucwords(str_replace('_',' ',$k))).'</th><td>'.($v!==''?'PRESENT':'MISSING').'</td></tr>';
        foreach(['authorization_granted'=>'Authorization granted','production_deployment_allowed'=>'Production deployment allowed','deployment_executed'=>'Deployment executed'] as $k=>$label) echo '<tr><th>'.esc_html($label).'</th><td>'.($s[$k]?'YES':'NO').'</td></tr>';
        echo '<tr><th>Reason</th><td>'.esc_html(str_replace('_',' ',$s['reason'])).'</td></tr><tr><th>Evaluated at</th><td>'.esc_html(wp_date('Y-m-d H:i:s',$s['evaluated_at'])).'</td></tr></tbody></table></div>';
    }
}
