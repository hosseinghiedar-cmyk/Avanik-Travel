<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class NotificationProviderHealthAlert {
 public static function register(): void { add_filter('avanik_notification_provider_health_alert',[self::class,'evaluate'],10,3); }
 public static function evaluate(string $provider,array $summary,array $context=[]): array {
  $status=(string)($summary['status']??'unknown'); $alerts=[];
  if($status==='unhealthy')$alerts[]=['code'=>'provider_unhealthy','severity'=>'critical','message'=>'Provider connection test is failing.'];
  elseif($status==='attention')$alerts[]=['code'=>'credentials_missing','severity'=>'warning','message'=>'Provider is enabled but credentials are missing.'];
  elseif($status==='slow')$alerts[]=['code'=>'provider_slow','severity'=>'warning','message'=>'Provider response time exceeded the configured threshold.'];
  elseif($status==='disabled')$alerts[]=['code'=>'provider_disabled','severity'=>'info','message'=>'Provider is disabled.'];
  $duration=$summary['duration_ms']??null;
  if($duration!==null&&isset($summary['slow_threshold_ms'])&&(int)$duration>(int)$summary['slow_threshold_ms']&&$status!=='unhealthy'&&$status!=='slow')$alerts[]=['code'=>'provider_slow','severity'=>'warning','message'=>'Provider response time exceeded the configured threshold.'];
  $last=(string)($context['last_alert_code']??''); $out=[];
  foreach($alerts as $alert){$alert['provider']=$provider;$alert['dedupe_key']=$provider.':'.$alert['code'];$alert['repeat']=($last===$alert['code']);$alert=apply_filters('avanik_notification_provider_health_alert_record',$alert,$context);$out[]=$alert;}
  return ['provider'=>$provider,'alerts'=>$out,'has_alert'=>!empty($out)];
 }
}