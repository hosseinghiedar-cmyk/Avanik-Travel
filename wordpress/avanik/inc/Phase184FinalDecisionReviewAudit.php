<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class Phase184FinalDecisionReviewAudit {
    private const OPTION='avanik_phase_184_final_decision_review_audit';
    private const CAPABILITY='manage_options';
    public static function register(): void { add_options_page('SLA Drift Guard Final Decision Review Audit','Final Decision Review Audit',self::CAPABILITY,'avanik-phase-184-final-decision-review-audit',[self::class,'render']); }
    public static function evaluate(): array {
        $v=get_option('avanik_phase_183_final_decision_review_verification',[]); $v=is_array($v)?$v:[];
        $valid=($v['verification_status']??'')==='verified' && ($v['review_status']??'')==='review_open' && ($v['review_decision']??'')==='pending_review' && ($v['integrity_status']??'')==='verified' && ($v['guard_release']??true)===false && ($v['execution_allowed']??true)===false;
        $result=['audit_status'=>$valid?'verified':'blocked','verification_status'=>(string)($v['verification_status']??'failed'),'review_status'=>(string)($v['review_status']??'unknown'),'review_decision'=>(string)($v['review_decision']??'unknown'),'integrity_status'=>(string)($v['integrity_status']??'failed'),'event'=>'final_decision_review_verification_audited','guard_release'=>false,'execution_allowed'=>false,'reason'=>$valid?'final_decision_review_verification_is_auditable_and_locked':'final_decision_review_verification_not_valid_for_audit','audited_at'=>time()];
        update_option(self::OPTION,$result,false); return $result;
    }
    public static function render(): void { if(!current_user_can(self::CAPABILITY)) return; $s=self::evaluate(); echo '<div class="wrap"><h1>SLA Drift Guard Final Decision Review Audit</h1><p>Phase 184 audits the verified review state without recording an outcome or enabling execution.</p><table class="widefat striped"><tbody>'; foreach(['Audit status'=>strtoupper($s['audit_status']),'Verification status'=>strtoupper($s['verification_status']),'Review status'=>strtoupper(str_replace('_',' ',$s['review_status'])),'Review decision'=>strtoupper(str_replace('_',' ',$s['review_decision'])),'Integrity status'=>strtoupper($s['integrity_status']),'Event'=>str_replace('_',' ',$s['event']),'Guard release'=>$s['guard_release']?'YES':'NO','Execution allowed'=>$s['execution_allowed']?'YES':'NO','Reason'=>str_replace('_',' ',$s['reason']),'Audited at'=>wp_date('Y-m-d H:i:s',$s['audited_at'])] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>'; echo '</tbody></table></div>'; }
}
