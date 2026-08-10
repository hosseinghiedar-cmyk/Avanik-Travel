<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskNotificationPolicy {
    private const OPTION = 'avanik_provider_health_sla_risk_notification_policy';

    public static function register(): void {
        add_options_page('Provider SLA Risk Notification Policy', 'Provider SLA Risk Policy', 'manage_options', 'avanik-provider-sla-risk-notification-policy', [self::class, 'render']);
    }

    public static function defaults(): array {
        return [
            'critical_enabled' => true,
            'high_enabled' => true,
            'medium_enabled' => true,
            'low_enabled' => false,
            'critical_role' => 'admin',
            'high_role' => 'admin',
            'medium_role' => 'admin',
            'cooldown_minutes' => 0,
        ];
    }

    public static function settings(): array {
        return wp_parse_args((array) get_option(self::OPTION, []), self::defaults());
    }

    public static function allows(string $risk): bool {
        $s = self::settings();
        $key = strtolower($risk) . '_enabled';
        return array_key_exists($key, $s) ? !empty($s[$key]) : false;
    }

    public static function role(string $risk): string {
        $s = self::settings();
        $key = strtolower($risk) . '_role';
        return sanitize_key((string) ($s[$key] ?? 'admin')) ?: 'admin';
    }

    public static function cooldown_minutes(): int {
        return max(0, min(1440, absint(self::settings()['cooldown_minutes'] ?? 0)));
    }

    public static function render(): void {
        if (!current_user_can('manage_options')) return;
        $s = self::settings();
        if (isset($_POST['avanik_policy_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['avanik_policy_nonce'])), 'avanik_save_risk_policy')) {
            foreach (['critical','high','medium','low'] as $risk) $s[$risk . '_enabled'] = !empty($_POST[$risk . '_enabled']);
            foreach (['critical','high','medium'] as $risk) $s[$risk . '_role'] = sanitize_key((string) ($_POST[$risk . '_role'] ?? 'admin')) ?: 'admin';
            $s['cooldown_minutes'] = max(0, min(1440, absint($_POST['cooldown_minutes'] ?? 0)));
            update_option(self::OPTION, $s, false);
            echo '<div class="notice notice-success"><p>Risk notification policy saved.</p></div>';
        }
        echo '<div class="wrap"><h1>Provider SLA Risk Notification Policy</h1><form method="post">';
        wp_nonce_field('avanik_save_risk_policy', 'avanik_policy_nonce');
        foreach (['critical','high','medium','low'] as $risk) {
            echo '<p><label><input type="checkbox" name="'.esc_attr($risk).'_enabled" value="1" '.checked(!empty($s[$risk.'_enabled']), true, false).'> '.esc_html(ucfirst($risk)).' risk alerts</label></p>';
        }
        foreach (['critical','high','medium'] as $risk) {
            echo '<p><label>'.esc_html(ucfirst($risk)).' role <input type="text" name="'.esc_attr($risk).'_role" value="'.esc_attr($s[$risk.'_role']).'" class="regular-text"></label></p>';
        }
        echo '<p><label>Cooldown minutes <input type="number" min="0" max="1440" name="cooldown_minutes" value="'.esc_attr($s['cooldown_minutes']).'"></label></p>';
        echo '<p><button class="button button-primary">Save Policy</button></p></form></div>';
    }
}
