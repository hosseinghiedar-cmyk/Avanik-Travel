<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskNotificationPolicyAuditMonitor {
    private const OPTION = 'avanik_provider_health_sla_risk_notification_policy_audit_monitor';
    private const CRON = 'avanik_provider_sla_risk_policy_audit_integrity_check';

    public static function register(): void {
        add_action(self::CRON, [self::class, 'check']);
        add_action('admin_notices', [self::class, 'notice']);
        if (!wp_next_scheduled(self::CRON)) {
            wp_schedule_event(time() + HOUR_IN_SECONDS, 'hourly', self::CRON);
        }
    }

    public static function check(): void {
        $result = NotificationProviderHealthSlaRiskNotificationPolicyAudit::integrity();
        $state = get_option(self::OPTION, []);
        if (!is_array($state)) $state = [];
        $state['checked_at'] = time();
        $state['valid'] = !empty($result['valid']);
        $state['legacy'] = !empty($result['legacy']);
        if (!$state['valid'] && !$state['legacy']) {
            $state['incident_count'] = absint($state['incident_count'] ?? 0) + 1;
            $state['last_failure_at'] = time();
            do_action('avanik_provider_health_sla_risk_notification_policy_audit_integrity_failed', $state);
        }
        update_option(self::OPTION, $state, false);
    }

    public static function state(): array {
        $state = get_option(self::OPTION, []);
        return is_array($state) ? $state : [];
    }

    public static function notice(): void {
        if (!current_user_can('manage_options')) return;
        $state = self::state();
        if (empty($state['checked_at'])) return;
        if (isset($state['valid']) && !$state['valid'] && empty($state['legacy'])) {
            echo '<div class="notice notice-error"><p><strong>Avanik:</strong> Provider SLA Risk Policy audit integrity check failed. Review the audit log immediately.</p></div>';
        }
    }
}
