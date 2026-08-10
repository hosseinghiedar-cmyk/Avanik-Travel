<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportSchedule {
    private const OPTION = 'avanik_sla_audit_verification_report_schedule';
    private const HOOK = 'avanik_sla_audit_verification_report_refresh';

    public static function register(): void {
        add_action(self::HOOK, [self::class, 'refresh']);
        add_options_page('SLA Verification Report Schedule', 'SLA Verification Report Schedule', 'manage_options', 'avanik-sla-verification-report-schedule', [self::class, 'render']);
        if (!wp_next_scheduled(self::HOOK)) wp_schedule_event(time() + HOUR_IN_SECONDS, 'hourly', self::HOOK);
    }

    public static function refresh(): void {
        $report = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReport::report();
        update_option(self::OPTION, $report, false);
    }

    public static function state(): array {
        $state = get_option(self::OPTION, []);
        return is_array($state) ? $state : [];
    }

    public static function render(): void {
        if (!current_user_can('manage_options')) return;
        self::refresh();
        $state = self::state();
        echo '<div class="wrap"><h1>SLA Verification Report Schedule</h1><p>The verification report is refreshed hourly through WP-Cron.</p><table class="widefat striped"><tbody>';
        $rows = ['Status'=>strtoupper((string)($state['status'] ?? 'unknown')),'Audit entries'=>(int)($state['entries'] ?? 0),'CSV bytes'=>(int)($state['bytes'] ?? 0),'SHA-256'=>(string)($state['sha256'] ?? '—'),'Last refresh'=>!empty($state['generated_at']) ? wp_date('Y-m-d H:i:s',(int)$state['generated_at']) : '—'];
        foreach ($rows as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
