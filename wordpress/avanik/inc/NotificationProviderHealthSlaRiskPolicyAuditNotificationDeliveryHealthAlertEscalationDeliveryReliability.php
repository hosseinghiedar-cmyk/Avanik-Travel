<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliability {
    private const OPTION = 'avanik_provider_sla_health_escalation_delivery_reliability';

    public static function register(): void {
        add_options_page('SLA Escalation Delivery Reliability', 'SLA Escalation Delivery Reliability', 'manage_options', 'avanik-sla-escalation-delivery-reliability', [self::class, 'render']);
        NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrend::register();
    }

    public static function metrics(): array {
        $summary = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryAudit::summary();
        $attempts = (int)($summary['attempts'] ?? 0);
        $sent = (int)($summary['sent'] ?? 0);
        $retry = (int)($summary['retry'] ?? 0);
        $dead = (int)($summary['dead'] ?? 0);
        $resolved = $sent + $dead;
        $successRate = $resolved > 0 ? round(($sent / $resolved) * 100, 2) : 0.0;
        $failureRate = $resolved > 0 ? round(($dead / $resolved) * 100, 2) : 0.0;
        $retryRate = $attempts > 0 ? round(($retry / $attempts) * 100, 2) : 0.0;
        $metrics = [
            'events' => (int)($summary['events'] ?? 0),
            'attempts' => $attempts,
            'sent' => $sent,
            'retry' => $retry,
            'dead' => $dead,
            'success_rate' => $successRate,
            'failure_rate' => $failureRate,
            'retry_rate' => $retryRate,
            'latest_at' => (int)($summary['latest_at'] ?? 0),
        ];
        update_option(self::OPTION, $metrics, false);
        return $metrics;
    }

    public static function render(): void {
        if (!current_user_can('manage_options')) return;
        $m = self::metrics();
        echo '<div class="wrap"><h1>SLA Escalation Delivery Reliability</h1><div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px;margin:20px 0;">';
        $cards = [
            'Attempts' => $m['attempts'],
            'Sent' => $m['sent'],
            'Retry Rate' => $m['retry_rate'].'%',
            'Success Rate' => $m['success_rate'].'%',
            'Failure Rate' => $m['failure_rate'].'%',
        ];
        foreach ($cards as $label => $value) echo '<div style="background:#fff;border:1px solid #ccd0d4;padding:16px"><strong>'.esc_html($label).'</strong><div style="font-size:26px;margin-top:8px">'.esc_html((string)$value).'</div></div>';
        echo '</div><table class="widefat striped"><tbody>';
        $rows = [
            'Total events' => $m['events'],
            'Attempts' => $m['attempts'],
            'Sent' => $m['sent'],
            'Retry' => $m['retry'],
            'Dead' => $m['dead'],
            'Latest event' => $m['latest_at'] ? wp_date('Y-m-d H:i:s', $m['latest_at']) : '—',
        ];
        foreach ($rows as $key => $value) echo '<tr><th>'.esc_html($key).'</th><td>'.esc_html((string)$value).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
