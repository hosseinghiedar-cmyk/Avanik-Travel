<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class NotificationProviderHealthSlaSettings {
 private const OPTION='avanik_provider_health_sla_policy';
 private const DEFAULTS=['acknowledgement_seconds'=>900,'resolution_seconds'=>3600,'downtime_seconds'=>3600];
 public static function register(): void {
  add_filter('avanik_notification_provider_health_sla_policy',[self::class,'policy'],10,2);
  add_action('admin_menu',[self::class,'menu']);
  add_action('admin_post_avanik_save_provider_health_sla',[self::class,'save']);
 }
 public static function policy(array $configured=[],string $provider=''): array {
  $saved=get_option(self::OPTION,[]); $saved=is_array($saved)?$saved:[];
  $out=[];
  foreach(self::DEFAULTS as $key=>$default){$value=$saved[$key]??$default;$out[$key]=max(0,is_numeric($value)?(int)$value:$default);}
  return $out;
 }
 public static function defaults(): array { return self::DEFAULTS; }
 public static function menu(): void { add_submenu_page('options-general.php','Provider Health SLA','Provider Health SLA','manage_options','avanik-provider-health-sla',[self::class,'render']); }
 public static function render(): void {
  if(!current_user_can('manage_options'))return;
  $p=self::policy();
  echo '<div class="wrap" dir="rtl"><h1>تنظیمات SLA سلامت Provider</h1><p>حد زمانی نقض SLA برای Incidentهای Provider را تنظیم کنید. مقدار صفر یعنی آن معیار غیرفعال است.</p>';
  if(isset($_GET['updated']))echo '<div class="notice notice-success is-dismissible"><p>تنظیمات SLA ذخیره شد.</p></div>';
  echo '<form method="post" action="'.esc_url(admin_url('admin-post.php')).'"><input type="hidden" name="action" value="avanik_save_provider_health_sla">'.wp_nonce_field('avanik_save_provider_health_sla','_wpnonce',true,false);
  echo '<table class="form-table" role="presentation"><tbody>';
  self::field('acknowledgement_seconds','مهلت Acknowledgement (ثانیه)',(int)$p['acknowledgement_seconds']);
  self::field('resolution_seconds','مهلت Resolution (ثانیه)',(int)$p['resolution_seconds']);
  self::field('downtime_seconds','حد Downtime (ثانیه)',(int)$p['downtime_seconds']);
  echo '</tbody></table><p class="submit"><button type="submit" class="button button-primary">ذخیره تنظیمات SLA</button></p></form>';
  echo '<h2>مقادیر فعلی</h2><table class="widefat striped"><thead><tr><th>معیار</th><th>زمان</th></tr></thead><tbody>';
  echo '<tr><td>Acknowledgement</td><td>'.esc_html(NotificationProviderHealthSla::format_duration((int)$p['acknowledgement_seconds'])).'</td></tr>';
  echo '<tr><td>Resolution</td><td>'.esc_html(NotificationProviderHealthSla::format_duration((int)$p['resolution_seconds'])).'</td></tr>';
  echo '<tr><td>Downtime</td><td>'.esc_html(NotificationProviderHealthSla::format_duration((int)$p['downtime_seconds'])).'</td></tr></tbody></table></div>';
 }
 private static function field(string $name,string $label,int $value): void { echo '<tr><th scope="row"><label for="'.esc_attr($name).'">'.esc_html($label).'</label></th><td><input class="small-text" type="number" min="0" step="1" id="'.esc_attr($name).'" name="'.esc_attr($name).'" value="'.esc_attr((string)$value).'"> <span class="description">0 = غیرفعال</span></td></tr>'; }
 public static function save(): void {
  if(!current_user_can('manage_options'))wp_die('Unauthorized');
  check_admin_referer('avanik_save_provider_health_sla');
  $data=[]; foreach(array_keys(self::DEFAULTS) as $key){$raw=isset($_POST[$key])?wp_unslash($_POST[$key]):self::DEFAULTS[$key];$data[$key]=max(0,(int)$raw);}
  update_option(self::OPTION,$data,false);
  wp_safe_redirect(add_query_arg(['page'=>'avanik-provider-health-sla','updated'=>'1'],admin_url('options-general.php')));exit;
 }
}