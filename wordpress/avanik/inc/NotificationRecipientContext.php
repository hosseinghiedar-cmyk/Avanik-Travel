<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class NotificationRecipientContext {
 public static function register(): void { add_filter('avanik_notification_recipients', [self::class,'resolve'], 30, 3); }
 public static function resolve(string $event,array $payload,array $context=[]): array {
  $ids=self::ids($event,$payload,$context);
  if(!$ids)return [];
  return apply_filters('avanik_notification_resolved_recipients',NotificationRecipientResolver::resolve($event,$ids,$context),$event,$ids,$context);
 }
 private static function ids(string $event,array $payload,array $context=[]): array {
  $ids=['customer_id'=>absint($payload['customer_id']??$context['customer_id']??0),'agency_id'=>absint($payload['agency_id']??$context['agency_id']??0),'admin_id'=>absint($payload['admin_id']??$context['admin_id']??0)];
  $booking_id=sanitize_text_field($payload['booking_id']??$context['booking_id']??'');
  $refund_id=sanitize_text_field($payload['refund_id']??$context['refund_id']??'');
  $ids=apply_filters('avanik_notification_booking_recipient_ids',$ids,$booking_id,$event,$payload,$context);
  if($refund_id)$ids=apply_filters('avanik_notification_refund_recipient_ids',$ids,$refund_id,$event,$payload,$context);
  return array_map('absint',$ids);
 }
}