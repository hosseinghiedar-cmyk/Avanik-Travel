<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class Phase190FinalDecisionReviewIntegritySnapshotAudit {
    private const OPTION='avanik_phase_190_final_decision_review_integrity_snapshot_audit';
    private const CAPABILITY='manage_options';

    public static function register(): void {
        add_options_page('SLA Drift Guard Review Integrity Snapshot Audit','Review Integrity Snapshot Audit',self::CAPABILITY,'avanik-phase-190-review-integrity-snapshot-audit',[self::class,'render']);
    }

    public static function evaluate(): array {
        $v=get_option('avanik_phase_189_final_decision_review_integrity_snapshot_verification',[]);
        $v=is_array($v)?$v:[];
        $valid=($v['verification_status']??'')==='verified'
            && ($v['snapshot_status']??'')==='verified'
            && ($v['gate_status']??'')==='open_for_final_decision_review'
            && ($v['integrity_status']??'')==='verified'
            && ($v['review_decision']??'')==='pending_review'
            && ($v['guard_release']??true)===false
            && ($v['execution_allowed']??true)===false;
        $result=[
            'audit_status'=>$valid?'verified':'blocked',
            'verification_status'=>(string)($v['verification_status']??'unknown'),
            'snapshot_status'=>(string)($v['snapshot_status']??'unknown'),
            'gate_status'=>(string)($v['gate_status']??'unknown'),
            'integrity_status'=>(string)($v['integrity_status']??'failed'),
            'review_decision'=>'pending_review',
            'event'=>'final_decision_review_integrity_snapshot_audited',
            'guard_release'=>false,
            'execution_allowed'=>false,
            'reason'=>$valid?'review_integrity_snapshot_verified_and_audited':'review_integrity_snapshot_verification_failed',
            'audited_at'=>time(),
        ];
        update_option(self::OPTION,$result,false);
        return $result;
    }

    public static function render(): void {
        if(!current_user_can(self::CAPABILITY)) return;
        $s=self::evaluate();
        echo '<div class="wrap"><h1>SLA Drift Guard Review Integrity Snapshot Audit</h1><p>Phase 190 audits the verified integrity snapshot. It does not create a final decision or enable execution.</p><table class="widefat striped"><tbody>';
        foreach([
            'Audit status'=>strtoupper($s['audit_status']),
            'Verification status'=>strtoupper($s['verification_status']),
            'Snapshot status'=>strtoupper($s['snapshot_status']),
            'Gate status'=>strtoupper(str_replace('_',' ',$s['gate_status'])),
            'Integrity status'=>strtoupper($s['integrity_status']),
            'Review decision'=>strtoupper(str_replace('_',' ',$s['review_decision'])),
            'Event'=>str_replace('_',' ',$s['event']),
            'Guard release'=>$s['guard_release']?'YES':'NO',
            'Execution allowed'=>$s['execution_allowed']?'YES':'NO',
            'Reason'=>str_replace('_',' ',$s['reason']),
            'Audited at'=>wp_date('Y-m-d H:i:s',$s['audited_at']),
        ] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
