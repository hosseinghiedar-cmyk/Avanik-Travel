<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class Phase221ReleaseCandidate {
    private const OPTION='avanik_phase_221_release_candidate';
    private const CAPABILITY='manage_options';

    public static function register(): void {
        add_options_page('Avanik Release Candidate','Release Candidate',self::CAPABILITY,'avanik-phase-221-release-candidate',[self::class,'render']);
    }

    public static function evaluate(): array {
        $checks=[
            'staging_readiness_present'=>class_exists(__NAMESPACE__.'\\Phase220StagingDeploymentReadiness'),
            'security_baseline_present'=>class_exists(__NAMESPACE__.'\\Phase212SecurityHardeningReadiness'),
            'e2e_readiness_present'=>class_exists(__NAMESPACE__.'\\Phase213EndToEndTestReadiness'),
            'backup_readiness_present'=>class_exists(__NAMESPACE__.'\\Phase218BackupRestoreReadiness'),
            'rollback_readiness_present'=>class_exists(__NAMESPACE__.'\\Phase219RollbackRecoveryReadiness'),
        ];
        $baseline=!in_array(false,$checks,true);
        $version=defined('AVANIK_VERSION')?(string)AVANIK_VERSION:'not_declared';
        $r=[
            'status'=>$baseline?'candidate_structure_ready':'candidate_blocked_missing_baseline',
            'version'=>$version,
            'checks'=>$checks,
            'candidate_artifact_registered'=>false,
            'candidate_hash_registered'=>false,
            'staging_validation_passed'=>false,
            'release_approved'=>false,
            'production_deployment_allowed'=>false,
            'external_supplier_calls'=>false,
            'external_payment_calls'=>false,
            'ticket_issuance'=>false,
            'event'=>'release_candidate_readiness_evaluated',
            'reason'=>$baseline?'candidate_structure_ready_but_immutable_artifact_hash_and_staging_evidence_are_pending':'required_release_baselines_are_missing',
            'evaluated_at'=>time(),
        ];
        update_option(self::OPTION,$r,false);
        return $r;
    }

    public static function render(): void {
        if(!current_user_can(self::CAPABILITY)) return;
        $s=self::evaluate();
        echo '<div class="wrap"><h1>Avanik Release Candidate</h1><p>Phase 221 defines a traceable release-candidate boundary. It does not approve or deploy to production.</p><table class="widefat striped"><tbody>';
        foreach(['Status'=>strtoupper(str_replace('_',' ',$s['status'])),'Version'=>$s['version'],'Candidate artifact registered'=>$s['candidate_artifact_registered']?'YES':'NO','Candidate hash registered'=>$s['candidate_hash_registered']?'YES':'NO','Staging validation passed'=>$s['staging_validation_passed']?'YES':'NO','Release approved'=>$s['release_approved']?'YES':'NO','Production deployment allowed'=>$s['production_deployment_allowed']?'YES':'NO','External supplier calls'=>$s['external_supplier_calls']?'YES':'NO','External payment calls'=>$s['external_payment_calls']?'YES':'NO','Ticket issuance'=>$s['ticket_issuance']?'YES':'NO','Reason'=>str_replace('_',' ',$s['reason']),'Evaluated at'=>wp_date('Y-m-d H:i:s',$s['evaluated_at'])] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        foreach($s['checks'] as $k=>$v) echo '<tr><th>'.esc_html(ucwords(str_replace('_',' ',$k))).'</th><td>'.($v?'YES':'NO').'</td></tr>';
        echo '</tbody></table></div>';
    }
}
