<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class Phase200FinalDecisionIntegrityGateVerificationSnapshotAuditVerification {
    private const OPTION='avanik_phase_200_final_decision_integrity_gate_verification_snapshot_audit_verification';
    private const CAPABILITY='manage_options';

    public static function register(): void {
        add_options_page('SLA Drift Guard Final Decision Gate Audit Verification','Final Decision Gate Audit Verification',self::CAPABILITY,'avanik-phase-200-final-decision-gate-audit-verification',[self::class,'render']);
    }

    public static function evaluate(): array {
        $a=get_option('avanik_phase_199_final_decision_integrity_gate_verification_snapshot_audit',[]);
        $a=is_array($a)?$a:[];
        $valid=($a['audit_status']??'')==='verified'
            && ($a['snapshot_status']??'')==='verified'
            && ($a['verification_status']??'')==='verified'
            && ($a['source_snapshot_status']??'')==='verified'
            && ($a['gate_status']??'')==='open_for_final_decision_review'
            && ($a['integrity_status']??'')==='verified'
            && ($a['review_decision']??'')==='pending_review'
            && ($a['guard_release']??true)===false
            && ($a['execution_allowed']??true)===false;
        $r=[
            'verification_status'=>$valid?'verified':'failed',
            'audit_status'=>(string)($a['audit_status']??'unknown'),
            'snapshot_status'=>(string)($a['snapshot_status']??'unknown'),
            'verification_chain_status'=>(string)($a['verification_status']??'unknown'),
            'gate_status'=>(string)($a['gate_status']??'unknown'),
            'integrity_status'=>(string)($a['integrity_status']??'failed'),
            'review_decision'=>'pending_review',
            'guard_release'=>false,
            'execution_allowed'=>false,
            'event'=>'final_decision_integrity_gate_verification_snapshot_audit_verified',
            'reason'=>$valid?'final_decision_integrity_gate_audit_chain_is_verified_and_locked':'final_decision_integrity_gate_audit_chain_verification_failed',
            'verified_at'=>time(),
        ];
        update_option(self::OPTION,$r,false);
        return $r;
    }

    public static function render(): void {
        if(!current_user_can(self::CAPABILITY)) return;
        $s=self::evaluate();
        echo '<div class="wrap"><h1>SLA Drift Guard Final Decision Gate Audit Verification</h1><p>Phase 200 verifies the complete Phase 199 gate-verification snapshot audit chain while keeping final decision and execution locked.</p><table class="widefat striped"><tbody>';
        foreach([
            'Verification status'=>strtoupper($s['verification_status']),
            'Audit status'=>strtoupper($s['audit_status']),
            'Snapshot status'=>strtoupper($s['snapshot_status']),
            'Verification chain status'=>strtoupper($s['verification_chain_status']),
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
