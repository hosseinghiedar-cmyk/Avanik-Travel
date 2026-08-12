<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class Phase186FinalDecisionReviewAuditIntegrityGate {
    private const OPTION='avanik_phase_186_final_decision_review_audit_integrity_gate';
    private const CAPABILITY='manage_options';
    public static function register(): void { add_options_page('SLA Drift Guard Review Audit Integrity Gate','Review Audit Integrity Gate',self::CAPABILITY,'avanik-phase-186-review-audit-integrity-gate',[self::class,'render']); }
    public static function evaluate(): array {
        $v=get_option('avanik_phase_185_final_decision_review_audit_verification',[]); $v=is_array($v)?$v:[];
        $valid=($v['verification_status']??'')==='verified' && ($v['audit_status']??'')==='verified' && ($v['review_status']??'')==='review_open' && ($v['review_decision']??'')==='pending_review' && ($v['integrity_status']??'')==='verified' && ($v['guard_release']??true)===false && ($v['execution_allowed']??true)===false;
        $r=['gate_status'=>$valid?'open_for_final_decision_review':'blocked','integrity_status'=>$valid?'verified':'failed','review_decision'=>'pending_review','guard_release'=>false,'execution_allowed'=>false,'reason'=>$valid?'review_audit_integrity_verified_gate_open_only_for_review':'review_audit_integrity_failed_gate_blocked','evaluated_at'=>time()];
        update_option(self::OPTION,$r,false); return $r;
    }
    public static function render(): void { if(!current_user_can(self::CAPABILITY)) return; $s=self::evaluate(); echo '<div class="wrap"><h1>SLA Drift Guard Review Audit Integrity Gate</h1><p>Phase 186 opens the integrity-verified review gate only; no final outcome or execution is authorized.</p><table class="widefat striped"><tbody>'; foreach(['Gate status'=>strtoupper(str_replace('_',' ',$s['gate_status'])),'Integrity status'=>strtoupper($s['integrity_status']),'Review decision'=>strtoupper(str_replace('_',' ',$s['review_decision'])),'Guard release'=>$s['guard_release']?'YES':'NO','Execution allowed'=>$s['execution_allowed']?'YES':'NO','Reason'=>str_replace('_',' ',$s['reason']),'Evaluated at'=>wp_date('Y-m-d H:i:s',$s['evaluated_at'])] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>'; echo '</tbody></table></div>'; }
}
