<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class NotificationProviderHealthDashboard {
 public static function register(): void { add_action('admin_menu',[self::class,'menu']); }
 public static function menu(): void { add_submenu_page('options-general.php','Provider Health','Provider Health','manage_options','avanik-provider-health',[self::class,'render']); }
 public static function render(): void {
  if(!current_user_can('manage_options'))return;
  $providers=NotificationProviderSettings::get(); $logs=[];
  if(class_exists(NotificationProviderTestLog::class)&&method_exists(NotificationProviderTestLog::class,'recent'))$logs=NotificationProviderTestLog::recent(100);
  $latest=[]; foreach((array)$logs as $log){$id=(string)($log['provider']??$log['provider_id']??'');if($id!==''&&!isset($latest[$id]))$latest[$id]=$log;}
  $alerts=class_exists(NotificationProviderHealthAlertLog::class)?NotificationProviderHealthAlertLog::recent(50):[];
  echo '<div class="wrap" dir="rtl"><h1>سلامت Providerهای اعلان</h1><p>وضعیت تنظیمات، Credentials، تست اتصال، سلامت و Alertهای اخیر Providerها.</p>';
  echo '<table class="widefat striped"><thead><tr><th>Provider</th><th>Channel</th><th>سلامت</th><th>Credentials</th><th>آخرین تست</th><th>زمان پاسخ</th></tr></thead><tbody>';
  foreach($providers as $id=>$p){$id=(string)$id;$log=$latest[$id]??[];$summary=NotificationProviderHealthSummary::summary($id,['last_test'=>$log]);$label=NotificationProviderHealthSummary::status_label($summary['status']);$duration=$summary['duration_ms']!==null?(int)$summary['duration_ms'].' ms':'—';echo '<tr><td><strong>'.esc_html($summary['name']).'</strong><br><code>'.esc_html($id).'</code></td><td>'.esc_html(implode(', ',(array)$summary['channels'])?:'—').'</td><td>'.esc_html($label).'</td><td>'.($summary['credentials']?'✓':'—').'</td><td>'.($log?esc_html((($log['result']??$log['status']??'')==='success')?'موفق':'ناموفق'):'تست نشده').'</td><td>'.esc_html($duration).'</td></tr>';}
  if(!$providers)echo '<tr><td colspan="6">Providerی تعریف نشده است.</td></tr>'; echo '</tbody></table>';
  echo '<h2 style="margin-top:24px">Alert History</h2><table class="widefat striped"><thead><tr><th>Provider</th><th>Alert</th><th>Severity</th><th>Message</th><th>Time</th></tr></thead><tbody>';
  foreach($alerts as $alert){$time=!empty($alert['timestamp'])?wp_date(get_option('date_format').' '.get_option('time_format'),(int)$alert['timestamp']):'—';echo '<tr><td><code>'.esc_html($alert['provider']??'').'</code></td><td>'.esc_html($alert['code']??'').'</td><td>'.esc_html($alert['severity']??'').'</td><td>'.esc_html($alert['message']??'').'</td><td>'.esc_html($time).'</td></tr>';}
  if(!$alerts)echo '<tr><td colspan="5">Alert ثبت‌شده‌ای وجود ندارد.</td></tr>'; echo '</tbody></table></div>';
 }
}