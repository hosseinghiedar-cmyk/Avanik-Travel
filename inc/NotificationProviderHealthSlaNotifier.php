<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class NotificationProviderHealthSlaNotifier {
 private const CRON='avanik_provider_health_sla_check';
 private const OPTION='avanik_provider_health_sla_notification_log';
 private const DEFAULT_COOLDOWN=900;
 public static function register(): void {
  add_filter('cron_schedules',[self::class,'cron']);
  add_action(self::CRON,[self::class,'check']);
  add_filter('avanik_notification_recipients',[self::class,'recipients'],5,2);
  add_action('init',[self::class,'schedule']);
 }
 public static function cron(array $s): array { if(!isset($s['avanik_five_minutes']))$s['avanik_five_minutes']=['interval'=>300,'display'=>'Avanik every five minutes']; return $s; }
 public static function schedule(): void { if(!wp_next_scheduled(self::CRON))wp_schedule_event(time()+300,'avanik_five_minutes',self::CRON); }
 public static function check(): array {
  $result=NotificationProviderHealthSla::evaluate(); $log=(array)get_option(self::OPTION,[]); $now=time(); $sent=0;$deduped=0;
  foreach((array)($result['breaches']??[]) as $breach){$incident=(string)($breach['incident_key']??'');$type=(string)($breach['type']??'');$provider=(string)($breach['provider']??'');if($incident===''||$type==='')continue;$key=$incident.':'.$type;$last=(int)($log[$key]??0);if($last>0&&($now-$last)<self::DEFAULT_COOLDOWN){$deduped++;continue;}$log[$key]=$now;NotificationCenter::enqueue('provider_health_sla_breach',['provider'=>$provider,'incident_key'=>$incident,'type'=>$type,'threshold_seconds'=>(int)($breach['threshold_seconds']??0),'actual_seconds'=>(int)($breach['actual_seconds']??0),'status'=>'breached']);$sent++;}
  if(count($log)>500){arsort($log,SORT_NUMERIC);$log=array_slice($log,0,500,true);}update_option(self::OPTION,$log,false);return ['sent'=>$sent,'deduplicated'=>$deduped,'breaches'=>(int)($result['breach_count']??0),'checked_at'=>$now];
 }
 public static function recipients(array $recipients,string $event,array $payload=[]): array { if($event!=='provider_health_sla_breach')return $recipients; foreach(get_users(['role'=>'administrator','fields'=>['ID']]) as $admin)$recipients['admin_'.(int)$admin->ID]=['user_id'=>(int)$admin->ID,'channels'=>['internal'=>true]]; return $recipients; }
 public static function recent_log(int $limit=50): array { $log=(array)get_option(self::OPTION,[]);arsort($log,SORT_NUMERIC);$out=[];foreach(array_slice($log,0,max(1,min(200,$limit)),true) as $key=>$ts)$out[]=['key'=>(string)$key,'timestamp'=>(int)$ts];return $out; }
}