<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAuditSummary {
    private const OPTION = 'avanik_provider_sla_health_escalation_reliability_trend_health_action_audit_summary';

    public static function register(): void {
        add_options_page('SLA Trend Health Action Audit Summary', 'SLA Trend Health Action Audit Summary', 'manage_options', 'avanik-sla-trend-health-action-audit-summary', [self::class, 'render']);
    }

    public static function summary(): array {
        $entries = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAudit::entries();
        $counts = [];
        $statuses = [];
        foreach ($entries as $entry) {
            $action = sanitize_key((string)($entry['action'] ?? 'unknown'));
            $status = sanitize_key((string)($entry['status'] ?? 'no-data'));
            $counts[$action] = ($counts[$action] ?? 0) + 1;
            $statuses[$status] = ($statuses[$status] ?? 0) + 1;
        }
        $summary = [
            'entries' => count($entries),
            'actions' => $counts,
            'statuses' => $statuses,
            'latest_at' => $entries ? (int)$entries[count($entries)-1]['at'] : 0,
        ];
        update_option(self::OPTION, $summary, false);
        return $summary;
    }

    public static function render(): void {
        if (!current_user_can('manage_options')) return;
        $summary = self::summary();
        echo '<div class="wrap"><h1>SLA Trend Health Action Audit Summary</h1><table class="widefat striped"><tbody>';
        echo '<tr><th>Total audit entries</th><td>'.(int)$summary['entries'].'</td></tr>';
        echo '<tr><th>Latest entry</th><td>'.($summary['latest_at'] ? esc_html(wp_date('Y-m-d H:i:s', $summary['latest_at'])) : '—').'</td></tr>';
        echo '<tr><th>Actions</th><td>'.esc_html(wp_json_encode($summary['actions'])).'</td></tr>';
        echo '<tr><th>Statuses</th><td>'.esc_html(wp_json_encode($summary['statuses'])).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
