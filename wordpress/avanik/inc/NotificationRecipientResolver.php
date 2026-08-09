<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class NotificationRecipientResolver {
 public static function register(): void { add_filter('avanik_notification_recipients',[self::class,'resolve'],20,3); }
 public static function resolve(string $event,array $payload,array $context=[]): array { $recipients=NotificationPreferences::recipients($event,$payload,$context); foreach($recipients as $role=>&$r){ $r['channels']=array_filter($r['channels'],static fn($enabled)=>$enabled); } unset($r); return $recipients; }
 public static function for_refund(array $refund): array { $payload=['customer_id'=>absint($refund['customer_user_id']??0),'agency_id'=>absint($refund['agency_user_id']??0),'admin_id'=>absint($refund['admin_user_id']??0)]; return self::resolve('refund_status',$payload); }
}