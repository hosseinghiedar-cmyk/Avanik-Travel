<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSla {
    private const OPTION = 'avanik_sla_delivery_trend_alert_ack_sla';
    private const CAPABILITY = 'manage_options';
    private const TARGET_SECONDS = 3600;

    public static function register(): void {
        add_options_page('SLA Trend Alert Acknowledgement SLA', 'SLA Trend Alert Acknowledgement SLA', self::CAPABILITY, 'avanik-sla-trend-alert-ack-sla', [self::class, 'render']);
    }

    public static function evaluate(): array {
        $ack = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgement::state();
        $alert = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlert::evaluate();
        $now = time();
        $ackAt = (int)($ack['acknowledged_at'] ?? 0);
        $active = !empty($alert['alert']);
        $elapsed = $ackAt > 0 ? max(0, $ackAt - (int)($alert['evaluated_at'] ?? $now)) : 0;
        $compliant = !$active || ($ackAt > 0 && $elapsed <= self::TARGET_SECONDS);
        $state = ['status'=>$compliant ? 'compliant' : 'breach','target_seconds'=>self::TARGET_SECONDS,'active_alert'=>$active,'acknowledged_at'=>$ackAt,'elapsed_seconds'=>$elapsed,'evaluated_at'=>$now];
        update_option(self::OPTION, $state, false);
        return $state;
    }

    public static function render(): void {
        if (!current_user_can(self::CAPABILITY)) return;
        $s = self::evaluate();
        echo '<div class="wrap"><h1>SLA Trend Alert Acknowledgement SLA</h1><p>Checks whether an active Phase 114 alert has been acknowledged within the one-hour target.</p><table class="widefat striped"><tbody>';
        foreach (['Status'=>strtoupper($s['status']),'Active alert'=>$s['active_alert'] ? 'YES' : 'NO','Target (sec)'=>$s['target_seconds'],'Elapsed (sec)'=>$s['elapsed_seconds'],'Acknowledged at'=>$s['acknowledged_at'] ? wp_date('Y-m-d H:i:s',$s['acknowledged_at']) : '—','Evaluated at'=>wp_date('Y-m-d H:i:s',$s['evaluated_at'])] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
