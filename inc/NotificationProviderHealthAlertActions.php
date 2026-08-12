<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class NotificationProviderHealthAlertActions {
 public static function register(): void { add_action('admin_post_avanik_ack_provider_health_alert',[self::class,'acknowledge']); }
 public static function acknowledge(): void {
  if(!current_user_can('manage_options'))wp_die('Forbidden',403);
  check_admin_referer('avanik_ack_provider_health_alert');
  $key=sanitize_text_field(wp_unslash($_POST['dedupe_key']??''));
  if($key!=='' && class_exists(NotificationProviderHealthAlertLog::class))NotificationProviderHealthAlertLog::acknowledge($key,get_current_user_id());
  wp_safe_redirect(wp_get_referer()?:admin_url('options-general.php?page=avanik-provider-health')); exit;
 }
}