<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class Phase192FinalDecisionReviewIntegrityAuditSnapshot {
    private const OPTION='avanik_phase_192_final_decision_review_integrity_audit_snapshot';
    private const CAPABILITY='manage_options';

    public static function register(): void {
        add_options_page('SLA Drift Guard Review Integrity Audit Snapshot','Review Integrity Audit Snapshot',self::CAPABILITY,'avanik-phase-192-review-integrity-audit-snapshot',[self::class,'render']);
    }

    public static function evaluate(): array {
        $v=get_option('avanik_phase_191_final_decision_review_integrity_audit_verification',[]);
        $v=is_array($v)?$v:[];
        $valid=($v['verification_status']??'')==='verified'
            && ($v['audit_status']??'')==='verified'
            && ($v['snapshot_status']??'')==='verified'
            && ($v['gate_status']??'')==='open_for_final_decision_review'
            && ($v['integrity_status']??'')==='verified'
            && ($v['review_decision']??'')==='pending_review'
            && ($v['guard_release']??true)===false
            && ($v['execution_allowed']??true)===false;
        $result=[
            'snapshot_status'=>$valid?'verified':'failed',
            'verification_status'=>(string)($v['verification_status']??'unknown'),
            'audit_status'=>(string)($v['audit_status']??'unknown'),
            'gate_status'=>(string)($v['gate_status']??'unknown'),
            'integrity_status'=>(string)($v['integrity_status']??'failed'),
            'review_decision'=>'pending_review',
            'guard_release'=>false,
            'execution_allowed'=>false,
            'event'=>'final_decision_review_integrity_audit_snapshot_created',
            'reason'=>$valid?'review_integrity_audit_chain_snapshot_verified_and_locked':'review_integrity_audit_chain_snapshot_blocked',
            'snapshot_at'=>time(),
        ];
        update_option(self::OPTION,$result,false);
        return $result;
    }

    public static function render(): void {
        if(!current_user_can(self::CAPABILITY)) return;
        $s=self::evaluate();
        echo '<div class="wrap"><h1>SLA Drift Guard Review Integrity Audit Snapshot</h1><p>Phase 192 creates a stable snapshot of the verified review-integrity audit chain. It does not authorize a final outcome or execution.</p><table class="widefat striped"><tbody>';
        foreach([
            'Snapshot status'=>strtoupper($s['snapshot_status']),
            'Verification status'=>strtoupper($s['verification_status']),
            'Audit status'=>strtoupper($s['audit_status']),
            'Gate status'=>strtoupper(str_replace('_',' ',$s['gate_status'])),
            'Integrity status'=>strtoupper($s['integrity_status']),
            'Review decision'=>strtoupper(str_replace('_',' ',$s['review_decision'])),
            'Guard release'=>$s['guard_release']?'YES':'NO',
            'Execution allowed'=>$s['execution_allowed']?'YES':'NO',
            'Event'=>str_replace('_',' ',$s['event']),
            'Reason'=>str_replace('_',' ',$s['reason']),
            'Snapshot at'=>wp_date('Y-m-d H:i:s',$s['snapshot_at']),
        ] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
