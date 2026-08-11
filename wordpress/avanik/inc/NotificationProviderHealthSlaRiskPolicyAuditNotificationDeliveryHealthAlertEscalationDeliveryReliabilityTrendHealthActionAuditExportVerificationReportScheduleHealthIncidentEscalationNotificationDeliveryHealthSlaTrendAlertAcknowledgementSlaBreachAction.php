<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachAction {
    private const OPTION = 'avanik_sla_trend_alert_ack_sla_breach_action';
    private const CAPABILITY = 'manage_options';

    public static function register(): void {
        add_options_page('SLA Acknowledgement Breach Action', 'SLA Acknowledgement Breach Action', self::CAPABILITY, 'avanik-sla-ack-breach-action', [self::class, 'render']);
    }

    public static function evaluate(): array {
        $sla = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSla::evaluate();
        $previous = get_option(self::OPTION, []);
        $previous = is_array($previous) ? $previous : [];
        $breach = $sla['status'] === 'breach';
        $transition = $breach && ($previous['status'] ?? 'compliant') !== 'breach' ? 'opened' : (!$breach && ($previous['status'] ?? '') === 'breach' ? 'resolved' : 'steady');
        $state = ['status'=>$breach ? 'breach' : 'compliant','transition'=>$transition,'action'=>$breach ? 'administrator_attention' : 'none','evaluated_at'=>time(),'last_breach_at'=>$breach ? time() : (int)($previous['last_breach_at'] ?? 0)];
        update_option(self::OPTION, $state, false);
        return $state;
    }

    public static function render(): void {
        if (!current_user_can(self::CAPABILITY)) return;
        $s = self::evaluate();
        echo '<div class="wrap"><h1>SLA Acknowledgement Breach Action</h1><p>Turns an acknowledgement-SLA breach into an explicit administrator-attention state without creating duplicate notifications or audit events.</p><table class="widefat striped"><tbody>';
        foreach (['Status'=>strtoupper($s['status']),'Transition'=>strtoupper($s['transition']),'Action'=>strtoupper(str_replace('_',' ',$s['action'])),'Last breach'=>!empty($s['last_breach_at']) ? wp_date('Y-m-d H:i:s',(int)$s['last_breach_at']) : '—','Evaluated at'=>wp_date('Y-m-d H:i:s',(int)$s['evaluated_at'])] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
