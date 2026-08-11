<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class Phase197FinalDecisionIntegrityAuditGateSnapshotVerification {
    private const OPTION='avanik_phase_197_final_decision_integrity_audit_gate_snapshot_verification';
    private const CAPABILITY='manage_options';

    public static function register(): void {
        add_options_page('SLA Drift Guard Final Decision Integrity Audit Gate Snapshot Verification','Final Decision Integrity Audit Gate Snapshot Verification',self::CAPABILITY,'avanik-phase-197-final-decision-integrity-audit-gate-snapshot-verification',[self::class,'render']);
    }

    public static function evaluate(): array {
        $s=get_option('avanik_phase_196_final_decision_integrity_audit_gate_snapshot',[]);
        $s=is_array($s)?$s:[];
        $valid=($s['snapshot_status']??'')==='verified'
            && ($s['verification_status']??'')==='verified'
            && ($s['gate_status']??'')==='open_for_final_decision_review'
            && ($s['integrity_status']??'')==='verified'
            && ($s['review_decision']??'')==='pending_review'
            && ($s['guard_release']??true)===false
            && ($s['execution_allowed']??true)===false;
        $r=[
            'verification_status'=>$valid?'verified':'failed',
            'snapshot_status'=>(string)($s['snapshot_status']??'unknown'),
            'gate_status'=>(string)($s['gate_status']??'unknown'),
            'integrity_status'=>(string)($s['integrity_status']??'failed'),
            'review_decision'=>'pending_review',
            'guard_release'=>false,
            'execution_allowed'=>false,
            'event'=>'final_decision_integrity_audit_gate_snapshot_verified',
            'reason'=>$valid?'final_decision_integrity_audit_gate_snapshot_is_verified_and_locked':'final_decision_integrity_audit_gate_snapshot_verification_failed',
            'verified_at'=>time(),
        ];
        update_option(self::OPTION,$r,false);
        return $r;
    }

    public static function render(): void {
        if(!current_user_can(self::CAPABILITY)) return;
        $s=self::evaluate();
        echo '<div class="wrap"><h1>SLA Drift Guard Final Decision Integrity Audit Gate Snapshot Verification</h1><p>Phase 197 verifies the Phase 196 snapshot without recording a final outcome or enabling execution.</p><table class="widefat striped"><tbody>';
        foreach([
            'Verification status'=>strtoupper($s['verification_status']),
            'Snapshot status'=>strtoupper($s['snapshot_status']),
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
