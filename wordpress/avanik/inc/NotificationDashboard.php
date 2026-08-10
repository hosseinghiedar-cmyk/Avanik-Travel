<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class NotificationDashboard {
 public static function register(): void { add_action('admin_menu',[self::class,'menu']); }
 public static function menu(): void { add_submenu_page('options-general.php','Avanik Notification Dashboard','Notification Dashboard','manage_options','avanik-notification-dashboard',[self::class,'page']); }
 public static function page(): void {
  if(!current_user_can('manage_options'))return;
  global $wpdb;
  $queue=$wpdb->prefix.'avanik_notification_queue';
  $inbox=$wpdb->prefix.'avanik_notifications';
  $days=max(1,min(90,absint($_GET['days']??7)));
  $since=date('Y-m-d H:i:s',current_time('timestamp')-($days*DAY_IN_SECONDS));
  $qstats=$wpdb->get_results($wpdb->prepare("SELECT status,COUNT(*) total FROM {$queue} WHERE created_at >= %s GROUP BY status",$since),ARRAY_A);
  $cstats=$wpdb->get_results($wpdb->prepare("SELECT channel,COUNT(*) total FROM {$queue} WHERE created_at >= %s GROUP BY channel",$since),ARRAY_A);
  $rstats=$wpdb->get_results($wpdb->prepare("SELECT role,COUNT(*) total FROM {$queue} WHERE created_at >= %s GROUP BY role",$since),ARRAY_A);
  $unread=(int)$wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$inbox} WHERE read_at IS NULL AND created_at >= %s",$since));
  $dead=(int)$wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$queue} WHERE status='dead' AND created_at >= %s",$since));
  $failed=(int)$wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$queue} WHERE status='failed' AND created_at >= %s",$since));
  $queued=(int)$wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$queue} WHERE status IN ('queued','retry') AND created_at >= %s",$since));
  echo '<div class="wrap"><h1>Avanik Notification Dashboard</h1><form method="get"><input type="hidden" name="page" value="avanik-notification-dashboard"><label>Period <select name="days">'; foreach([1,7,14,30,90] as $d)echo '<option value="'.$d.'" '.selected($days,$d,false).'>Last '.$d.' day(s)</option>'; echo '</select></label> <button class="button">Apply</button></form><div style="display:grid;grid-template-columns:repeat(4,minmax(160px,1fr));gap:12px;margin:18px 0">'; self::card('Queued / Retry',$queued); self::card('Failed',$failed); self::card('Dead',$dead); self::card('Unread',$unread); echo '</div><div style="display:grid;grid-template-columns:repeat(3,minmax(240px,1fr));gap:20px"><section><h2>Status</h2>'; self::table(['Status','Count'],$qstats,'status'); echo '</section><section><h2>Channel</h2>'; self::table(['Channel','Count'],$cstats,'channel'); echo '</section><section><h2>Role</h2>'; self::table(['Role','Count'],$rstats,'role'); echo '</section></div><p><strong>Scope:</strong> metrics are limited to the selected period. Delivery providers remain outside this dashboard; provider-specific metrics can be added through the delivery hook.</p></div>'; }
 private static function card(string $label,int $value): void { echo '<div style="background:#fff;border:1px solid #ccd0d4;padding:16px"><div style="color:#646970">'.esc_html($label).'</div><strong style="font-size:28px">'.number_format_i18n($value).'</strong></div>'; }
 private static function table(array $headers,array $rows,string $key): void { echo '<table class="widefat striped"><thead><tr>'; foreach($headers as $h)echo '<th>'.esc_html($h).'</th>'; echo '</tr></thead><tbody>'; if(!$rows){echo '<tr><td colspan="2">No data</td></tr>';} foreach($rows as $r)echo '<tr><td>'.esc_html($r[$key]).'</td><td>'.number_format_i18n((int)$r['total']).'</td></tr>'; echo '</tbody></table>'; }
}