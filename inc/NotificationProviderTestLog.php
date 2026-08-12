<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class NotificationProviderTestLog {
 private static function table(): string { global $wpdb; return $wpdb->prefix.'avanik_provider_test_log'; }
 public static function install(): void { global $wpdb; require_once ABSPATH.'wp-admin/includes/upgrade.php'; $t=self::table(); $c=$wpdb->get_charset_collate(); dbDelta("CREATE TABLE {$t} (id bigint unsigned NOT NULL AUTO_INCREMENT,provider varchar(80) NOT NULL,user_id bigint unsigned NOT NULL DEFAULT 0,ok tinyint(1) NOT NULL DEFAULT 0,code varchar(80) NOT NULL DEFAULT '',duration_ms int unsigned NOT NULL DEFAULT 0,created_at datetime NOT NULL,PRIMARY KEY(id),KEY provider_created(provider,created_at),KEY user_created(user_id,created_at)) {$c};"); }
 public static function register(): void { add_action('admin_menu',[self::class,'menu']); }
 public static function record(string $provider,bool $ok,string $code,int $duration_ms): void { global $wpdb; $wpdb->insert(self::table(),['provider'=>sanitize_key($provider),'user_id'=>get_current_user_id(),'ok'=>$ok?1:0,'code'=>sanitize_key($code),'duration_ms'=>max(0,$duration_ms),'created_at'=>current_time('mysql')],['%s','%d','%d','%s','%d','%s']); }
 public static function recent(int $limit=50): array { global $wpdb; $limit=max(1,min(200,$limit)); return $wpdb->get_results('SELECT * FROM '.self::table().' ORDER BY id DESC LIMIT '.(int)$limit,ARRAY_A); }
 public static function menu(): void { add_submenu_page('options-general.php','Provider Test Log','Provider Test Log','manage_options','avanik-provider-test-log',[self::class,'render']); }
 public static function render(): void { if(!current_user_can('manage_options'))return; $rows=self::recent(); echo '<div class="wrap" dir="rtl"><h1>گزارش تست اتصال Providerها</h1><table class="widefat striped"><thead><tr><th>ID</th><th>Provider</th><th>User</th><th>Result</th><th>Code</th><th>Duration</th><th>Date</th></tr></thead><tbody>'; foreach($rows as $r){echo '<tr><td>'.(int)$r['id'].'</td><td><code>'.esc_html($r['provider']).'</code></td><td>'.(int)$r['user_id'].'</td><td>'.(!empty($r['ok'])?'OK':'FAILED').'</td><td><code>'.esc_html($r['code']).'</code></td><td>'.(int)$r['duration_ms'].' ms</td><td>'.esc_html($r['created_at']).'</td></tr>';} echo '</tbody></table></div>'; }
}
