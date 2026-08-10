<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAudit {
    private const OPTION = 'avanik_provider_sla_health_escalation_reliability_trend_health_action_audit';
    private const MAX_ENTRIES = 50;

    public static function register(): void {
        add_options_page('SLA Trend Health Action Audit', 'SLA Trend Health Action Audit', 'manage_options', 'avanik-sla-trend-health-action-audit', [self::class, 'render']);
        NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditSummary::register();
    }

    public static function record(string $action, array $health, array $state): void {
        $entries = get_option(self::OPTION, []);
        $entries = is_array($entries) ? $entries : [];
        $entries[] = ['at'=>time(),'action'=>sanitize_key($action),'status'=>sanitize_key((string)($health['status'] ?? 'no-data')),'success_rate'=>(float)($health['success_rate'] ?? 0),'retry_rate'=>(float)($health['retry_rate'] ?? 0),'failure_rate'=>(float)($health['failure_rate'] ?? 0),'snapshot_count'=>(int)($health['snapshot_count'] ?? 0),'action_count'=>(int)($state['action_count'] ?? 0)];
        if (count($entries) > self::MAX_ENTRIES) $entries = array_slice($entries, -self::MAX_ENTRIES);
        update_option(self::OPTION, $entries, false);
    }

    public static function entries(): array {
        $entries = get_option(self::OPTION, []);
        return is_array($entries) ? array_slice($entries, -self::MAX_ENTRIES) : [];
    }

    public static function render(): void {
        if (!current_user_can('manage_options')) return;
        $entries = self::entries();
        echo '<div class="wrap"><h1>SLA Trend Health Action Audit</h1><p>Latest '.count($entries).' health actions are retained.</p><table class="widefat striped"><thead><tr><th>Time</th><th>Action</th><th>Status</th><th>Success %</th><th>Retry %</th><th>Failure %</th><th>Snapshots</th><th>Actions</th></tr></thead><tbody>';
        foreach (array_reverse($entries) as $entry) echo '<tr><td>'.esc_html(wp_date('Y-m-d H:i:s',(int)$entry['at'])).'</td><td>'.esc_html($entry['action']).'</td><td>'.esc_html(strtoupper($entry['status'])).'</td><td>'.esc_html((string)$entry['success_rate']).'%</td><td>'.esc_html((string)$entry['retry_rate']).'%</td><td>'.esc_html((string)$entry['failure_rate']).'%</td><td>'.(int)$entry['snapshot_count'].'</td><td>'.(int)$entry['action_count'].'</td></tr>';
        if (!$entries) echo '<tr><td colspan="8">No health actions recorded yet.</td></tr>';
        echo '</tbody></table></div>';
    }
}
