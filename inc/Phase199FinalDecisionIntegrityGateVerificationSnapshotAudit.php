<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class Phase199FinalDecisionIntegrityGateVerificationSnapshotAudit {
    private const OPTION='avanik_phase_199_final_decision_integrity_gate_verification_snapshot_audit';
    private const CAPABILITY='manage_options';

    public static function register(): void {
        add_options_page('SLA Drift Guard Final Decision Integrity Gate Verification Snapshot Audit','Final Decision Gate Verification Snapshot Audit',self::CAPABILITY,'avanik-phase-199-final-decision-gate-verification-snapshot-audit',[self::class,'render']);
    }

    public static function evaluate(): array {
        $s=get_option('avanik_phase_198_final_decision_integrity_audit_gate_verification_snapshot',[]);
        $s=is_array($s)?$s:[];
        $valid=($s['snapshot_status']??'')==='verified'
            && ($s['verification_status']??'')==='verified'
            && ($s['source_snapshot_status']??'')==='verified'
            && ($s['gate_status']??'')==='open_for_final_decision_review'
            && ($s['integrity_status']??'')==='verified'
            && ($s['review_decision']??'')==='pending_review'
            && ($s['guard_release']??true)===false
            && ($s['execution_allowed']??true)===false;
        $r=[
            'audit_status'=>$valid?'verified':'failed',
            'snapshot_status'=>(string)($s['snapshot_status']??'unknown'),
            'verification_status'=>(string)($s['verification_status']??'unknown'),
            'source_snapshot_status'=>(string)($s['source_snapshot_status']??'unknown'),
            'gate_status'=>(string)($s['gate_status']??'unknown'),
            'integrity_status'=>(string)($s['integrity_status']??'failed'),
            'review_decision'=>'pending_review',
            'guard_release'=>false,
            'execution_allowed'=>false,
            'event'=>'final_decision_integrity_gate_verification_snapshot_audited',
            'reason'=>$valid?'verified_gate_verification_snapshot_audit_locked':'gate_verification_snapshot_audit_failed',
            'audited_at'=>time(),
        ];
        update_option(self::OPTION,$r,false);
        return $r;
    }

    public static function render(): void {
        if(!current_user_can(self::CAPABILITY)) return;
        $s=self::evaluate();
        echo '<div class="wrap"><h1>SLA Drift Guard Final Decision Integrity Gate Verification Snapshot Audit</h1><p>Phase 199 audits the verified Phase 198 verification snapshot while keeping final decision and execution locked.</p><table class="widefat striped"><tbody>';
        foreach([
            'Audit status'=>strtoupper($s['audit_status']),
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
            'Audited at'=>wp_date('Y-m-d H:i:s',$s['audited_at']),
        ] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
