<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class NotificationProviderHealthSlaReport {
 public static function register(): void {}
 public static function rows(): array {
  $providers=NotificationProviderSettings::get();
  if(!$providers)$providers=['default'=>['name'=>'Default / Core','adapter'=>'core','enabled'=>1]];
  $rows=[];
  foreach($providers as $id=>$p){
   $id=(string)$id;
   $policy=NotificationProviderHealthSla::policy($id);
   $metrics=NotificationProviderHealthIncidentMetrics::metrics($id);
   $eval=NotificationProviderHealthSla::evaluate($id);
   $breaches=(array)($eval['breaches']??[]);$counts=['acknowledgement'=>0,'resolution'=>0,'downtime'=>0];
   foreach($breaches as $b){$type=(string)($b['type']??'');if(isset($counts[$type]))$counts[$type]++;}
   $rows[]=['provider'=>$id,'name'=>(string)($p['name']??$id),'enabled'=>!empty($p['enabled']),'policy'=>$policy,'incident_count'=>(int)($metrics['incident_count']??0),'open_incidents'=>(int)($metrics['open_incidents']??0),'resolved_incidents'=>(int)($metrics['resolved_incidents']??0),'downtime_seconds'=>(int)($metrics['total_downtime_seconds']??0),'avg_resolution_seconds'=>(int)($metrics['avg_resolution_seconds']??0),'breach_count'=>count($breaches),'breaches'=>$counts];
  }
  return $rows;
 }
}