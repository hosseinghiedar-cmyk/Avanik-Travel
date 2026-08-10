<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class NotificationProviderHealthAlertRouter {
 private const OPTION='avanik_provider_health_alert_route_log';
 public static function register(): void { add_filter('avanik_notification_provider_health_alert_route',[self::class,'route'],10,2); add_filter('avanik_notification_recipients',[self::class,'recipients'],5,2); add_action('admin_init',[self::class,'maybe_migrate']); }
 public static function maybe_migrate(): void { if(false===get_option(self::OPTION,false))add_option(self::OPTION,[],'','no'); }
 public static function route(array $alert,array $context=[]): array { if(empty($alert['escalated'])){$alert['routed']=false;return $alert;} self::maybe_migrate(); $key=(string)($alert['escalation_key']??''); $log=(array)get_option(self::OPTION,[]); $now=time(); $cooldown=max(60,absint($alert['cooldown_seconds']??900)); if($key!==''&&isset($log[$key])&&($now-(int)$log[$key])<$cooldown){$alert['routed']=false;$alert['route_deduplicated']=true;return $alert;} if($key!==''){$log[$key]=$now;update_option(self::OPTION,$log,false);} NotificationCenter::enqueue('provider_health_escalation',['provider'=>(string)($alert['provider']??''),'code'=>(string)($alert['code']??''),'severity'=>(string)($alert['severity']??'info'),'message'=>(string)($alert['message']??''),'escalation_key'=>$key]); $alert['routed']=true;$alert['route_deduplicated']=false;return $alert; }
 public static function recipients(array $recipients,string $event,array $payload=[]): array { if($event!=='provider_health_escalation')return $recipients; $admins=get_users(['role'=>'administrator','fields'=>['ID']]); foreach($admins as $admin){$recipients['admin_'.(int)$admin->ID]=['user_id'=>(int)$admin->ID,'channels'=>['internal'=>true]];} return $recipients; }
}