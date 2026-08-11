<?php
namespace Avanik;
defined('ABSPATH') || exit;

/**
 * Phase 179 — Final Decision Audit Integrity Snapshot.
 * Records the verified integrity state from Phase 178 without changing
 * the final decision, guard-release state, or execution permission.
 */
final class Phase179FinalDecisionAuditIntegritySnapshot {
    private const OPTION = 'avanik_sla_drift_guard_final_decision_audit_integrity_snapshot';

    public static function register(): void {
        add_options_page(
            'SLA Drift Guard — Phase 179',
            'SLA Drift Guard — Phase 179',
            'manage_options',
            'avanik-sla-drift-guard-phase-179',
            [self::class, 'render']
        );
    }

    public static function evaluate(): array {
        $source = get_option(
            'avanik_sla_drift_guard_release_final_decision_audit_verification',
            []
        );
        $source = is_array($source) ? $source : [];

        $verified =
            ($source['verification_status'] ?? '') === 'verified' &&
            ($source['audit_status'] ?? '') === 'verified' &&
            ($source['decision_status'] ?? '') === 'ready_for_final_decision' &&
            ($source['decision'] ?? '') === 'pending_final_decision' &&
            ($source['guard_release'] ?? true) === false &&
            ($source['execution_allowed'] ?? true) === false;

        $snapshot = [
            'snapshot_status'    => $verified ? 'verified' : 'blocked',
            'source_verification'=> (string) ($source['verification_status'] ?? 'failed'),
            'audit_status'       => (string) ($source['audit_status'] ?? 'unknown'),
            'decision_status'    => (string) ($source['decision_status'] ?? 'unknown'),
            'decision'           => (string) ($source['decision'] ?? 'unknown'),
            'guard_release'     => false,
            'execution_allowed' => false,
            'event'              => 'phase_179_final_decision_audit_integrity_snapshot',
            'reason'             => $verified
                ? 'phase_178_integrity_is_verified_and_execution_remains_locked'
                : 'phase_178_integrity_verification_is_not_valid',
            'created_at'         => time(),
        ];

        update_option(self::OPTION, $snapshot, false);
        return $snapshot;
    }

    public static function render(): void {
        if (!current_user_can('manage_options')) {
            return;
        }

        $s = self::evaluate();
        echo '<div class="wrap"><h1>SLA Drift Guard — Phase 179</h1>';
        echo '<p>Final Decision Audit Integrity Snapshot.</p>';
        echo '<table class="widefat striped"><tbody>';

        $rows = [
            'Snapshot status'     => strtoupper((string) $s['snapshot_status']),
            'Source verification' => strtoupper((string) $s['source_verification']),
            'Audit status'        => strtoupper((string) $s['audit_status']),
            'Decision status'     => strtoupper(str_replace('_', ' ', (string) $s['decision_status'])),
            'Decision'            => strtoupper(str_replace('_', ' ', (string) $s['decision'])),
            'Guard release'       => $s['guard_release'] ? 'YES' : 'NO',
            'Execution allowed'   => $s['execution_allowed'] ? 'YES' : 'NO',
            'Event'               => str_replace('_', ' ', (string) $s['event']),
            'Reason'              => str_replace('_', ' ', (string) $s['reason']),
            'Created at'          => wp_date('Y-m-d H:i:s', (int) $s['created_at']),
        ];

        foreach ($rows as $key => $value) {
            echo '<tr><th>' . esc_html($key) . '</th><td>' . esc_html($value) . '</td></tr>';
        }

        echo '</tbody></table></div>';
    }
}
