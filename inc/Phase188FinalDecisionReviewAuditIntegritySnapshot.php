<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class Phase188FinalDecisionReviewAuditIntegritySnapshot {
    private const OPTION='avanik_phase_188_final_decision_review_audit_integrity_snapshot';
    private const CAPABILITY='manage_options';
    public static function register(): void { add_options_page('SLA Drift Guard Review Audit Integrity Snapshot','Review Audit Integrity Snapshot',self::CAPABILITY,'avanik-phase-188-review-audit-integrity-snapshot',[self::class,'render']); }
    public static function evaluate(): array {
        $v=get_option('avanik_phase_187_final_decision_review_audit_integrity_verification',[]); $v=is_array($v)?$v:[];
        $valid=($v['verification_status']??'')==='verified' && ($v['gate_status']??'')==='open_for_final_decision_review' && ($v['integrity_status']??'')==='verified' && ($v['review_decision']??'')==='pending_review' && ($v['guard_release']??true)===false && ($v['execution_allowed']??true)===false;
        $r=['snapshot_status'=>$valid?'verified':'blocked','verification_status'=>(string)($v['verification_status']??'failed'),'gate_status'=>(string)($v['gate_status']??'unknown'),'integrity_status'=>(string)($v['integrity_status']??'failed'),'review_decision'=>(string)($v['review_decision']??'unknown'),'event'=>'final_decision_review_audit_integrity_verified_snapshot','guard_release'=>false,'execution_allowed'=>false,'reason'=>$valid?'verified_review_audit_integrity_state_snapshotted_and_locked':'review_audit_integrity_state_not_valid_for_snapshot','snapshotted_at'=>time()];
        update_option(self::OPTION,$r,false); return $r;
    }
    public static function render(): void { if(!current_user_can(self::CAPABILITY)) return; $s=self::evaluate(); echo '<div class="wrap"><h1>SLA Drift Guard Review Audit Integrity Snapshot</h1><p>Phase 188 creates a stable audit-integrity snapshot after Phase 187 verification. No final outcome or execution is authorized.</p><table class="widefat striped"><tbody>'; foreach(['Snapshot status'=>strtoupper($s['snapshot_status']),'Verification status'=>strtoupper($s['verification_status']),'Gate status'=>strtoupper(str_replace('_',' ',$s['gate_status'])),'Integrity status'=>strtoupper($s['integrity_status']),'Review decision'=>strtoupper(str_replace('_',' ',$s['review_decision'])),'Event'=>str_replace('_',' ',$s['event']),'Guard release'=>$s['guard_release']?'YES':'NO','Execution allowed'=>$s['execution_allowed']?'YES':'NO','Reason'=>str_replace('_',' ',$s['reason']),'Snapshotted at'=>wp_date('Y-m-d H:i:s',$s['snapshotted_at'])] as $k=>$val) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$val).'</td></tr>'; echo '</tbody></table></div>'; }
}
