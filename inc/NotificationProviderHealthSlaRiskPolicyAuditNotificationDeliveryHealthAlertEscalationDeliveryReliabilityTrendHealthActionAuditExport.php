<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExport {
    private const CAPABILITY = 'manage_options';

    public static function register(): void {
        add_action('admin_post_avanik_sla_trend_health_audit_export', [self::class, 'export_csv']);
        add_options_page('SLA Trend Health Audit Export', 'SLA Trend Health Audit Export', self::CAPABILITY, 'avanik-sla-trend-health-audit-export', [self::class, 'render']);
        NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportIntegrity::register();
    }

    public static function render(): void {
        if (!current_user_can(self::CAPABILITY)) return;
        echo '<div class="wrap"><h1>SLA Trend Health Audit Export</h1><p>Export the bounded Phase 100 audit entries as CSV for offline review.</p>';
        echo '<form method="post" action="'.esc_url(admin_url('admin-post.php')).'">';
        echo '<input type="hidden" name="action" value="avanik_sla_trend_health_audit_export">';
        wp_nonce_field('avanik_sla_trend_health_audit_export');
        submit_button('Export Audit CSV', 'primary');
        echo '</form></div>';
    }

    public static function export_csv(): void {
        if (!current_user_can(self::CAPABILITY)) wp_die('Forbidden');
        check_admin_referer('avanik_sla_trend_health_audit_export');
        $entries = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAudit::entries();
        nocache_headers();
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="avanik-sla-trend-health-action-audit.csv"');
        $out = fopen('php://output', 'w');
        fputcsv($out, ['timestamp','action','status','success_rate','retry_rate','failure_rate','snapshot_count','action_count']);
        foreach ($entries as $entry) {
            fputcsv($out, [wp_date('c', (int)$entry['at']), (string)$entry['action'], (string)$entry['status'], (float)$entry['success_rate'], (float)$entry['retry_rate'], (float)$entry['failure_rate'], (int)$entry['snapshot_count'], (int)$entry['action_count']]);
        }
        fclose($out);
        exit;
    }
}
