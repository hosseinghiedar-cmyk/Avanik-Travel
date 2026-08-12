<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSlaBreachActionRecovery {
    private const OPTION = 'avanik_sla_ack_breach_action_recovery';
    private const CAPABILITY = 'manage_options';

    public static function register(): void {
        add_options_page('SLA Breach Action Recovery', 'SLA Breach Action Recovery', self::CAPABILITY, 'avanik-sla-breach-action-recovery', [self::class, 'render']);
    }

    public static function evaluate(): array {
        $sla = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotificationDeliveryHealthSlaTrendAlertAcknowledgementSla::evaluate();
        $previous = get_option(self::OPTION, []);
        $previous = is_array($previous) ? $previous : [];
        $status = (string)$sla['status'];
        $previous_status = (string)($previous['status'] ?? 'none');
        $transition = $status === 'breach' && $previous_status !== 'breach' ? 'opened' : ($status !== 'breach' && $previous_status === 'breach' ? 'resolved' : 'steady');
        $state = ['status'=>$status,'transition'=>$transition,'action'=>$status === 'breach' ? 'administrator_attention' : 'none','evaluated_at'=>time(),'previous_status'=>$previous_status];
        update_option(self::OPTION, $state, false);
        return $state;
    }

    public static function state(): array {
        $s = get_option(self::OPTION, []);
        return is_array($s) ? $s : [];
    }

    public static function render(): void {
        if (!current_user_can(self::CAPABILITY)) return;
        $s = self::evaluate();
        echo '<div class="wrap"><h1>SLA Breach Action Recovery</h1><p>Tracks recovery of the Phase 117 acknowledgement SLA breach action.</p><table class="widefat striped"><tbody>';
        foreach (['Status'=>strtoupper($s['status']),'Transition'=>strtoupper($s['transition']),'Action'=>strtoupper(str_replace('_',' ',$s['action'])),'Previous status'=>strtoupper($s['previous_status']),'Evaluated at'=>wp_date('Y-m-d H:i:s',$s['evaluated_at'])] as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
