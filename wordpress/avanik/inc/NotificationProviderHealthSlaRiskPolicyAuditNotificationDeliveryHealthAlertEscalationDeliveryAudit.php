<?php
namespace Avanik;
defined('ABSPATH') || exit;

require_once __DIR__ . '/NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliability.php';

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryAudit {
    private const OPTION = 'avanik_provider_sla_health_escalation_delivery_audit';
    private const MAX = 100;

    public static function register(): void {
        add_action('avanik_notification_delivery_attempt', [self::class, 'attempt'], 10, 5);
        add_action('avanik_notification_delivery_result', [self::class, 'result'], 10, 12);
        add_options_page('SLA Escalation Delivery Audit', 'SLA Escalation Delivery Audit', 'manage_options', 'avanik-sla-escalation-delivery-audit', [self::class, 'render']);
        NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliability::register();
    }

    public static function attempt(int $id, string $event, string $role, int $userId, string $channel): void {
        if ($event !== 'provider_health_escalation') return;
        self::append(['type'=>'attempt','at'=>time(),'id'=>$id,'role'=>sanitize_key($role),'user_id'=>$userId,'channel'=>sanitize_key($channel),'status'=>'attempt','provider'=>'','code'=>'']);
    }

    public static function result(int $id, string $event, string $role, int $userId, string $channel, string $status, string $provider = '', string $providerCode = '', string $errorCode = '', string $errorMessage = '', array $meta = []): void {
        if ($event !== 'provider_health_escalation') return;
        self::append(['type'=>'result','at'=>time(),'id'=>$id,'role'=>sanitize_key($role),'user_id'=>$userId,'channel'=>sanitize_key($channel),'status'=>sanitize_key($status),'provider'=>sanitize_key($provider),'code'=>sanitize_key($errorCode ?: $providerCode)]);
    }

    private static function append(array $entry): void {
        $log = get_option(self::OPTION, []);
        $log = is_array($log) ? $log : [];
        array_unshift($log, $entry);
        update_option(self::OPTION, array_slice($log, 0, self::MAX), false);
    }

    public static function summary(): array {
        $log = get_option(self::OPTION, []);
        $summary = ['events'=>0,'attempts'=>0,'sent'=>0,'retry'=>0,'dead'=>0,'failed'=>0,'latest_at'=>0];
        foreach (is_array($log) ? $log : [] as $row) {
            $summary['events']++;
            $summary['latest_at'] = max($summary['latest_at'], (int)($row['at'] ?? 0));
            if (($row['type'] ?? '') === 'attempt') $summary['attempts']++;
            if (($row['type'] ?? '') === 'result') {
                $status = sanitize_key((string)($row['status'] ?? ''));
                if (isset($summary[$status])) $summary[$status]++;
            }
        }
        $summary['failed'] = $summary['retry'] + $summary['dead'];
        return $summary;
    }

    public static function render(): void {
        if (!current_user_can('manage_options')) return;
        $s = self::summary();
        echo '<div class="wrap"><h1>SLA Escalation Delivery Audit</h1><div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:12px;margin:20px 0;">';
        foreach (['Events'=>'events','Attempts'=>'attempts','Sent'=>'sent','Retry'=>'retry','Dead'=>'dead'] as $label=>$key) echo '<div style="background:#fff;border:1px solid #ccd0d4;padding:16px"><strong>'.esc_html($label).'</strong><div style="font-size:26px;margin-top:8px">'.(int)$s[$key].'</div></div>';
        echo '</div><p>Latest event: '.esc_html($s['latest_at'] ? wp_date('Y-m-d H:i:s',(int)$s['latest_at']) : '—').'</p><table class="widefat striped"><thead><tr><th>Time</th><th>Type</th><th>Queue ID</th><th>Role</th><th>User</th><th>Channel</th><th>Status</th><th>Provider</th><th>Code</th></tr></thead><tbody>';
        $log = get_option(self::OPTION, []);
        foreach (is_array($log) ? $log : [] as $row) echo '<tr><td>'.esc_html(wp_date('Y-m-d H:i:s',(int)($row['at']??0))).'</td><td>'.esc_html($row['type']??'').'</td><td>'.(int)($row['id']??0).'</td><td>'.esc_html($row['role']??'').'</td><td>'.(int)($row['user_id']??0).'</td><td>'.esc_html($row['channel']??'').'</td><td>'.esc_html($row['status']??'').'</td><td>'.esc_html($row['provider']??'').'</td><td>'.esc_html($row['code']??'').'</td></tr>';
        if (!$log) echo '<tr><td colspan="9">No escalation delivery events recorded.</td></tr>';
        echo '</tbody></table></div>';
    }
}
