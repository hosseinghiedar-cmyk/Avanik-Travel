<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class NotificationProviderHealthAlertEscalation {
 private const OPTION='avanik_provider_health_alert_escalation';
 public static function register(): void { add_action('admin_init',[self::class,'maybe_migrate']); add_filter('avanik_notification_provider_health_alert_escalate',[self::class,'evaluate'],10,3); }
 public static function maybe_migrate(): void { if(false===get_option(self::OPTION,false)) add_option(self::OPTION,['critical_after'=>0,'warning_after'=>2,'cooldown_seconds'=>900],'','no'); }
 public static function evaluate(array $alert,array $history=[],array $context=[]): array {
  self::maybe_migrate(); $settings=(array)get_option(self::OPTION,[]); $code=(string)($alert['code']??''); $severity=(string)($alert['severity']??'info'); $count=absint($history['consecutive_count']??1); $threshold=isset($settings[$severity.'_after'])?absint($settings[$severity.'_after']):($severity==='critical'?0:2); $alert['consecutive_count']=$count; $alert['escalation_threshold']=$threshold; $alert['escalated']=($severity==='critical')||($threshold>0&&$count>=$threshold); $alert['escalation_key']=((string)($alert['provider']??'')).':'.$code.':escalation'; $alert['cooldown_seconds']=max(60,absint($context['cooldown_seconds']??$settings['cooldown_seconds']??900)); return $alert;
 }
 public static function settings(): array { self::maybe_migrate(); return (array)get_option(self::OPTION,[]); }
}