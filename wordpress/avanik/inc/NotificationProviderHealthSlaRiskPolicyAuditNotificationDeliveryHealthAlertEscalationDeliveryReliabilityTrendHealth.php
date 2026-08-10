<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealth {
    private const OPTION = 'avanik_provider_sla_health_escalation_delivery_reliability_trend_health';

    public static function register(): void {
        add_options_page('SLA Escalation Reliability Trend Health', 'SLA Escalation Reliability Trend Health', 'manage_options', 'avanik-sla-escalation-reliability-trend-health', [self::class, 'render']);
        NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthAction::register();
    }

    public static function assess(): array {
        $points = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrend::points();
        $latest = $points ? end($points) : null;
        $previous = null;
        if (count($points) > 1) $previous = $points[count($points) - 2];
        $status = 'no-data';
        $reason = 'No escalation reliability snapshots are available.';
        if ($latest) {
            $failure = (float)$latest['failure_rate']; $retry = (float)$latest['retry_rate']; $success = (float)$latest['success_rate'];
            $status = 'stable'; $reason = 'Current delivery reliability is within the normal bounded trend state.';
            if ($failure >= 20 || $success < 80 || $retry >= 30) { $status = 'degraded'; $reason = 'Failure, retry, or success-rate thresholds indicate degraded escalation delivery reliability.'; }
            elseif ($previous) {
                $failureDelta = $failure - (float)$previous['failure_rate']; $successDelta = $success - (float)$previous['success_rate'];
                if ($failureDelta >= 10 || $successDelta <= -10) { $status = 'degrading'; $reason = 'The latest snapshot is materially worse than the previous snapshot.'; }
                elseif ($failureDelta <= -10 || $successDelta >= 10) { $status = 'improving'; $reason = 'The latest snapshot is materially better than the previous snapshot.'; }
            }
        }
        $assessment = ['status'=>$status,'reason'=>$reason,'latest_at'=>$latest ? (int)$latest['at'] : 0,'failure_rate'=>$latest ? (float)$latest['failure_rate'] : 0.0,'retry_rate'=>$latest ? (float)$latest['retry_rate'] : 0.0,'success_rate'=>$latest ? (float)$latest['success_rate'] : 0.0,'snapshot_count'=>count($points)];
        update_option(self::OPTION, $assessment, false);
        return $assessment;
    }

    public static function render(): void {
        if (!current_user_can('manage_options')) return;
        $a = self::assess();
        echo '<div class="wrap"><h1>SLA Escalation Reliability Trend Health</h1><table class="widefat striped"><tbody>';
        $rows = ['Status'=>strtoupper($a['status']),'Reason'=>$a['reason'],'Success rate'=>$a['success_rate'].'%','Retry rate'=>$a['retry_rate'].'%','Failure rate'=>$a['failure_rate'].'%','Snapshots'=>$a['snapshot_count'],'Latest snapshot'=>$a['latest_at'] ? wp_date('Y-m-d H:i:s',$a['latest_at']) : '—'];
        foreach ($rows as $key=>$value) echo '<tr><th>'.esc_html($key).'</th><td>'.esc_html((string)$value).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
