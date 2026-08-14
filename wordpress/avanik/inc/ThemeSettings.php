<?php
namespace Avanik;
if (!defined('ABSPATH')) exit;
final class ThemeSettings {
    public static function boot(): void {add_action('admin_menu',[self::class,'menus'],9);add_action('admin_init',[self::class,'register']);add_action('admin_enqueue_scripts',[self::class,'assets']);}
    public static function register(): void {
        $keys=['navy','gold','phone','instagram','telegram','whatsapp','linkedin','logo','hero','hero_eyebrow','hero_title_prefix','hero_title_accent','hero_subtitle','hero_button'];
        foreach($keys as $key){$type=in_array($key,['navy','gold'],true)?'sanitize_hex_color':'sanitize_text_field';register_setting('avanik_theme_options','avanik_'.$key,['sanitize_callback'=>$type]);}
    }
    public static function assets($hook): void {if(strpos($hook,'avanik')===false)return;wp_enqueue_media();wp_enqueue_style('avanik-admin',get_template_directory_uri().'/assets/css/avanik-admin.css',['dashicons'],'0.4.2');wp_enqueue_script('avanik-admin',get_template_directory_uri().'/assets/js/avanik-admin.js',['jquery'],'0.4.2',true);}
    public static function menus(): void {
        add_menu_page('آوانیک پرواز آسیا','آوانیک','manage_options','avanik-dashboard',[self::class,'dashboard'],'dashicons-airplane',3);
        add_submenu_page('avanik-dashboard','داشبورد آوانیک','داشبورد','manage_options','avanik-dashboard',[self::class,'dashboard']);
        add_submenu_page('avanik-dashboard','تنظیمات قالب آوانیک','تنظیمات قالب','manage_options','avanik-settings',[self::class,'settings']);
        add_menu_page('ارائه‌دهندگان','ارائه‌دهندگان','manage_options','avanik-providers',[self::class,'providers'],'dashicons-networking',4);
        add_menu_page('اعلان‌ها','اعلان‌ها','manage_options','avanik-notifications',[self::class,'notifications'],'dashicons-bell',5);
    }
    private static function shell(string $title,string $content): void {echo '<div class="wrap avanik-admin-wrap" dir="rtl"><h1>'.esc_html($title).'</h1><div class="avanik-admin-card">'.$content.'</div></div>';}
    public static function dashboard(): void {self::shell('آوانیک پرواز آسیا','<p>پنل اختصاصی آوانیک. تنظیمات ظاهری قالب در <b>آوانیک ← تنظیمات قالب</b> قرار دارد.</p>');}
    public static function settings(): void {self::shell('تنظیمات قالب آوانیک','<p>تنظیمات ظاهری قالب از نسخه قبلی حفظ شده است.</p>');}
    public static function providers(): void {self::shell('ارائه‌دهندگان','<p>مدیریت ارائه‌دهندگان خدمات سفر.</p>');}
    public static function notifications(): void {self::shell('اعلان‌ها','<p>اعلان‌ها و وضعیت سرویس‌ها.</p>');}
}
