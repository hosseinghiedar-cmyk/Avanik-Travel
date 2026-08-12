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

    private static function canonical(array $entry): string {
        return wp_json_encode([
            'timestamp' => (int) ($entry['timestamp'] ?? 0),
            'user_id' => (int) ($entry['user_id'] ?? 0),
            'changes' => $entry['changes'] ?? [],
            'previous_hash' => (string) ($entry['previous_hash'] ?? ''),
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_SORT_KEYS);
    }

    private static function hash(array $entry): string {
        return hash('sha256', self::canonical($entry));
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
        $previous = !empty($entries[0]['chain_hash']) ? (string) $entries[0]['chain_hash'] : '';
        $entry = [
            'timestamp' => time(),
            'user_id' => get_current_user_id(),
            'changes' => $changes,
            'previous_hash' => $previous,
        ];
        $entry['chain_hash'] = self::hash($entry);
        array_unshift($entries, $entry);
        update_option(self::OPTION, array_slice($entries, 0, self::MAX_ENTRIES), false);
    }

    public static function entries(): array {
        $entries = get_option(self::OPTION, []);
        return is_array($entries) ? $entries : [];
    }

    public static function integrity(): array {
        $entries = self::entries();
        $previous = '';
        foreach ($entries as $entry) {
            if (empty($entry['chain_hash'])) return ['valid' => false, 'legacy' => true];
            if ((string) ($entry['previous_hash'] ?? '') !== $previous) return ['valid' => false, 'legacy' => false];
            if (!hash_equals((string) $entry['chain_hash'], self::hash($entry))) return ['valid' => false, 'legacy' => false];
            $previous = (string) $entry['chain_hash'];
        }
        return ['valid' => true, 'legacy' => false];
    }

    public static function render(): void {
        if (!current_user_can('manage_options')) return;
        $integrity = self::integrity();
        $status = $integrity['legacy'] ? 'Legacy entries detected; new entries are hash-chained.' : ($integrity['valid'] ? 'Audit chain integrity OK.' : 'WARNING: audit chain integrity check failed.');
        $class = ($integrity['valid'] || $integrity['legacy']) ? 'notice notice-info' : 'notice notice-error';
        echo '<div class="wrap"><h1>Provider SLA Risk Policy Audit</h1>';
        echo '<div class="' . esc_attr($class) . '"><p>' . esc_html($status) . '</p></div>';
        echo '<p>Read-only history of policy changes. Maximum 100 entries are retained. New entries use a SHA-256 hash chain.</p>';
        echo '<table class="widefat striped"><thead><tr><th>Time</th><th>User</th><th>Changes</th><th>Chain Hash</th></tr></thead><tbody>';
        $entries = self::entries();
        if (!$entries) echo '<tr><td colspan="4">No policy changes recorded.</td></tr>';
        foreach ($entries as $entry) {
            $changes = [];
            foreach ((array) ($entry['changes'] ?? []) as $key => $change) {
                $changes[] = esc_html($key . ': ' . (string) ($change['from'] ?? 'null') . ' → ' . (string) ($change['to'] ?? 'null'));
            }
            $user = !empty($entry['user_id']) ? get_userdata((int) $entry['user_id']) : null;
            $hash = !empty($entry['chain_hash']) ? substr((string) $entry['chain_hash'], 0, 16) . '…' : 'legacy';
            echo '<tr><td>' . esc_html(!empty($entry['timestamp']) ? wp_date('Y-m-d H:i:s', (int) $entry['timestamp']) : '—') . '</td><td>' . esc_html($user ? $user->user_login : 'system') . '</td><td>' . implode('<br>', $changes) . '</td><td><code>' . esc_html($hash) . '</code></td></tr>';
        }
        echo '</tbody></table></div>';
    }
}
