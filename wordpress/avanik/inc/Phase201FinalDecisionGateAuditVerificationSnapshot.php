<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class Phase201FinalDecisionGateAuditVerificationSnapshot {
    private const OPTION='avanik_phase_201_final_decision_gate_audit_verification_snapshot';
    private const CAPABILITY='manage_options';

    public static function register(): void {
        add_options_page('SLA Drift Guard Final Decision Gate Audit Verification Snapshot','Final Decision Gate Audit Verification Snapshot',self::CAPABILITY,'avanik-phase-201-final-decision-gate-audit-verification-snapshot',[self::class,'render']);
    }

    public static function evaluate(): array {
        $v=get_option('avanik_phase_200_final_decision_integrity_gate_verification_snapshot_audit_verification',[]);
        $v=is_array($v)?$v:[];
        $valid=($v['verification_status']??'')==='verified'
            && ($v['audit_status']??'')==='verified'
            && ($v['snapshot_status']??'')==='verified'
            && ($v['verification_chain_status']??'')==='verified'
            && ($v['gate_status']??'')==='open_for_final_decision_review'
            && ($v['integrity_status']??'')==='verified'
            && ($v['review_decision']??'')==='pending_review'
            && ($v['guard_release']??true)===false
            && ($v['execution_allowed']??true)===false;
        $r=[
            'snapshot_status'=>$valid?'verified':'blocked',
            'source_verification_status'=>(string)($v['verification_status']??'unknown'),
            'audit_status'=>(string)($v['audit_status']??'unknown'),
            'verification_chain_status'=>(string)($v['verification_chain_status']??'unknown'),
            'gate_status'=>(string)($v['gate_status']??'unknown'),
            'integrity_status'=>(string)($v['integrity_status']??'failed'),
            'review_decision'=>'pending_review',
            'guard_release'=>false,
            'execution_allowed'=>false,
            'event'=>'final_decision_gate_audit_verification_snapshot_created',
            'reason'=>$valid?'verified_final_decision_gate_audit_verification_snapshot_locked':'final_decision_gate_audit_verification_snapshot_blocked',
            'snapshotted_at'=>time(),
        ];
        update_option(self::OPTION,$r,false);
        return $r;
    }

    public static function render(): void {
        if(!current_user_can(self::CAPABILITY)) return;
        $s=self::evaluate();
        echo '<div class="wrap"><h1>SLA Drift Guard Final Decision Gate Audit Verification Snapshot</h1><p>Phase 201 creates a stable snapshot of the verified Phase 200 audit-verification chain while keeping final decision and execution locked.</p><table class="widefat striped"><tbody>';
        foreach([
            'Snapshot status'=>strtoupper($s['snapshot_status']),
            'Source verification status'=>strtoupper($s['source_verification_status']),
            'Audit status'=>strtoupper($s['audit_status']),
            'Verification chain status'=>strtoupper($s['verification_chain_status']),
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
