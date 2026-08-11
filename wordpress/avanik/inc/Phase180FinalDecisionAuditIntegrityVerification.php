<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class Phase180FinalDecisionAuditIntegrityVerification {
    private const OPTION = 'avanik_sla_drift_guard_final_decision_audit_integrity_verification';

    public static function evaluate(): array {
        $s = get_option('avanik_sla_drift_guard_release_final_decision_audit', []);
        $s = is_array($s) ? $s : [];
        $valid = ($s['audit_status'] ?? '') === 'verified'
            && ($s['source_verification'] ?? '') === 'verified'
            && ($s['decision_status'] ?? '') === 'ready_for_final_decision'
            && ($s['decision'] ?? '') === 'pending_final_decision'
            && ($s['guard_release'] ?? true) === false
            && ($s['execution_allowed'] ?? true) === false;

        $result = [
            'verification_status' => $valid ? 'verified' : 'failed',
            'audit_status' => (string) ($s['audit_status'] ?? 'unknown'),
            'decision_status' => (string) ($s['decision_status'] ?? 'unknown'),
            'decision' => (string) ($s['decision'] ?? 'unknown'),
            'guard_release' => false,
            'execution_allowed' => false,
            'reason' => $valid ? 'final_decision_audit_integrity_is_valid_and_locked' : 'final_decision_audit_integrity_check_failed',
            'verified_at' => time(),
        ];
        update_option(self::OPTION, $result, false);
        return $result;
    }

    public static function register(): void {
        add_options_page(
            'SLA Drift Guard Integrity Verification',
            'SLA Drift Guard Integrity Verification',
            'manage_options',
            'avanik-sla-drift-guard-integrity-verification',
            [self::class, 'render']
        );
    }

    public static function render(): void {
        if (!current_user_can('manage_options')) return;
        $s = self::evaluate();
        echo '<div class="wrap"><h1>SLA Drift Guard Integrity Verification</h1>';
        echo '<p>Phase 180 verifies the integrity of the Phase 179 final-decision audit snapshot.</p>';
        echo '<table class="widefat striped"><tbody>';
        foreach ([
            'Verification status' => strtoupper($s['verification_status']),
            'Audit status' => strtoupper($s['audit_status']),
            'Decision status' => strtoupper(str_replace('_', ' ', $s['decision_status'])),
            'Decision' => strtoupper(str_replace('_', ' ', $s['decision'])),
            'Guard release' => $s['guard_release'] ? 'YES' : 'NO',
            'Execution allowed' => $s['execution_allowed'] ? 'YES' : 'NO',
            'Reason' => str_replace('_', ' ', $s['reason']),
            'Verified at' => wp_date('Y-m-d H:i:s', $s['verified_at']),
        ] as $k => $v) {
            echo '<tr><th>' . esc_html($k) . '</th><td>' . esc_html((string) $v) . '</td></tr>';
        }
        echo '</tbody></table></div>';
    }
}
