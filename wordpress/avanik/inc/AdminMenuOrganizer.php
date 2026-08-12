<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class AdminMenuOrganizer {
  public static function register(): void { add_action('admin_menu',[self::class,'organize'],999); }
  public static function organize(): void {
    global $submenu;
    $moves=[];
    foreach((array)($submenu['options-general.php']??[]) as $entry){
      $title=(string)($entry[0]??'');$cap=(string)($entry[1]??'manage_options');$slug=(string)($entry[2]??'');$callback=$entry[3]??null;
      if(!$slug)continue;
      if(stripos($slug,'notification')!==false || stripos($slug,'provider')!==false || stripos($slug,'sla-escalation')!==false)$moves[]=[$title,$cap,$slug,$callback];
    }
    foreach($moves as $entry){
      foreach((array)$submenu['options-general.php'] as $i=>$candidate){if(($candidate[2]??'')===$entry[2])unset($submenu['options-general.php'][$i]);}
      $parent=(stripos($entry[2],'provider')!==false)?'avanik-providers':'avanik-notifications';
      $label=self::label($entry[2],$entry[0]);
      add_submenu_page($parent,$label,$label,$entry[1],$entry[2],$entry[3]?:function()use($entry){wp_die(esc_html($entry[0]));});
    }
  }
  private static function label(string $slug,string $fallback): string {
    $map=[
      'avanik-notification-dashboard'=>'داشبورد اعلان‌ها','avanik-notification-providers'=>'ارائه‌دهندگان اعلان','avanik-notification-templates'=>'قالب‌های اعلان','avanik-notification-delivery'=>'تحلیل تحویل اعلان‌ها','avanik-notification-health'=>'سلامت اعلان‌ها','avanik-provider-health'=>'داشبورد سلامت ارائه‌دهندگان','avanik-provider-health-sla'=>'SLA سلامت ارائه‌دهندگان','avanik-provider-credentials'=>'اعتبارنامه ارائه‌دهندگان','avanik-provider-test-log'=>'گزارش تست ارائه‌دهندگان',
      'avanik-sla-escalation-notification'=>'اعلان‌های SLA','avanik-sla-escalation-reliability-trend'=>'روند پایداری ارسال اعلان','avanik-sla-escalation-reliability-trend-health'=>'سلامت روند ارسال اعلان','avanik-sla-escalation-delivery-audit'=>'ممیزی تحویل اعلان','avanik-provider-sla-notification-health'=>'سلامت اعلان SLA','avanik-provider-sla-notification-health-alert'=>'هشدار سلامت اعلان SLA','avanik-provider-sla-notification-metrics'=>'شاخص‌های اعلان SLA','avanik-provider-sla-risk-notification-policy'=>'سیاست ریسک اعلان SLA','avanik-provider-sla-audit-notification-delivery'=>'ممیزی تحویل اعلان SLA','avanik-sla-notification-delivery-sla-trend'=>'روند SLA تحویل اعلان','avanik-sla-notification-delivery-sla'=>'SLA تحویل اعلان'
    ];
    if(isset($map[$slug]))return $map[$slug];
    $label=$fallback;
    $label=str_ireplace(['Notification','Notifications','Provider','Providers','SLA','Dashboard','Delivery','Analytics','Health','Test Log','Settings','Credentials','Policy','Metrics','Trend','Audit'],['اعلان','اعلان‌ها','ارائه‌دهنده','ارائه‌دهندگان','SLA','داشبورد','تحویل','تحلیل','سلامت','گزارش تست','تنظیمات','اعتبارنامه','سیاست','شاخص‌ها','روند','ممیزی'],$label);
    return trim($label)?:'مدیریت آوانیک';
  }
}
