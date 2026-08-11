<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class Phase187FinalDecisionReviewAuditIntegrityVerification {
    private const OPTION='avanik_phase_187_final_decision_review_audit_integrity_verification';
    private const CAPABILITY='manage_options';
    public static function register(): void { add_options_page('SLA Drift Guard Review Audit Integrity Verification','Review Audit Integrity Verification',self::CAPABILITY,'avanik-phase-187-review-audit-integrity-verification',[self::class,'render']); }
    public static function evaluate(): array {
        $g=get_option('avanik_phase_186_final_decision_review_audit_integrity_gate',[]); $g=is_array($g)?$g:[];
        $valid=($g['gate_status']??'')==='open_for_final_decision_review' && ($g['integrity_status']??'')==='verified' && ($g['review_decision']??'')==='pending_review' && ($g['guard_release']??true)===false && ($g['execution_allowed']??true)===false;
        $r=['verification_status'=>$valid?'verified':'failed','gate_status'=>(string)($g['gate_status']??'unknown'),'integrity_status'=>(string)($g['integrity_status']??'failed'),'review_decision'=>(string)($g['review_decision']??'unknown'),'guard_release'=>false,'execution_allowed'=>false,'reason'=>$valid?'review_audit_integrity_gate_is_valid_and_locked':'review_audit_integrity_gate_is_invalid','verified_at'=>time()];
        update_option(self::OPTION,$r,false); return $r;
    }
    public static function render(): void { if(!current_user_can(self::CAPABILITY)) return; $s=self::evaluate(); echo '<div class="wrap"><h1>SLA Drift Guard Review Audit Integrity Verification</h1><p>Phase 187 verifies the Phase 186 integrity gate before any further review transition. No final outcome or execution is authorized.</p><table class="widefat striped"><tbody>'; foreach(['Verification status'=>strtoupper($s['verification_status']),'Gate status'=>strtoupper(str_replace('_',' ',$s['gate_status'])),'Integrity status'=>strtoupper($s['integrity_status']),'Review decision'=>strtoupper(str_replace('_',' ',$s['review_decision'])),'Guard release'=>$s['guard_release']?'YES':'NO','Execution allowed'=>$s['execution_allowed']?'YES':'NO','Reason'=>str_replace('_',' ',$s['reason']),'Verified at'=>wp_date('Y-m-d H:i:s',$s['verified_at'])] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>'; echo '</tbody></table></div>'; }
}
