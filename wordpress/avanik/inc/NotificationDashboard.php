<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class NotificationDashboard {
 public static function register(): void { add_action('admin_menu',[self::class,'menu'],46); }
 public static function menu(): void { add_menu_page('اعلان‌های آوانیک','اعلان‌ها','manage_options','avanik-notification-dashboard',[self::class,'page'],'dashicons-bell',3.2); }
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
  echo '<div class="wrap av-admin-wrap" dir="rtl"><div class="av-admin-panel-title"><span class="dashicons dashicons-bell"></span><div><h1>مرکز اعلان‌های آوانیک</h1><p>وضعیت اعلان‌ها، کانال‌های ارسال و صف تحویل را مدیریت و بررسی کنید.</p></div></div><form method="get"><input type="hidden" name="page" value="avanik-notification-dashboard"><label>بازه زمانی <select name="days">'; foreach([1,7,14,30,90] as $d)echo '<option value="'.$d.'" '.selected($days,$d,false).'>'.$d.' روز اخیر</option>'; echo '</select></label> <button class="button">اعمال</button></form><div class="av-admin-card"><div style="display:grid;grid-template-columns:repeat(4,minmax(160px,1fr));gap:12px">'; self::card('در صف / تلاش مجدد',$queued); self::card('ناموفق',$failed); self::card('متوقف‌شده',$dead); self::card('خوانده‌نشده',$unread); echo '</div></div><div class="av-admin-card"><div style="display:grid;grid-template-columns:repeat(3,minmax(240px,1fr));gap:20px"><section><h2>وضعیت</h2>'; self::table(['وضعیت','تعداد'],$qstats,'status'); echo '</section><section><h2>کانال</h2>'; self::table(['کانال','تعداد'],$cstats,'channel'); echo '</section><section><h2>نقش</h2>'; self::table(['نقش','تعداد'],$rstats,'role'); echo '</section></div><p><strong>محدوده گزارش:</strong> آمار فقط مربوط به بازه انتخاب‌شده است.</p></div></div>'; }
 private static function card(string $label,int $value): void { echo '<div style="background:#f8fbff;border:1px solid #e4e9ef;padding:16px;border-radius:14px"><div style="color:#687386">'.esc_html($label).'</div><strong style="font-size:28px;color:#072B5A">'.number_format_i18n($value).'</strong></div>'; }
 private static function table(array $headers,array $rows,string $key): void { echo '<table class="widefat striped"><thead><tr>'; foreach($headers as $h)echo '<th>'.esc_html($h).'</th>'; echo '</tr></thead><tbody>'; if(!$rows){echo '<tr><td colspan="2">داده‌ای وجود ندارد</td></tr>';} foreach($rows as $r){$value=$r[$key];$labels=['queued'=>'در صف','retry'=>'تلاش مجدد','failed'=>'ناموفق','dead'=>'متوقف‌شده','email'=>'ایمیل','sms'=>'پیامک','whatsapp'=>'واتساپ','internal'=>'اعلان داخلی','customer'=>'مشتری','agency'=>'آژانس','admin'=>'مدیر'];$value=$labels[$value]??$value;echo '<tr><td>'.esc_html($value).'</td><td>'.number_format_i18n((int)$r['total']).'</td></tr>';} echo '</tbody></table>'; }
}
