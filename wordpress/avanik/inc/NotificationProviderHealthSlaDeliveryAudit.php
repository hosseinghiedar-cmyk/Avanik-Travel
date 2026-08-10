<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class NotificationProviderHealthSlaDeliveryAudit {
 public static function register(): void {}
 public static function summary(int $days=30): array {
  global $wpdb; $days=max(1,min(365,$days)); $t=NotificationDeliveryLog::table_name(); $since=date('Y-m-d H:i:s',current_time('timestamp')-($days*DAY_IN_SECONDS));
  $base=$wpdb->prepare(" FROM {$t} WHERE event=%s AND created_at >= %s",'provider_health_sla_breach',$since);
  $attempts=(int)$wpdb->get_var('SELECT COUNT(*)'.$base);
  $sent=(int)$wpdb->get_var("SELECT COUNT(*){$base} AND status='sent'");
  $failed=(int)$wpdb->get_var("SELECT COUNT(*){$base} AND status IN ('failed','dead')");
  $queued=(int)$wpdb->get_var("SELECT COUNT(*){$base} AND status IN ('attempted','queued','retry')");
  return ['days'=>$days,'attempts'=>$attempts,'sent'=>$sent,'failed'=>$failed,'pending'=>$queued,'success_rate'=>$attempts?round(($sent/$attempts)*100,1):0];
 }
 public static function recent(int $limit=25): array {
  global $wpdb; $t=NotificationDeliveryLog::table_name(); $limit=max(1,min(100,$limit));
  return $wpdb->get_results($wpdb->prepare("SELECT id,queue_id,event,role,user_id,channel,provider,status,attempt,error_code,error_message,created_at FROM {$t} WHERE event=%s ORDER BY id DESC LIMIT %d",'provider_health_sla_breach',$limit),ARRAY_A);
 }
}