<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealth {
    private const CAPABILITY = 'manage_options';
    private const HOOK = 'avanik_sla_audit_verification_report_refresh';

    public static function register(): void {
        add_options_page('SLA Verification Report Schedule Health', 'SLA Verification Report Schedule Health', self::CAPABILITY, 'avanik-sla-verification-report-schedule-health', [self::class, 'render']);
        NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncident::register();
    }

    public static function state(): array {
        $next = wp_next_scheduled(self::HOOK);
        $report = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportSchedule::state();
        $last = (int)($report['generated_at'] ?? 0);
        return ['scheduled'=>(bool)$next,'next_run'=>$next ? (int)$next : 0,'last_refresh'=>$last,'age_seconds'=>$last ? max(0,time()-$last) : null,'healthy'=>(bool)$next && $last > 0 && (time()-$last) <= (2*HOUR_IN_SECONDS)];
    }

    public static function render(): void {
        if (!current_user_can(self::CAPABILITY)) return;
        $s = self::state();
        echo '<div class="wrap"><h1>SLA Verification Report Schedule Health</h1><p>Operational health of the Phase 106 scheduled verification refresh.</p><table class="widefat striped"><tbody>';
        foreach (['Status'=>$s['healthy']?'HEALTHY':'ATTENTION','Cron scheduled'=>$s['scheduled']?'YES':'NO','Next run'=>$s['next_run']?wp_date('Y-m-d H:i:s',$s['next_run']):'—','Last refresh'=>$s['last_refresh']?wp_date('Y-m-d H:i:s',$s['last_refresh']):'—','Refresh age'=>$s['age_seconds']===null?'—':((int)$s['age_seconds']).' seconds'] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
