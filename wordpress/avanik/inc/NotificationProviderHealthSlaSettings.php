<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class NotificationProviderHealthSlaSettings {
 private const OPTION='avanik_provider_health_sla_policy';
 private const OVERRIDE_OPTION='avanik_provider_health_sla_overrides';
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
  $overrides=get_option(self::OVERRIDE_OPTION,[]); $overrides=is_array($overrides)?$overrides:[];
  if($provider!==''&&isset($overrides[$provider])&&is_array($overrides[$provider])){
   foreach(self::DEFAULTS as $key=>$default){
    if(array_key_exists($key,$overrides[$provider])&&$overrides[$provider][$key]!==''&&is_numeric($overrides[$provider][$key]))$out[$key]=max(0,(int)$overrides[$provider][$key]);
   }
  }
  return $out;
 }
 public static function defaults(): array { return self::DEFAULTS; }
 public static function overrides(): array { $v=get_option(self::OVERRIDE_OPTION,[]); return is_array($v)?$v:[]; }
 public static function menu(): void { add_submenu_page('options-general.php','Provider Health SLA','Provider Health SLA','manage_options','avanik-provider-health-sla',[self::class,'render']); }
 public static function render(): void {
  if(!current_user_can('manage_options'))return;
  $p=self::policy(); $overrides=self::overrides(); $providers=NotificationProviderSettings::get();
  echo '<div class="wrap" dir="rtl"><h1>تنظیمات SLA سلامت Provider</h1><p>حد زمانی پیش‌فرض و Override اختصاصی هر Provider را تنظیم کنید. مقدار خالی یعنی استفاده از مقدار پیش‌فرض؛ مقدار صفر یعنی آن معیار برای همان Provider غیرفعال است.</p>';
  if(isset($_GET['updated']))echo '<div class="notice notice-success is-dismissible"><p>تنظیمات SLA ذخیره شد.</p></div>';
  echo '<form method="post" action="'.esc_url(admin_url('admin-post.php')).'"><input type="hidden" name="action" value="avanik_save_provider_health_sla">'.wp_nonce_field('avanik_save_provider_health_sla','_wpnonce',true,false);
  echo '<h2>مقادیر پیش‌فرض</h2><table class="form-table" role="presentation"><tbody>';
  self::field('acknowledgement_seconds','مهلت Acknowledgement (ثانیه)',(int)$p['acknowledgement_seconds']);
  self::field('resolution_seconds','مهلت Resolution (ثانیه)',(int)$p['resolution_seconds']);
  self::field('downtime_seconds','حد Downtime (ثانیه)',(int)$p['downtime_seconds']);
  echo '</tbody></table>';
  if(!$providers)$providers=['default'=>['name'=>'Default / Core','adapter'=>'core','enabled'=>1]];
  echo '<h2>Override اختصاصی Providerها</h2><table class="widefat striped"><thead><tr><th>Provider</th><th>Acknowledgement (ثانیه)</th><th>Resolution (ثانیه)</th><th>Downtime (ثانیه)</th></tr></thead><tbody>';
  foreach($providers as $id=>$provider){$id=(string)$id;$base='overrides['.esc_attr($id).']';$o=(array)($overrides[$id]??[]);echo '<tr><td><strong>'.esc_html($provider['name']??$id).'</strong><br><code>'.esc_html($id).'</code></td>';foreach(array_keys(self::DEFAULTS) as $key){$value=array_key_exists($key,$o)?$o[$key]:'';echo '<td><input class="small-text" type="number" min="0" step="1" name="'.$base.'['.esc_attr($key).']" value="'.esc_attr((string)$value).'" placeholder="inherit"/></td>';}echo '</tr>';}
  echo '</tbody></table><p class="description">Overrideها فقط برای Provider همان ردیف اعمال می‌شوند و Policy فاز ۶۷ همچنان از همین Filter استفاده می‌کند.</p><p class="submit"><button type="submit" class="button button-primary">ذخیره تنظیمات SLA</button></p></form>';
  echo '<h2>مقادیر پیش‌فرض فعلی</h2><table class="widefat striped"><thead><tr><th>معیار</th><th>زمان</th></tr></thead><tbody>';
  foreach(self::DEFAULTS as $key=>$default){echo '<tr><td>'.esc_html(ucwords(str_replace('_',' ',$key))).'</td><td>'.esc_html(NotificationProviderHealthSla::format_duration((int)$p[$key])).'</td></tr>';}
  echo '</tbody></table></div>';
 }
 private static function field(string $name,string $label,int $value): void { echo '<tr><th scope="row"><label for="'.esc_attr($name).'">'.esc_html($label).'</label></th><td><input class="small-text" type="number" min="0" step="1" id="'.esc_attr($name).'" name="'.esc_attr($name).'" value="'.esc_attr((string)$value).'"> <span class="description">0 = غیرفعال</span></td></tr>'; }
 public static function save(): void {
  if(!current_user_can('manage_options'))wp_die('Unauthorized');
  check_admin_referer('avanik_save_provider_health_sla');
  $data=[]; foreach(array_keys(self::DEFAULTS) as $key){$raw=isset($_POST[$key])?wp_unslash($_POST[$key]):self::DEFAULTS[$key];$data[$key]=max(0,(int)$raw);}
  update_option(self::OPTION,$data,false);
  $clean=[]; $posted=isset($_POST['overrides'])&&is_array($_POST['overrides'])?wp_unslash($_POST['overrides']):[];
  foreach($posted as $provider=>$values){$provider=sanitize_key($provider);if(!$provider||!is_array($values))continue;$row=[];foreach(array_keys(self::DEFAULTS) as $key){if(!array_key_exists($key,$values))continue;$raw=$values[$key];if($raw==='')continue;$row[$key]=max(0,(int)$raw);}if($row)$clean[$provider]=$row;}
  update_option(self::OVERRIDE_OPTION,$clean,false);
  wp_safe_redirect(add_query_arg(['page'=>'avanik-provider-health-sla','updated'=>'1'],admin_url('options-general.php')));exit;
 }
}