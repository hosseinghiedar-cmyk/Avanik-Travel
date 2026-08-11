<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class Phase189FinalDecisionReviewIntegritySnapshotVerification {
    private const OPTION='avanik_phase_189_final_decision_review_integrity_snapshot_verification';
    private const CAPABILITY='manage_options';

    public static function register(): void {
        add_options_page('SLA Drift Guard Review Integrity Snapshot Verification','Review Integrity Snapshot Verification',self::CAPABILITY,'avanik-phase-189-review-integrity-snapshot-verification',[self::class,'render']);
    }

    public static function evaluate(): array {
        $s=get_option('avanik_phase_188_final_decision_review_audit_integrity_snapshot',[]);
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
            'review_decision'=>(string)($s['review_decision']??'unknown'),
            'guard_release'=>false,
            'execution_allowed'=>false,
            'reason'=>$valid?'review_integrity_snapshot_is_valid_and_locked':'review_integrity_snapshot_is_invalid',
            'verified_at'=>time(),
        ];
        update_option(self::OPTION,$r,false);
        return $r;
    }

    public static function render(): void {
        if(!current_user_can(self::CAPABILITY)) return;
        $s=self::evaluate();
        echo '<div class="wrap"><h1>SLA Drift Guard Review Integrity Snapshot Verification</h1><p>Phase 189 verifies the Phase 188 integrity snapshot without recording a final outcome or enabling execution.</p><table class="widefat striped"><tbody>';
        foreach([
            'Verification status'=>strtoupper($s['verification_status']),
            'Snapshot status'=>strtoupper($s['snapshot_status']),
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
