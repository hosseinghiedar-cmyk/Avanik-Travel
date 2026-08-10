<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerification {
    private const CAPABILITY = 'manage_options';

    public static function register(): void {
        add_options_page('SLA Trend Health Audit Export Verification', 'SLA Trend Health Audit Export Verification', self::CAPABILITY, 'avanik-sla-trend-health-audit-export-verification', [self::class, 'render']);
        NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReport::register();
    }

    public static function verify(string $csv): array {
        $expected = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportIntegrity::checksum();
        $actual = hash('sha256', $csv);
        return ['match'=>hash_equals($expected, $actual),'expected'=>$expected,'actual'=>$actual];
    }

    public static function render(): void {
        if (!current_user_can(self::CAPABILITY)) return;
        $expected = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportIntegrity::checksum();
        echo '<div class="wrap"><h1>SLA Trend Health Audit Export Verification</h1><p>Use the checksum shown below to verify an exported Phase 102 CSV before importing or archiving it.</p><table class="widefat striped"><tbody><tr><th>Expected SHA-256</th><td><code>'.esc_html($expected).'</code></td></tr><tr><th>Dataset entries</th><td>'.count(NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAudit::entries()).'</td></tr></tbody></table></div>';
    }
}
