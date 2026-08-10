<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReport {
    private const CAPABILITY = 'manage_options';

    public static function register(): void {
        add_options_page('SLA Audit Export Verification Report', 'SLA Audit Export Verification Report', self::CAPABILITY, 'avanik-sla-audit-export-verification-report', [self::class, 'render']);
    }

    public static function report(): array {
        $entries = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAudit::entries();
        $csv = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportIntegrity::build_csv();
        return [
            'entries' => count($entries),
            'sha256' => hash('sha256', $csv),
            'bytes' => strlen($csv),
            'generated_at' => time(),
            'status' => $csv !== '' || !$entries ? 'ready' : 'empty',
        ];
    }

    public static function render(): void {
        if (!current_user_can(self::CAPABILITY)) return;
        $r = self::report();
        echo '<div class="wrap"><h1>SLA Audit Export Verification Report</h1><p>Current verification metadata for the exact Phase 102/103 audit dataset.</p><table class="widefat striped"><tbody>';
        $rows = ['Status'=>strtoupper($r['status']),'Audit entries'=>$r['entries'],'CSV bytes'=>$r['bytes'],'SHA-256'=>$r['sha256'],'Generated at'=>wp_date('Y-m-d H:i:s',$r['generated_at'])];
        foreach ($rows as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
