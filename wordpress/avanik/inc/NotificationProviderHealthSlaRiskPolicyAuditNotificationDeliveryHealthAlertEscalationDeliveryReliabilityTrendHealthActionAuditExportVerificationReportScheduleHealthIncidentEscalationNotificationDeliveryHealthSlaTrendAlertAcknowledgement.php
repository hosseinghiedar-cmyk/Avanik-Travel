<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgement {
    private const OPTION = 'avanik_sla_delivery_trend_alert_acknowledgement';
    private const CAPABILITY = 'manage_options';

    public static function register(): void {
        add_options_page('SLA Delivery Trend Alert Acknowledgement', 'SLA Delivery Trend Alert Acknowledgement', self::CAPABILITY, 'avanik-sla-delivery-trend-alert-ack', [self::class, 'render']);
    }

    public static function state(): array {
        $s = get_option(self::OPTION, []);
        return is_array($s) ? $s : [];
    }

    public static function acknowledge(): void {
        if (!current_user_can(self::CAPABILITY) || !check_admin_referer('avanik_sla_trend_ack')) return;
        $alert = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlert::evaluate();
        update_option(self::OPTION, ['acknowledged'=>true,'alert'=>$alert['alert'] ? 'yes' : 'no','direction'=>$alert['direction'],'acknowledged_at'=>time(),'user_id'=>get_current_user_id()], false);
    }

    public static function render(): void {
        if (!current_user_can(self::CAPABILITY)) return;
        if (isset($_POST['avanik_ack']) && $_POST['avanik_ack'] === '1') self::acknowledge();
        $s = self::state();
        echo '<div class="wrap"><h1>SLA Delivery Trend Alert Acknowledgement</h1><p>Records administrator acknowledgement metadata for the Phase 114 trend alert without creating a second audit stream.</p><table class="widefat striped"><tbody>';
        foreach (['Acknowledged'=>!empty($s['acknowledged']) ? 'YES' : 'NO','Alert at acknowledgement'=>strtoupper((string)($s['alert'] ?? 'none')),'Direction'=>strtoupper((string)($s['direction'] ?? 'none')),'Acknowledged at'=>!empty($s['acknowledged_at']) ? wp_date('Y-m-d H:i:s',(int)$s['acknowledged_at']) : '—','User ID'=>(int)($s['user_id'] ?? 0)] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table><form method="post" style="margin-top:16px">'.wp_nonce_field('avanik_sla_trend_ack','_wpnonce',true,false).'<input type="hidden" name="avanik_ack" value="1"><button class="button button-primary">Acknowledge current alert</button></form></div>';
    }
}
