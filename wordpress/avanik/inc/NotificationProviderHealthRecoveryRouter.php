<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class NotificationProviderHealthRecoveryRouter {
 public static function register(): void { add_filter('avanik_notification_provider_health_recovery_route',[self::class,'route'],10,2); add_filter('avanik_notification_recipients',[self::class,'recipients'],5,2); }
 public static function route(array $recovery,array $context=[]): array { if(empty($recovery['recovered'])) return $recovery; NotificationCenter::enqueue('provider_health_recovered',['provider'=>(string)($recovery['provider']??''),'code'=>'provider_recovered','severity'=>'info','message'=>(string)($recovery['message']??'Provider health has recovered.'),'recovery_key'=>(string)($recovery['recovery_key']??'')]); $recovery['routed']=true; return $recovery; }
 public static function recipients(array $recipients,string $event,array $payload=[]): array { if($event!=='provider_health_recovered')return $recipients; foreach(get_users(['role'=>'administrator','fields'=>['ID']]) as $admin)$recipients['admin_'.(int)$admin->ID]=['user_id'=>(int)$admin->ID,'channels'=>['internal'=>true]]; return $recipients; }
}