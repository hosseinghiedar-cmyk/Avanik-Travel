<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class Phase185FinalDecisionReviewAuditVerification {
    private const OPTION='avanik_phase_185_final_decision_review_audit_verification';
    private const CAPABILITY='manage_options';
    public static function register(): void { add_options_page('SLA Drift Guard Final Decision Review Audit Verification','Final Decision Review Audit Verification',self::CAPABILITY,'avanik-phase-185-final-decision-review-audit-verification',[self::class,'render']); }
    public static function evaluate(): array {
        $a=get_option('avanik_phase_184_final_decision_review_audit',[]); $a=is_array($a)?$a:[];
        $valid=($a['audit_status']??'')==='verified' && ($a['verification_status']??'')==='verified' && ($a['review_status']??'')==='review_open' && ($a['review_decision']??'')==='pending_review' && ($a['integrity_status']??'')==='verified' && ($a['guard_release']??true)===false && ($a['execution_allowed']??true)===false;
        $r=['verification_status'=>$valid?'verified':'failed','audit_status'=>(string)($a['audit_status']??'unknown'),'review_status'=>(string)($a['review_status']??'unknown'),'review_decision'=>(string)($a['review_decision']??'unknown'),'integrity_status'=>(string)($a['integrity_status']??'failed'),'guard_release'=>false,'execution_allowed'=>false,'reason'=>$valid?'final_decision_review_audit_is_valid_and_locked':'final_decision_review_audit_is_invalid','verified_at'=>time()];
        update_option(self::OPTION,$r,false); return $r;
    }
    public static function render(): void { if(!current_user_can(self::CAPABILITY)) return; $s=self::evaluate(); echo '<div class="wrap"><h1>SLA Drift Guard Final Decision Review Audit Verification</h1><p>Phase 185 verifies the audit snapshot produced by Phase 184 without recording a final outcome or enabling execution.</p><table class="widefat striped"><tbody>'; foreach(['Verification status'=>strtoupper($s['verification_status']),'Audit status'=>strtoupper($s['audit_status']),'Review status'=>strtoupper(str_replace('_',' ',$s['review_status'])),'Review decision'=>strtoupper(str_replace('_',' ',$s['review_decision'])),'Integrity status'=>strtoupper($s['integrity_status']),'Guard release'=>$s['guard_release']?'YES':'NO','Execution allowed'=>$s['execution_allowed']?'YES':'NO','Reason'=>str_replace('_',' ',$s['reason']),'Verified at'=>wp_date('Y-m-d H:i:s',$s['verified_at'])] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>'; echo '</tbody></table></div>'; }
}
