<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalationNotification {
    private const OPTION = 'avanik_sla_schedule_escalation_notification';
    private const CAPABILITY = 'manage_options';

    public static function register(): void {
        add_options_page('SLA Escalation Notification', 'SLA Escalation Notification', self::CAPABILITY, 'avanik-sla-escalation-notification', [self::class, 'render']);
    }

    public static function notify_if_needed(): void {
        $state = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditExportVerificationReportScheduleHealthIncidentEscalation::state();
        $previous = get_option(self::OPTION, []);
        $previous = is_array($previous) ? $previous : [];
        $level = (string)($state['level'] ?? 'none');
        if ($level === 'none' || $level === (string)($previous['level'] ?? 'none')) {
            update_option(self::OPTION, ['level'=>$level,'notified_at'=>(int)($previous['notified_at'] ?? 0)], false);
            return;
        }
        $admin = get_option('admin_email');
        if (is_email($admin)) {
            wp_mail($admin, 'Avanik SLA Scheduler '.$level, 'SLA verification scheduler escalation level changed to '.strtoupper($level).'.');
        }
        update_option(self::OPTION, ['level'=>$level,'notified_at'=>time()], false);
    }

    public static function render(): void {
        if (!current_user_can(self::CAPABILITY)) return;
        self::notify_if_needed();
        $state = get_option(self::OPTION, []);
        echo '<div class="wrap"><h1>SLA Escalation Notification</h1><p>Notifications are sent only when the escalation level changes and use the WordPress administrator email.</p><table class="widefat striped"><tbody>';
        echo '<tr><th>Current level</th><td>'.esc_html(strtoupper((string)($state['level'] ?? 'none'))).'</td></tr>';
        echo '<tr><th>Last notification</th><td>'.(!empty($state['notified_at']) ? esc_html(wp_date('Y-m-d H:i:s',(int)$state['notified_at'])) : '—').'</td></tr>';
        echo '</tbody></table></div>';
    }
}
