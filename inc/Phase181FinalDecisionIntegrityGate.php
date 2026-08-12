<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class Phase181FinalDecisionIntegrityGate {
    private const OPTION = 'avanik_phase_181_final_decision_integrity_gate';
    private const CAPABILITY = 'manage_options';

    public static function register(): void {
        add_options_page(
            'SLA Drift Guard Final Decision Integrity Gate',
            'Final Decision Integrity Gate',
            self::CAPABILITY,
            'avanik-phase-181-final-decision-integrity-gate',
            [self::class, 'render']
        );
    }

    public static function evaluate(): array {
        $audit = get_option('avanik_sla_drift_guard_final_decision_audit', []);
        $audit = is_array($audit) ? $audit : [];

        $integrity = ($audit['audit_status'] ?? '') === 'verified'
            && ($audit['source_verification'] ?? '') === 'verified'
            && ($audit['decision_status'] ?? '') === 'ready_for_final_decision'
            && ($audit['decision'] ?? '') === 'pending_final_decision'
            && ($audit['guard_release'] ?? true) === false
            && ($audit['execution_allowed'] ?? true) === false;

        $result = [
            'gate_status' => $integrity ? 'open_for_final_decision_review' : 'blocked',
            'integrity_status' => $integrity ? 'verified' : 'failed',
            'decision' => 'pending_final_decision',
            'guard_release' => false,
            'execution_allowed' => false,
            'reason' => $integrity
                ? 'final_decision_integrity_verified_gate_open_only_for_review'
                : 'final_decision_integrity_verification_failed_gate_blocked',
            'evaluated_at' => time(),
        ];

        update_option(self::OPTION, $result, false);
        return $result;
    }

    public static function render(): void {
        if (!current_user_can(self::CAPABILITY)) {
            return;
        }

        $s = self::evaluate();
        echo '<div class="wrap"><h1>SLA Drift Guard Final Decision Integrity Gate</h1>';
        echo '<p>Phase 181 opens only the review gate after integrity verification. It does not approve, release, or execute anything.</p>';
        echo '<table class="widefat striped"><tbody>';

        $rows = [
            'Gate status' => strtoupper(str_replace('_', ' ', $s['gate_status'])),
            'Integrity status' => strtoupper($s['integrity_status']),
            'Decision' => strtoupper(str_replace('_', ' ', $s['decision'])),
            'Guard release' => $s['guard_release'] ? 'YES' : 'NO',
            'Execution allowed' => $s['execution_allowed'] ? 'YES' : 'NO',
            'Reason' => str_replace('_', ' ', $s['reason']),
            'Evaluated at' => wp_date('Y-m-d H:i:s', $s['evaluated_at']),
        ];

        foreach ($rows as $k => $v) {
            echo '<tr><th>' . esc_html($k) . '</th><td>' . esc_html((string) $v) . '</td></tr>';
        }

        echo '</tbody></table></div>';
    }
}
