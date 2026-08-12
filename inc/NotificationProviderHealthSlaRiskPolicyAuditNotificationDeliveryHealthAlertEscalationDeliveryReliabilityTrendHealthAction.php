<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthAction {
    private const OPTION = 'avanik_provider_sla_health_escalation_reliability_trend_health_action';
    private const COOLDOWN = 3600;

    public static function register(): void {
        add_action('admin_post_avanik_sla_trend_health_acknowledge', [self::class, 'acknowledge']);
        add_options_page('SLA Trend Health Action', 'SLA Trend Health Action', 'manage_options', 'avanik-sla-trend-health-action', [self::class, 'render']);
        NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAudit::register();
    }

    public static function state(): array {
        $defaults = ['acknowledged_at'=>0,'last_status'=>'','action_count'=>0];
        $stored = get_option(self::OPTION, []);
        return wp_parse_args(is_array($stored) ? $stored : [], $defaults);
    }

    public static function acknowledge(): void {
        if (!current_user_can('manage_options')) wp_die('Forbidden');
        check_admin_referer('avanik_sla_trend_health_acknowledge');
        $health = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealth::assess();
        $state = self::state();
        $state['acknowledged_at'] = time();
        $state['last_status'] = sanitize_key($health['status']);
        $state['action_count']++;
        update_option(self::OPTION, $state, false);
        NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealthActionAudit::record('acknowledge', $health, $state);
        wp_safe_redirect(add_query_arg(['page'=>'avanik-sla-trend-health-action','acknowledged'=>'1'], admin_url('options-general.php')));
        exit;
    }

    public static function can_acknowledge(): bool {
        $state = self::state();
        return !$state['acknowledged_at'] || (time() - (int)$state['acknowledged_at']) >= self::COOLDOWN;
    }

    public static function render(): void {
        if (!current_user_can('manage_options')) return;
        $health = NotificationProviderHealthSlaRiskPolicyAuditNotificationDeliveryHealthAlertEscalationDeliveryReliabilityTrendHealth::assess();
        $state = self::state();
        echo '<div class="wrap"><h1>SLA Trend Health Action</h1>';
        echo '<p><strong>Current status:</strong> '.esc_html(strtoupper($health['status'])).'</p>';
        echo '<p>'.esc_html($health['reason']).'</p>';
        if (!empty($_GET['acknowledged'])) echo '<div class="notice notice-success"><p>Health state acknowledged.</p></div>';
        if (self::can_acknowledge()) {
            echo '<form method="post" action="'.esc_url(admin_url('admin-post.php')).'"><input type="hidden" name="action" value="avanik_sla_trend_health_acknowledge">'.wp_nonce_field('avanik_sla_trend_health_acknowledge','_wpnonce',true,false).'<p><button class="button button-primary">Acknowledge Current Health State</button></p></form>';
        } else {
            echo '<p><em>Acknowledgement cooldown is active.</em></p>';
        }
        echo '<table class="widefat striped"><tbody>';
        $rows = ['Last acknowledgement'=>$state['acknowledged_at'] ? wp_date('Y-m-d H:i:s',(int)$state['acknowledged_at']) : '—','Acknowledged status'=>$state['last_status'] ?: '—','Action count'=>(int)$state['action_count']];
        foreach ($rows as $k=>$v) echo '<tr><th>'.esc_html($k).'</th><td>'.esc_html((string)$v).'</td></tr>';
        echo '</tbody></table></div>';
    }
}
