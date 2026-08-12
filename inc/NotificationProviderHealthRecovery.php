<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class NotificationProviderHealthRecovery {
 private const OPTION='avanik_provider_health_recovery_state';
 public static function register(): void { add_filter('avanik_notification_provider_health_recovery',[self::class,'evaluate'],10,3); add_action('admin_init',[self::class,'maybe_migrate']); }
 public static function maybe_migrate(): void { if(false===get_option(self::OPTION,false)) add_option(self::OPTION,[],'','no'); }
 public static function evaluate(string $provider,array $summary,array $context=[]): array {
  self::maybe_migrate(); $state=(array)get_option(self::OPTION,[]); $status=(string)($summary['status']??'unknown'); $previous=(string)($state[$provider]['status']??'unknown'); $recovered=in_array($previous,['unhealthy','slow','attention'],true)&&$status==='healthy';
  $result=['provider'=>$provider,'status'=>$status,'previous_status'=>$previous,'recovered'=>$recovered,'recovery_code'=>$recovered?'provider_recovered':''];
  $state[$provider]=['status'=>$status,'timestamp'=>time()]; update_option(self::OPTION,$state,false);
  if($recovered){ $result['severity']='info'; $result['message']='Provider health has recovered and is healthy again.'; $result['recovery_key']=$provider.':provider_recovered'; $result['routed']=false; $result=apply_filters('avanik_notification_provider_health_recovery_route',$result,$context); }
  return $result;
 }
 public static function state(string $provider): array { $state=(array)get_option(self::OPTION,[]); return (array)($state[$provider]??[]); }
}