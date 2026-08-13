<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class AdminMenuOrganizer {
  public static function register(): void {
    add_action('admin_menu',[self::class,'organize'],9999);
    add_filter('gettext',[self::class,'translate_admin_text'],20,3);
  }

  public static function organize(): void {
    if (!current_user_can('manage_options')) return;
    global $menu,$submenu;

    // Avanik gets its own Persian top-level areas; provider/notification tools never live under Settings.
    if (!isset($menu)) $menu=[];
    $provider_exists=false;$notification_exists=false;
    foreach((array)$menu as $item){
      if(($item[2]??'')==='avanik-providers')$provider_exists=true;
      if(($item[2]??'')==='avanik-notifications')$notification_exists=true;
    }
    if(!$provider_exists) add_menu_page('پروایدرهای آوانیک','پروایدرها','manage_options','avanik-providers',[ProviderAdmin::class,'render'],'dashicons-networking',26);
    if(!$notification_exists) add_menu_page('اعلان‌های آوانیک','اعلان‌ها','manage_options','avanik-notifications',[NotificationCenter::class,'page'],'dashicons-bell',27);

    // Remove the old theme/provider/notification entries from Settings, including legacy slugs.
    $legacy=['avanik-theme-settings','avanik_theme_settings','avanik-notifications','avanik-providers'];
    foreach($legacy as $slug) remove_submenu_page('options-general.php',$slug);
    foreach((array)($submenu['options-general.php']??[]) as $entry){
      $slug=strtolower((string)($entry[2]??''));
      $label=strtolower((string)($entry[0]??''));
      if(strpos($slug,'avanik')!==false || strpos($label,'provider')!==false || strpos($label,'notification')!==false || strpos($label,'اعلان')!==false || strpos($label,'پروایدر')!==false){
        remove_submenu_page('options-general.php',(string)($entry[2]??''));
      }
    }

    foreach($menu as &$item){
      if(($item[2]??'')==='avanik-providers')$item[0]='پروایدرها';
      if(($item[2]??'')==='avanik-notifications')$item[0]='اعلان‌ها';
    }
    unset($item);

    $entries=[];
    foreach((array)($submenu['options-general.php']??[]) as $entry){
      $slug=(string)($entry[2]??'');
      if(!$slug)continue;
      $needle=strtolower($slug.' '.($entry[0]??''));
      if(strpos($needle,'notification')===false && strpos($needle,'provider')===false && strpos($needle,'sla-escalation')===false)continue;
      $entries[$slug]=[$entry[1]??'manage_options',$entry[2]??$slug,$entry[3]??null,$entry[0]??''];
    }
    foreach($entries as $slug=>$entry){
      remove_submenu_page('options-general.php',$slug);
      $lower=strtolower($slug);
      $parent=(strpos($lower,'provider')!==false && strpos($lower,'notification')===false)?'avanik-providers':'avanik-notifications';
      $label=self::label($slug,(string)$entry[3]);
      add_submenu_page($parent,$label,$label,$entry[0],$slug,$entry[2]?:function()use($entry){wp_die(esc_html($entry[3]));});
    }

    foreach(['avanik-providers','avanik-notifications'] as $parent){
      if(!isset($submenu[$parent])||!is_array($submenu[$parent]))continue;
      foreach($submenu[$parent] as &$item){$slug=(string)($item[2]??'');$item[0]=self::label($slug,(string)($item[0]??''));}
      unset($item);
    }
  }

  private static function label(string $slug,string $fallback): string {
    $map=[
      'avanik-providers'=>'مدیریت پروایدرها','avanik-bookings'=>'رزروها','avanik-payment-verification'=>'بررسی پرداخت','avanik-payment-settings'=>'تنظیمات پرداخت',
      'avanik-notifications'=>'مرکز اعلان‌ها','avanik-notification-dashboard'=>'داشبورد اعلان‌ها','avanik-notification-providers'=>'پروایدرهای اعلان','avanik-notification-templates'=>'قالب‌های اعلان','avanik-notification-delivery'=>'گزارش تحویل اعلان','avanik-notification-health'=>'سلامت اعلان‌ها','avanik-provider-health'=>'داشبورد سلامت پروایدرها','avanik-provider-health-sla'=>'تنظیمات SLA پروایدرها','avanik-provider-health-sla-compliance'=>'رعایت SLA پروایدرها','avanik-provider-credentials'=>'اعتبارنامه پروایدرها','avanik-provider-test-log'=>'گزارش تست پروایدرها',
      'avanik-sla-escalation-notification'=>'اعلان‌های SLA','avanik-sla-escalation-reliability-trend'=>'روند پایداری ارسال اعلان','avanik-sla-escalation-reliability-trend-health'=>'سلامت روند ارسال اعلان','avanik-sla-escalation-delivery-audit'=>'ممیزی تحویل اعلان',
    ];
    if(isset($map[$slug]))return $map[$slug];
    $label=$fallback;
    $label=str_ireplace(['Notification','Notifications','Provider','Providers','SLA','Dashboard','Delivery','Analytics','Health','Test Log','Settings','Credentials','Policy','Metrics','Trend','Audit','Payment Verification','Payment Settings','Bookings'],['اعلان','اعلان‌ها','پروایدر','پروایدرها','SLA','داشبورد','تحویل','تحلیل','سلامت','گزارش تست','تنظیمات','اعتبارنامه','سیاست','شاخص‌ها','روند','ممیزی','بررسی پرداخت','تنظیمات پرداخت','رزروها'],$label);
    return trim($label)?:'مدیریت آوانیک';
  }

  public static function translate_admin_text(string $translated,string $text,string $domain): string {
    if(!is_admin()) return $translated;
    $map=[
      'Avanik Notifications'=>'مرکز اعلان‌های آوانیک','Avanik Notification Dashboard'=>'داشبورد اعلان‌های آوانیک','Notification Dashboard'=>'داشبورد اعلان‌ها','Notification Providers'=>'پروایدرهای اعلان','Notification Templates'=>'قالب‌های اعلان','Provider Credentials'=>'اعتبارنامه پروایدرها','Provider Test Log'=>'گزارش تست پروایدرها','Provider Health'=>'سلامت پروایدرها','Provider Health SLA'=>'تنظیمات SLA پروایدرها','Provider Health SLA Compliance'=>'رعایت SLA پروایدرها','Notification Delivery Analytics'=>'گزارش تحویل اعلان','Delivery Analytics'=>'گزارش تحویل','Avanik Notification Delivery Analytics'=>'گزارش تحویل اعلان‌های آوانیک',
      'Channel'=>'کانال','Enabled'=>'فعال','Customer'=>'مشتری','Agency'=>'آژانس','Admin'=>'مدیر','Event'=>'رویداد','Role'=>'نقش','User'=>'کاربر','Status'=>'وضعیت','Attempts'=>'تلاش‌ها','Updated'=>'آخرین تغییر','Action'=>'عملیات','Apply'=>'اعمال فیلتر','Save'=>'ذخیره','Save Templates'=>'ذخیره قالب‌ها','Retry'=>'تلاش مجدد','Queue'=>'صف اعلان‌ها','Provider'=>'پروایدر','Providers'=>'پروایدرها','Credentials'=>'اعتبارنامه‌ها','Result'=>'نتیجه','Code'=>'کد','Duration'=>'مدت','Date'=>'تاریخ','Time'=>'زمان','Total'=>'کل','Sent'=>'ارسال‌شده','Failed/Dead'=>'ناموفق / نهایی','Success Rate'=>'نرخ موفقیت','By Channel'=>'بر اساس کانال','By Event'=>'بر اساس رویداد','Recent Delivery History'=>'تاریخچه اخیر تحویل','Alert History'=>'تاریخچه هشدارها','Message'=>'پیام','Severity'=>'شدت','Incident'=>'رخداد','Incidents'=>'رخدادها','SLA Checks'=>'بررسی‌های SLA','Breaches'=>'نقض‌ها','Compliance'=>'رعایت','Resolution'=>'رفع','Downtime'=>'قطعی','Period'=>'بازه زمانی','Days'=>'روزها','Filter'=>'فیلتر','English'=>'متن انگلیسی','Settings'=>'تنظیمات','Analytics'=>'تحلیل','Health'=>'سلامت','Dashboard'=>'داشبورد','Delivery'=>'تحویل','Test Log'=>'گزارش تست','Credentials'=>'اعتبارنامه','Policy'=>'سیاست','Metrics'=>'شاخص‌ها','Trend'=>'روند','Audit'=>'ممیزی','Payment Verification'=>'بررسی پرداخت','Payment Settings'=>'تنظیمات پرداخت','Bookings'=>'رزروها','OK'=>'موفق','FAILED'=>'ناموفق','No data'=>'داده‌ای وجود ندارد','No provider data available.'=>'اطلاعاتی برای پروایدرها وجود ندارد.'
    ];
    return $map[$text]??$translated;
  }
}
