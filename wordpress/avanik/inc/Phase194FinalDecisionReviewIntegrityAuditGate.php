<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class Phase194FinalDecisionReviewIntegrityAuditGate {
    private const OPTION='avanik_phase_194_final_decision_review_integrity_audit_gate';
    private const CAPABILITY='manage_options';

    public static function register(): void {
        add_options_page('SLA Drift Guard Final Decision Integrity Audit Gate','Final Decision Integrity Audit Gate',self::CAPABILITY,'avanik-phase-194-final-decision-integrity-audit-gate',[self::class,'render']);
    }

    public static function evaluate(): array {
        $v=get_option('avanik_phase_193_final_decision_review_integrity_snapshot_audit_verification',[]);
        $v=is_array($v)?$v:[];
        $valid=($v['verification_status']??'')==='verified'
            && ($v['snapshot_status']??'')==='verified'
            && ($v['audit_status']??'')==='verified'
            && ($v['gate_status']??'')==='open_for_final_decision_review'
            && ($v['integrity_status']??'')==='verified'
            && ($v['review_decision']??'')==='pending_review'
            && ($v['guard_release']??true)===false
            && ($v['execution_allowed']??true)===false;

        $result=[
            'gate_status'=>$valid?'open_for_final_decision_review':'blocked',
            'integrity_status'=>$valid?'verified':'failed',
            'review_decision'=>'pending_review',
            'guard_release'=>false,
            'execution_allowed'=>false,
            'event'=>'final_decision_review_integrity_audit_gate_evaluated',
            'reason'=>$valid?'review_integrity_audit_chain_verified_gate_open_for_review_only':'review_integrity_audit_chain_failed_gate_blocked',
            'evaluated_at'=>time(),
        ];
        update_option(self::OPTION,$result,false);
        return $result;
    }

    public static function render(): void {
        if(!current_user_can(self::CAPABILITY)) return;
        $s=self::evaluate();
        echo '<div class="wrap"><h1>SLA Drift Guard Final Decision Integrity Audit Gate</h1><p>Phase 194 evaluates the verified review-integrity audit chain and keeps the final outcome and execution locked.</p><table class="widefat striped"><tbody>';
        foreach([
            'Gate status'=>strtoupper(str_replace('_',' ',$s['gate_status'])),
            'Integrity status'=>strtoupper($s['integrity_status']),
            'Review decision'=>strtoupper(str_replace('_',' ',$s['review_decision'])),
            'Guard release'=>$s['guard_release']?'YES':'NO',
            'Execution allowed'=>$s['execution_allowed']?'YES':'NO',
            'Event'=>str_replace('_',' ',$s['event']),
            'Reason'=>str_replace('_',' ',$s['reason']),
            'Evaluated at'=>wp_date('Y-m-d H:i:s',$s['evaluated_at']),
        ] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
