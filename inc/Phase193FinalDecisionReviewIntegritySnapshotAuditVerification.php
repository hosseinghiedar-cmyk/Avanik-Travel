<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class Phase193FinalDecisionReviewIntegritySnapshotAuditVerification {
    private const OPTION='avanik_phase_193_final_decision_review_integrity_snapshot_audit_verification';
    private const CAPABILITY='manage_options';

    public static function register(): void {
        add_options_page('SLA Drift Guard Review Integrity Snapshot Audit Verification','Review Integrity Snapshot Audit Verification',self::CAPABILITY,'avanik-phase-193-review-integrity-snapshot-audit-verification',[self::class,'render']);
    }

    public static function evaluate(): array {
        $snapshot=get_option('avanik_phase_192_final_decision_review_integrity_audit_snapshot',[]);
        $snapshot=is_array($snapshot)?$snapshot:[];
        $valid=($snapshot['snapshot_status']??'')==='verified'
            && ($snapshot['audit_status']??'')==='verified'
            && ($snapshot['verification_status']??'')==='verified'
            && ($snapshot['gate_status']??'')==='open_for_final_decision_review'
            && ($snapshot['integrity_status']??'')==='verified'
            && ($snapshot['review_decision']??'')==='pending_review'
            && ($snapshot['guard_release']??true)===false
            && ($snapshot['execution_allowed']??true)===false;

        $result=[
            'verification_status'=>$valid?'verified':'failed',
            'snapshot_status'=>(string)($snapshot['snapshot_status']??'unknown'),
            'audit_status'=>(string)($snapshot['audit_status']??'unknown'),
            'gate_status'=>(string)($snapshot['gate_status']??'unknown'),
            'integrity_status'=>(string)($snapshot['integrity_status']??'failed'),
            'review_decision'=>'pending_review',
            'guard_release'=>false,
            'execution_allowed'=>false,
            'reason'=>$valid?'review_integrity_snapshot_audit_chain_is_verified_and_locked':'review_integrity_snapshot_audit_chain_verification_failed',
            'verified_at'=>time(),
        ];
        update_option(self::OPTION,$result,false);
        return $result;
    }

    public static function render(): void {
        if(!current_user_can(self::CAPABILITY)) return;
        $s=self::evaluate();
        echo '<div class="wrap"><h1>SLA Drift Guard Review Integrity Snapshot Audit Verification</h1><p>Phase 193 verifies the Phase 192 snapshot and its audit chain without recording a final outcome or enabling execution.</p><table class="widefat striped"><tbody>';
        foreach([
            'Verification status'=>strtoupper($s['verification_status']),
            'Snapshot status'=>strtoupper($s['snapshot_status']),
            'Audit status'=>strtoupper($s['audit_status']),
            'Gate status'=>strtoupper(str_replace('_',' ',$s['gate_status'])),
            'Integrity status'=>strtoupper($s['integrity_status']),
            'Review decision'=>strtoupper(str_replace('_',' ',$s['review_decision'])),
            'Guard release'=>$s['guard_release']?'YES':'NO',
            'Execution allowed'=>$s['execution_allowed']?'YES':'NO',
            'Reason'=>str_replace('_',' ',$s['reason']),
            'Verified at'=>wp_date('Y-m-d H:i:s',$s['verified_at']),
        ] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
