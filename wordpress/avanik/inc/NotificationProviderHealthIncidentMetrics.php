<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class NotificationProviderHealthIncidentMetrics {
 public static function register(): void { add_filter('avanik_notification_provider_health_incident_metrics',[self::class,'metrics'],10,2); }
 public static function metrics(string $provider='',array $context=[]): array {
  $rows=NotificationProviderHealthIncident::recent(200); $now=time(); $total=0;$resolved=0;$open=0;$downtime=0;$ackCount=0;$ackWait=0;$resolveWait=0;$byProvider=[];
  foreach($rows as $row){ if($provider!=='' && (string)($row['provider']??'')!==$provider)continue; $total++; $opened=(int)($row['opened_at']??0);$resolvedAt=(int)($row['resolved_at']??0);$ackAt=(int)($row['acknowledged_at']??0); if($resolvedAt>0){$resolved++;$downtime+=max(0,$resolvedAt-$opened);$resolveWait+=max(0,$resolvedAt-$opened);}else{$open++;$downtime+=max(0,$now-$opened);} if($ackAt>0){$ackCount++;$ackWait+=max(0,$ackAt-$opened);} $p=(string)($row['provider']??'');if($p!==''){$byProvider[$p]=($byProvider[$p]??0)+1;} }
  return ['provider'=>$provider,'incident_count'=>$total,'open_incidents'=>$open,'resolved_incidents'=>$resolved,'total_downtime_seconds'=>$downtime,'avg_downtime_seconds'=>$resolved?intdiv($downtime,$resolved):0,'acknowledged_incidents'=>$ackCount,'avg_acknowledgement_seconds'=>$ackCount?intdiv($ackWait,$ackCount):0,'avg_resolution_seconds'=>$resolved?intdiv($resolveWait,$resolved):0,'incidents_by_provider'=>$byProvider,'generated_at'=>$now];
 }
 public static function format_duration(int $seconds): string { $seconds=max(0,$seconds);$h=intdiv($seconds,3600);$m=intdiv($seconds%3600,60);$s=$seconds%60;return $h>0?sprintf('%dh %02dm',$h,$m):sprintf('%dm %02ds',$m,$s); }
}