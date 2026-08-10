<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskNotificationPolicyAudit {
    private const OPTION = 'avanik_provider_health_sla_risk_notification_policy_audit';
    private const MAX_ENTRIES = 100;

    public static function register(): void {
        add_action('avanik_provider_health_sla_risk_notification_policy_saved', [self::class, 'record'], 10, 2);
        add_options_page('Provider SLA Risk Policy Audit', 'Provider SLA Risk Policy Audit', 'manage_options', 'avanik-provider-sla-risk-policy-audit', [self::class, 'render']);
    }

    public static function record(array $before, array $after): void {
        $changes = [];
        foreach (array_unique(array_merge(array_keys($before), array_keys($after))) as $key) {
            $old = $before[$key] ?? null;
            $new = $after[$key] ?? null;
            if ($old !== $new) {
                $changes[$key] = [
                    'from' => is_scalar($old) || $old === null ? $old : wp_json_encode($old),
                    'to' => is_scalar($new) || $new === null ? $new : wp_json_encode($new),
                ];
            }
        }
        if (!$changes) return;
        $entries = get_option(self::OPTION, []);
        if (!is_array($entries)) $entries = [];
        array_unshift($entries, [
            'timestamp' => time(),
            'user_id' => get_current_user_id(),
            'changes' => $changes,
        ]);
        update_option(self::OPTION, array_slice($entries, 0, self::MAX_ENTRIES), false);
    }

    public static function entries(): array {
        $entries = get_option(self::OPTION, []);
        return is_array($entries) ? $entries : [];
    }

    public static function render(): void {
        if (!current_user_can('manage_options')) return;
        echo '<div class="wrap"><h1>Provider SLA Risk Policy Audit</h1><p>Read-only history of policy changes. Maximum 100 entries are retained.</p>';
        echo '<table class="widefat striped"><thead><tr><th>Time</th><th>User</th><th>Changes</th></tr></thead><tbody>';
        $entries = self::entries();
        if (!$entries) echo '<tr><td colspan="3">No policy changes recorded.</td></tr>';
        foreach ($entries as $entry) {
            $changes = [];
            foreach ((array) ($entry['changes'] ?? []) as $key => $change) {
                $changes[] = esc_html($key . ': ' . (string) ($change['from'] ?? 'null') . ' → ' . (string) ($change['to'] ?? 'null'));
            }
            $user = !empty($entry['user_id']) ? get_userdata((int) $entry['user_id']) : null;
            echo '<tr><td>' . esc_html(!empty($entry['timestamp']) ? wp_date('Y-m-d H:i:s', (int) $entry['timestamp']) : '—') . '</td><td>' . esc_html($user ? $user->user_login : 'system') . '</td><td>' . implode('<br>', $changes) . '</td></tr>';
        }
        echo '</tbody></table></div>';
    }
}
