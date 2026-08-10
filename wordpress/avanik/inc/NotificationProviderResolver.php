<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class NotificationProviderResolver {
 public static function register(): void { add_filter('avanik_notification_provider_for_channel',[self::class,'resolve'],10,5); }
 public static function resolve(string $current,string $channel,string $event,array $payload,int $user_id): string { $providers=NotificationProviderSettings::get(); if(!$providers)return $current?:'core'; $candidates=[]; foreach($providers as $id=>$p){if(!is_array($p)||empty($p['enabled']))continue;if(!in_array($channel,(array)($p['channels']??[]),true))continue;if(NotificationProviderHealth::is_disabled((string)$id,$channel))continue;$adapter=(string)($p['adapter']??'');if($adapter==='')continue;$candidates[]=['id'=>(string)$id,'priority'=>max(1,min(100,absint($p['priority']??50)))];} if(!$candidates)return $current?:'core'; usort($candidates,static fn($a,$b)=>$a['priority']<=>$b['priority']?:strcmp($a['id'],$b['id'])); return (string)$candidates[0]['id']; }
 public static function available(string $channel): array { $out=[];foreach(NotificationProviderSettings::get() as $id=>$p){if(is_array($p)&&!empty($p['enabled'])&&in_array($channel,(array)($p['channels']??[]),true)&&!NotificationProviderHealth::is_disabled((string)$id,$channel))$out[]=['id'=>(string)$id,'name'=>(string)($p['name']??$id),'priority'=>(int)($p['priority']??50)];}return $out; }
}