<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryAudit {
    private const OPTION = 'avanik_provider_sla_risk_policy_audit_notification_delivery_audit';
    private const MAX_ENTRIES = 200;
    private const EVENTS = [
        'provider_sla_risk_policy_audit_integrity_failed',
        'provider_sla_risk_policy_audit_integrity_recovered',
    ];

    public static function register(): void {
        add_action('avanik_notification_delivery_result', [self::class, 'record'], 20, 10);
        add_options_page('Provider SLA Audit Notification Delivery', 'Provider SLA Audit Delivery', 'manage_options', 'avanik-provider-sla-audit-notification-delivery', [self::class, 'render']);
    }

    public static function record(int $notificationId, string $event, string $role, int $userId, string $channel, string $status, string $provider = '', string $providerMessage = '', string $errorCode = '', string $errorMessage = '', array $meta = []): void {
        if (!in_array($event, self::EVENTS, true)) return;
        $entries = get_option(self::OPTION, []);
        if (!is_array($entries)) $entries = [];
        array_unshift($entries, [
            'timestamp' => time(),
            'notification_id' => $notificationId,
            'event' => $event,
            'role' => sanitize_key($role),
            'user_id' => $userId,
            'channel' => sanitize_key($channel),
            'status' => sanitize_key($status),
            'provider' => sanitize_key($provider),
            'error_code' => sanitize_key($errorCode),
        ]);
        update_option(self::OPTION, array_slice($entries, 0, self::MAX_ENTRIES), false);
    }

    public static function entries(): array {
        $entries = get_option(self::OPTION, []);
        return is_array($entries) ? $entries : [];
    }

    public static function render(): void {
        if (!current_user_can('manage_options')) return;
        echo '<div class="wrap"><h1>Provider SLA Audit Notification Delivery</h1><p>Read-only delivery audit for integrity failure and recovery notifications. Maximum 200 events are retained.</p>';
        echo '<table class="widefat striped"><thead><tr><th>Time</th><th>Notification</th><th>Event</th><th>Role</th><th>User</th><th>Channel</th><th>Status</th><th>Provider</th><th>Error</th></tr></thead><tbody>';
        $entries = self::entries();
        if (!$entries) echo '<tr><td colspan="9">No delivery events recorded.</td></tr>';
        foreach ($entries as $entry) {
            echo '<tr>';
            echo '<td>'.esc_html(!empty($entry['timestamp']) ? wp_date('Y-m-d H:i:s', (int)$entry['timestamp']) : '—').'</td>';
            echo '<td>'.(int)($entry['notification_id'] ?? 0).'</td>';
            echo '<td>'.esc_html((string)($entry['event'] ?? '')).'</td>';
            echo '<td>'.esc_html((string)($entry['role'] ?? '')).'</td>';
            echo '<td>'.(int)($entry['user_id'] ?? 0).'</td>';
            echo '<td>'.esc_html((string)($entry['channel'] ?? '')).'</td>';
            echo '<td>'.esc_html((string)($entry['status'] ?? '')).'</td>';
            echo '<td>'.esc_html((string)($entry['provider'] ?? '')).'</td>';
            echo '<td>'.esc_html((string)($entry['error_code'] ?? '')).'</td>';
            echo '</tr>';
        }
        echo '</tbody></table></div>';
    }
}
