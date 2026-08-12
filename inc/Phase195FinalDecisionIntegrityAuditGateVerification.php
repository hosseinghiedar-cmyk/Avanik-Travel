<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class Phase195FinalDecisionIntegrityAuditGateVerification {
    private const OPTION='avanik_phase_195_final_decision_integrity_audit_gate_verification';
    private const CAPABILITY='manage_options';

    public static function register(): void {
        add_options_page('SLA Drift Guard Final Decision Integrity Audit Gate Verification','Final Decision Integrity Audit Gate Verification',self::CAPABILITY,'avanik-phase-195-final-decision-integrity-audit-gate-verification',[self::class,'render']);
    }

    public static function evaluate(): array {
        $g=get_option('avanik_phase_194_final_decision_review_integrity_audit_gate',[]);
        $g=is_array($g)?$g:[];
        $valid=($g['gate_status']??'')==='open_for_final_decision_review'
            && ($g['integrity_status']??'')==='verified'
            && ($g['review_decision']??'')==='pending_review'
            && ($g['guard_release']??true)===false
            && ($g['execution_allowed']??true)===false;
        $result=[
            'verification_status'=>$valid?'verified':'failed',
            'gate_status'=>(string)($g['gate_status']??'unknown'),
            'integrity_status'=>(string)($g['integrity_status']??'failed'),
            'review_decision'=>'pending_review',
            'guard_release'=>false,
            'execution_allowed'=>false,
            'event'=>'final_decision_integrity_audit_gate_verified',
            'reason'=>$valid?'final_decision_integrity_audit_gate_is_verified_and_locked':'final_decision_integrity_audit_gate_verification_failed',
            'verified_at'=>time(),
        ];
        update_option(self::OPTION,$result,false);
        return $result;
    }

    public static function render(): void {
        if(!current_user_can(self::CAPABILITY)) return;
        $s=self::evaluate();
        echo '<div class="wrap"><h1>SLA Drift Guard Final Decision Integrity Audit Gate Verification</h1><p>Phase 195 verifies the Phase 194 integrity-audit gate while keeping the final outcome and execution locked.</p><table class="widefat striped"><tbody>';
        foreach([
            'Verification status'=>strtoupper($s['verification_status']),
            'Gate status'=>strtoupper(str_replace('_',' ',$s['gate_status'])),
            'Integrity status'=>strtoupper($s['integrity_status']),
            'Review decision'=>strtoupper(str_replace('_',' ',$s['review_decision'])),
            'Guard release'=>$s['guard_release']?'YES':'NO',
            'Execution allowed'=>$s['execution_allowed']?'YES':'NO',
            'Event'=>str_replace('_',' ',$s['event']),
            'Reason'=>str_replace('_',' ',$s['reason']),
            'Verified at'=>wp_date('Y-m-d H:i:s',$s['verified_at']),
        ] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
