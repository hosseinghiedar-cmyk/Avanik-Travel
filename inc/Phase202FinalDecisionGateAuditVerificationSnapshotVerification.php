<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class Phase202FinalDecisionGateAuditVerificationSnapshotVerification {
    private const OPTION='avanik_phase_202_final_decision_gate_audit_verification_snapshot_verification';
    private const CAPABILITY='manage_options';

    public static function register(): void {
        add_options_page('SLA Drift Guard Final Decision Gate Snapshot Verification','Final Decision Gate Snapshot Verification',self::CAPABILITY,'avanik-phase-202-final-decision-gate-snapshot-verification',[self::class,'render']);
    }

    public static function evaluate(): array {
        $s=get_option('avanik_phase_201_final_decision_gate_audit_verification_snapshot',[]);
        $s=is_array($s)?$s:[];
        $valid=($s['snapshot_status']??'')==='verified'
            && ($s['source_verification_status']??'')==='verified'
            && ($s['audit_status']??'')==='verified'
            && ($s['gate_status']??'')==='open_for_final_decision_review'
            && ($s['integrity_status']??'')==='verified'
            && ($s['review_decision']??'')==='pending_review'
            && ($s['guard_release']??true)===false
            && ($s['execution_allowed']??true)===false;
        $r=[
            'verification_status'=>$valid?'verified':'failed',
            'snapshot_status'=>(string)($s['snapshot_status']??'unknown'),
            'source_verification_status'=>(string)($s['source_verification_status']??'unknown'),
            'audit_status'=>(string)($s['audit_status']??'unknown'),
            'gate_status'=>(string)($s['gate_status']??'unknown'),
            'integrity_status'=>(string)($s['integrity_status']??'failed'),
            'review_decision'=>'pending_review',
            'guard_release'=>false,
            'execution_allowed'=>false,
            'event'=>'final_decision_gate_audit_verification_snapshot_verified',
            'reason'=>$valid?'final_decision_gate_audit_verification_snapshot_is_verified_and_locked':'final_decision_gate_audit_verification_snapshot_verification_failed',
            'verified_at'=>time(),
        ];
        update_option(self::OPTION,$r,false);
        return $r;
    }

    public static function render(): void {
        if(!current_user_can(self::CAPABILITY)) return;
        $s=self::evaluate();
        echo '<div class="wrap"><h1>SLA Drift Guard Final Decision Gate Snapshot Verification</h1><p>Phase 202 verifies the Phase 201 audit-verification snapshot while preserving the pending decision and execution lock.</p><table class="widefat striped"><tbody>';
        foreach([
            'Verification status'=>strtoupper($s['verification_status']),
            'Snapshot status'=>strtoupper($s['snapshot_status']),
            'Source verification status'=>strtoupper($s['source_verification_status']),
            'Audit status'=>strtoupper($s['audit_status']),
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
