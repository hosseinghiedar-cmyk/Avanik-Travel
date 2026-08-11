<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class AvanikPhase178FinalDecisionAuditVerification {
    private const OPTION='avanik_phase_178_final_decision_audit_verification';
    private const CAPABILITY='manage_options';
    public static function register(): void { add_options_page('SLA Drift Guard Phase 178','SLA Drift Guard Phase 178',self::CAPABILITY,'avanik-phase-178-final-decision-audit-verification',[self::class,'render']); }
    public static function evaluate(): array {
        $v=get_option('avanik_sla_drift_guard_release_final_decision_audit_verification',[]); $v=is_array($v)?$v:[];
        $valid=($v['verification_status']??'')==='verified' && ($v['audit_status']??'')==='verified' && ($v['decision_status']??'')==='ready_for_final_decision' && ($v['decision']??'')==='pending_final_decision' && ($v['guard_release']??true)===false && ($v['execution_allowed']??true)===false;
        $result=['verification_status'=>$valid?'verified':'failed','source_verification'=>(string)($v['verification_status']??'failed'),'audit_status'=>(string)($v['audit_status']??'unknown'),'decision_status'=>(string)($v['decision_status']??'unknown'),'decision'=>(string)($v['decision']??'unknown'),'guard_release'=>false,'execution_allowed'=>false,'event'=>'phase_178_final_decision_audit_verification','reason'=>$valid?'phase_177_audit_verification_is_valid_and_execution_remains_locked':'phase_177_audit_verification_is_invalid','verified_at'=>time()];
        update_option(self::OPTION,$result,false); return $result;
    }
    public static function render(): void { if(!current_user_can(self::CAPABILITY)) return; $s=self::evaluate(); echo '<div class="wrap"><h1>SLA Drift Guard — Phase 178</h1><p>Phase 178 performs a second integrity verification of the final-decision audit chain and keeps execution locked.</p><table class="widefat striped"><tbody>'; foreach(['Verification status'=>strtoupper($s['verification_status']),'Source verification'=>strtoupper($s['source_verification']),'Audit status'=>strtoupper($s['audit_status']),'Decision status'=>strtoupper(str_replace('_',' ',$s['decision_status'])),'Decision'=>strtoupper(str_replace('_',' ',$s['decision'])),'Guard release'=>$s['guard_release']?'YES':'NO','Execution allowed'=>$s['execution_allowed']?'YES':'NO','Reason'=>str_replace('_',' ',$s['reason']),'Verified at'=>wp_date('Y-m-d H:i:s',$s['verified_at'])] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>'; echo '</tbody></table></div>'; }
}
