<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class Phase182FinalDecisionReviewState {
    private const OPTION = 'avanik_phase_182_final_decision_review_state';
    private const CAPABILITY = 'manage_options';

    public static function register(): void {
        add_options_page('SLA Drift Guard Final Decision Review','Final Decision Review',self::CAPABILITY,'avanik-phase-182-final-decision-review',[self::class,'render']);
    }

    public static function evaluate(): array {
        $gate = get_option('avanik_phase_181_final_decision_integrity_gate', []);
        $gate = is_array($gate) ? $gate : [];
        $ready = ($gate['gate_status'] ?? '') === 'open_for_final_decision_review'
            && ($gate['integrity_status'] ?? '') === 'verified';
        $result = [
            'review_status' => $ready ? 'review_open' : 'blocked',
            'review_decision' => 'pending_review',
            'integrity_status' => $ready ? 'verified' : 'failed',
            'guard_release' => false,
            'execution_allowed' => false,
            'reason' => $ready ? 'integrity_gate_verified_review_state_opened_without_final_outcome' : 'integrity_gate_not_verified_review_blocked',
            'opened_at' => time(),
        ];
        update_option(self::OPTION, $result, false);
        return $result;
    }

    public static function render(): void {
        if (!current_user_can(self::CAPABILITY)) return;
        $s = self::evaluate();
        echo '<div class="wrap"><h1>SLA Drift Guard Final Decision Review</h1><p>Phase 182 opens the review state only after Phase 181 integrity-gate verification. No final outcome is recorded.</p><table class="widefat striped"><tbody>';
        foreach ([
            'Review status'=>strtoupper(str_replace('_',' ',$s['review_status'])),
            'Review decision'=>strtoupper(str_replace('_',' ',$s['review_decision'])),
            'Integrity status'=>strtoupper($s['integrity_status']),
            'Guard release'=>$s['guard_release']?'YES':'NO',
            'Execution allowed'=>$s['execution_allowed']?'YES':'NO',
            'Reason'=>str_replace('_',' ',$s['reason']),
            'Opened at'=>wp_date('Y-m-d H:i:s',$s['opened_at']),
        ] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
