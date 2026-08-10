<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditDashboard {
    private const SLUG = 'avanik-provider-sla-risk-policy-audit-dashboard';

    public static function register(): void {
        add_options_page('Provider SLA Risk Audit Health', 'Provider SLA Risk Audit Health', 'manage_options', self::SLUG, [self::class, 'render']);
    }

    public static function render(): void {
        if (!current_user_can('manage_options')) return;
        if (isset($_POST['avanik_audit_health_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['avanik_audit_health_nonce'])), 'avanik_audit_health_check')) {
            NotificationProviderHealthSlaRiskNotificationPolicyAuditMonitor::check();
            echo '<div class="notice notice-success"><p>Audit integrity check completed.</p></div>';
        }
        $state = NotificationProviderHealthSlaRiskNotificationPolicyAuditMonitor::state();
        $valid = array_key_exists('valid', $state) ? (bool) $state['valid'] : null;
        echo '<div class="wrap"><h1>Provider SLA Risk Audit Health</h1>';
        echo '<p>This page is read-only except for the manual integrity check action.</p>';
        echo '<table class="widefat striped"><tbody>';
        echo '<tr><th>Integrity</th><td>' . ($valid === true ? '<span style="color:#008000"><strong>OK</strong></span>' : ($valid === false ? '<span style="color:#b32d2e"><strong>FAILED</strong></span>' : 'Not checked yet')) . '</td></tr>';
        echo '<tr><th>Last check</th><td>' . esc_html(!empty($state['checked_at']) ? wp_date('Y-m-d H:i:s', (int) $state['checked_at']) : '—') . '</td></tr>';
        echo '<tr><th>Last failure</th><td>' . esc_html(!empty($state['last_failure_at']) ? wp_date('Y-m-d H:i:s', (int) $state['last_failure_at']) : '—') . '</td></tr>';
        echo '<tr><th>Incidents</th><td>' . esc_html((string) absint($state['incident_count'] ?? 0)) . '</td></tr>';
        echo '</tbody></table>';
        echo '<form method="post" style="margin-top:16px">';
        wp_nonce_field('avanik_audit_health_check', 'avanik_audit_health_nonce');
        echo '<button class="button button-primary">Run Integrity Check Now</button></form></div>';
    }
}
