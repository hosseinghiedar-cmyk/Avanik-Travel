<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class Phase227FinalProjectClosure {
    private const OPTION='avanik_phase_227_final_project_closure';
    private const CAPABILITY='manage_options';

    public static function register(): void {
        add_options_page('Avanik Final Project Closure','Final Project Closure',self::CAPABILITY,'avanik-phase-227-project-closure',[self::class,'render']);
    }

    public static function evaluate(): array {
        $required=[
            'phase_223_release_authorization'=>'avanik_phase_223_production_release_authorization',
            'phase_224_deployment_gate'=>'avanik_phase_224_production_deployment_gate',
            'phase_225_smoke_test'=>'avanik_phase_225_post_deployment_smoke_test',
            'phase_226_monitoring'=>'avanik_phase_226_production_monitoring_verification',
        ];
        $state=[];
        foreach($required as $name=>$option) $state[$name]=get_option($option,[]);

        $releaseAuthorized=!empty($state['phase_223_release_authorization']['authorization_granted']);
        $deploymentExecuted=!empty($state['phase_224_deployment_gate']['deployment_executed']);
        $smokeExecuted=!empty($state['phase_225_smoke_test']['execution_performed']);
        $monitoringVerified=!empty($state['phase_226_monitoring']['production_monitoring_confirmed']);
        $closed=$releaseAuthorized && $deploymentExecuted && $smokeExecuted && $monitoringVerified;

        $r=[
            'status'=>$closed?'project_closed':'closure_pending_runtime_evidence',
            'release_authorized'=>$releaseAuthorized,
            'deployment_executed'=>$deploymentExecuted,
            'smoke_test_executed'=>$smokeExecuted,
            'monitoring_verified'=>$monitoringVerified,
            'closure_granted'=>$closed,
            'operational_maintenance_state'=>$closed,
            'production_deployment_allowed'=>false,
            'event'=>'final_project_closure_evaluated',
            'reason'=>$closed?'all_required_release_and_post_deployment_evidence_present':'project_closure_requires_actual_release_deployment_smoke_test_and_monitoring_evidence',
            'evaluated_at'=>time(),
        ];
        update_option(self::OPTION,$r,false);
        return $r;
    }

    public static function render(): void {
        if(!current_user_can(self::CAPABILITY)) return;
        $s=self::evaluate();
        echo '<div class="wrap"><h1>Avanik Final Project Closure</h1><p>Phase 227 is the terminal project gate. It closes the project only after real release, deployment, smoke-test and monitoring evidence exists.</p><table class="widefat striped"><tbody>';
        foreach([
            'Status'=>strtoupper(str_replace('_',' ',$s['status'])),
            'Release authorized'=>$s['release_authorized']?'YES':'NO',
            'Production deployment executed'=>$s['deployment_executed']?'YES':'NO',
            'Smoke test executed'=>$s['smoke_test_executed']?'YES':'NO',
            'Production monitoring verified'=>$s['monitoring_verified']?'YES':'NO',
            'Closure granted'=>$s['closure_granted']?'YES':'NO',
            'Operational maintenance state'=>$s['operational_maintenance_state']?'YES':'NO',
            'Production deployment allowed'=>$s['production_deployment_allowed']?'YES':'NO',
            'Reason'=>str_replace('_',' ',$s['reason']),
            'Evaluated at'=>wp_date('Y-m-d H:i:s',$s['evaluated_at']),
        ] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
