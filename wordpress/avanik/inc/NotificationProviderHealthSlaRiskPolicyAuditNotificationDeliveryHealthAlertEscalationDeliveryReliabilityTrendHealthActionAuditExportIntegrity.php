<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportIntegrity {
    private const CAPABILITY = 'manage_options';

    public static function register(): void {
        add_options_page('SLA Trend Health Audit Export Integrity', 'SLA Trend Health Audit Export Integrity', self::CAPABILITY, 'avanik-sla-trend-health-audit-export-integrity', [self::class, 'render']);
        NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerification::register();
    }

    public static function build_csv(): string {
        $entries = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAudit::entries();
        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, ['timestamp','action','status','success_rate','retry_rate','failure_rate','snapshot_count','action_count']);
        foreach ($entries as $entry) fputcsv($handle, [wp_date('c',(int)$entry['at']),(string)$entry['action'],(string)$entry['status'],(float)$entry['success_rate'],(float)$entry['retry_rate'],(float)$entry['failure_rate'],(int)$entry['snapshot_count'],(int)$entry['action_count']]);
        rewind($handle); $csv = stream_get_contents($handle); fclose($handle); return is_string($csv) ? $csv : '';
    }
    public static function checksum(): string { return hash('sha256', self::build_csv()); }
    public static function render(): void {
        if (!current_user_can(self::CAPABILITY)) return;
        $entries = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAudit::entries();
        echo '<div class="wrap"><h1>SLA Trend Health Audit Export Integrity</h1><p>This checksum is calculated from the exact bounded audit dataset used by the Phase 102 CSV export.</p><table class="widefat striped"><tbody><tr><th>Entries</th><td>'.count($entries).'</td></tr><tr><th>CSV SHA-256</th><td><code>'.esc_html(self::checksum()).'</code></td></tr></tbody></table></div>';
    }
}
