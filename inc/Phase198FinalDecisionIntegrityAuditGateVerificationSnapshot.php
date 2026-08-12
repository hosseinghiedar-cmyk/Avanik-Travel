<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class Phase198FinalDecisionIntegrityAuditGateVerificationSnapshot {
    private const OPTION='avanik_phase_198_final_decision_integrity_audit_gate_verification_snapshot';
    private const CAPABILITY='manage_options';

    public static function register(): void {
        add_options_page('SLA Drift Guard Final Decision Integrity Audit Gate Verification Snapshot','Final Decision Integrity Gate Verification Snapshot',self::CAPABILITY,'avanik-phase-198-final-decision-integrity-gate-verification-snapshot',[self::class,'render']);
    }

    public static function evaluate(): array {
        $v=get_option('avanik_phase_197_final_decision_integrity_audit_gate_snapshot_verification',[]);
        $v=is_array($v)?$v:[];
        $valid=($v['verification_status']??'')==='verified'
            && ($v['snapshot_status']??'')==='verified'
            && ($v['gate_status']??'')==='open_for_final_decision_review'
            && ($v['integrity_status']??'')==='verified'
            && ($v['review_decision']??'')==='pending_review'
            && ($v['guard_release']??true)===false
            && ($v['execution_allowed']??true)===false;
        $r=[
            'snapshot_status'=>$valid?'verified':'blocked',
            'verification_status'=>(string)($v['verification_status']??'unknown'),
            'source_snapshot_status'=>(string)($v['snapshot_status']??'unknown'),
            'gate_status'=>(string)($v['gate_status']??'unknown'),
            'integrity_status'=>(string)($v['integrity_status']??'failed'),
            'review_decision'=>'pending_review',
            'guard_release'=>false,
            'execution_allowed'=>false,
            'event'=>'final_decision_integrity_gate_verification_snapshot_created',
            'reason'=>$valid?'verified_gate_verification_snapshot_locked':'gate_verification_snapshot_blocked',
            'snapshotted_at'=>time(),
        ];
        update_option(self::OPTION,$r,false);
        return $r;
    }

    public static function render(): void {
        if(!current_user_can(self::CAPABILITY)) return;
        $s=self::evaluate();
        echo '<div class="wrap"><h1>SLA Drift Guard Final Decision Integrity Audit Gate Verification Snapshot</h1><p>Phase 198 snapshots the verified Phase 197 gate verification while keeping final decision and execution locked.</p><table class="widefat striped"><tbody>';
        foreach([
            'Snapshot status'=>strtoupper($s['snapshot_status']),
            'Verification status'=>strtoupper($s['verification_status']),
            'Source snapshot status'=>strtoupper($s['source_snapshot_status']),
            'Gate status'=>strtoupper(str_replace('_',' ',$s['gate_status'])),
            'Integrity status'=>strtoupper($s['integrity_status']),
            'Review decision'=>strtoupper(str_replace('_',' ',$s['review_decision'])),
            'Guard release'=>$s['guard_release']?'YES':'NO',
            'Execution allowed'=>$s['execution_allowed']?'YES':'NO',
            'Event'=>str_replace('_',' ',$s['event']),
            'Reason'=>str_replace('_',' ',$s['reason']),
            'Snapshotted at'=>wp_date('Y-m-d H:i:s',$s['snapshotted_at']),
        ] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
