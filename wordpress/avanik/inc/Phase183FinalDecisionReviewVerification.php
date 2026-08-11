<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class Phase183FinalDecisionReviewVerification {
    private const OPTION='avanik_phase_183_final_decision_review_verification';
    private const CAPABILITY='manage_options';
    public static function register(): void { add_options_page('SLA Drift Guard Final Decision Review Verification','Final Decision Review Verification',self::CAPABILITY,'avanik-phase-183-final-decision-review-verification',[self::class,'render']); }
    public static function evaluate(): array {
        $s=get_option('avanik_phase_182_final_decision_review_state',[]); $s=is_array($s)?$s:[];
        $valid=($s['review_status']??'')==='review_open' && ($s['review_decision']??'')==='pending_review' && ($s['integrity_status']??'')==='verified' && ($s['guard_release']??true)===false && ($s['execution_allowed']??true)===false;
        $result=['verification_status'=>$valid?'verified':'failed','review_status'=>(string)($s['review_status']??'unknown'),'review_decision'=>(string)($s['review_decision']??'unknown'),'integrity_status'=>(string)($s['integrity_status']??'failed'),'guard_release'=>false,'execution_allowed'=>false,'reason'=>$valid?'final_decision_review_state_is_valid_and_locked':'final_decision_review_state_is_invalid','verified_at'=>time()];
        update_option(self::OPTION,$result,false); return $result;
    }
    public static function render(): void { if(!current_user_can(self::CAPABILITY)) return; $s=self::evaluate(); echo '<div class="wrap"><h1>SLA Drift Guard Final Decision Review Verification</h1><p>Phase 183 verifies the Phase 182 review state without recording a final outcome or enabling execution.</p><table class="widefat striped"><tbody>'; foreach(['Verification status'=>strtoupper($s['verification_status']),'Review status'=>strtoupper(str_replace('_',' ',$s['review_status'])),'Review decision'=>strtoupper(str_replace('_',' ',$s['review_decision'])),'Integrity status'=>strtoupper($s['integrity_status']),'Guard release'=>$s['guard_release']?'YES':'NO','Execution allowed'=>$s['execution_allowed']?'YES':'NO','Reason'=>str_replace('_',' ',$s['reason']),'Verified at'=>wp_date('Y-m-d H:i:s',$s['verified_at'])] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>'; echo '</tbody></table></div>'; }
}
