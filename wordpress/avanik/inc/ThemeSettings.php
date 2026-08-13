<?php
namespace Avanik;
if (!defined('ABSPATH')) exit;
final class ThemeSettings {
    public static function boot(): void {
        add_action('admin_menu',[self::class,'menus'],9);
        add_action('admin_init',[self::class,'register']);
        add_action('admin_enqueue_scripts',[self::class,'assets']);
    }
    public static function register(): void {
        $keys=['navy','gold','phone','instagram','telegram','whatsapp','linkedin','logo','hero'];
        foreach($keys as $key) register_setting('avanik_theme_options','avanik_'.$key,['sanitize_callback'=>'sanitize_text_field']);
    }
    public static function assets($hook): void {
        if(strpos($hook,'avanik')===false) return;
        wp_enqueue_media();
        wp_enqueue_style('avanik-admin',get_template_directory_uri().'/assets/css/avanik-admin.css',['dashicons'],'0.4.0');
        wp_enqueue_script('avanik-admin',get_template_directory_uri().'/assets/js/avanik-admin.js',['jquery'],'0.4.0',true);
    }
    public static function menus(): void {
        add_menu_page('آوانیک پرواز آسیا','آوانیک','manage_options','avanik-dashboard',[self::class,'dashboard'],'dashicons-airplane',3);
        add_submenu_page('avanik-dashboard','تنظیمات قالب','تنظیمات قالب','manage_options','avanik-settings',[self::class,'settings']);
        add_menu_page('ارائه‌دهندگان','ارائه‌دهندگان','manage_options','avanik-providers',[self::class,'providers'],'dashicons-networking',4);
        add_menu_page('اعلان‌ها','اعلان‌ها','manage_options','avanik-notifications',[self::class,'notifications'],'dashicons-bell',5);
    }
    private static function shell($title,$content): void { echo '<div class="wrap avanik-admin-wrap" dir="rtl"><h1>'.esc_html($title).'</h1><div class="avanik-admin-card">'.$content.'</div></div>'; }
    public static function dashboard(): void { self::shell('آوانیک پرواز آسیا','<p>پنل اختصاصی مدیریت قالب آوانیک. از منوی <b>تنظیمات قالب</b> ظاهر سایت را مدیریت کنید.</p>'); }
    public static function settings(): void {
        $fields=[
            ['navy','رنگ سرمه‌ای','#082B52'],['gold','رنگ طلایی','#F2B134'],['phone','شماره تماس','021-12345678'],
            ['instagram','لینک اینستاگرام','#'],['telegram','لینک تلگرام','#'],['whatsapp','لینک واتساپ','#'],['linkedin','لینک لینکدین','#'],
            ['logo','آدرس لوگو',get_template_directory_uri().'/assets/images/avanik-logo.svg'],['hero','آدرس تصویر اسلایدر/هرو',get_template_directory_uri().'/assets/images/hero-istanbul.svg']
        ];
        $html='<form method="post" action="options.php">'; ob_start(); settings_fields('avanik_theme_options'); $html.=ob_get_clean();
        foreach($fields as $f){
            $v=get_option('avanik_'.$f[0],$f[2]); $type=in_array($f[0],['navy','gold'])?'color':'text';
            $html.='<div class="avanik-field"><label>'.esc_html($f[1]).'</label><input class="avanik-setting-input" name="avanik_'.esc_attr($f[0]).'" value="'.esc_attr($v).'" type="'.$type.'">'.(in_array($f[0],['logo','hero'])?'<button type="button" class="button avanik-media" data-target="avanik_'.esc_attr($f[0]).'">انتخاب تصویر</button>':'').'</div>';
        }
        $html.='<p><button class="button button-primary button-hero" type="submit">ذخیره تنظیمات قالب</button></p></form>';
        self::shell('تنظیمات قالب آوانیک',$html);
    }
    public static function providers(): void {
        self::shell('ارائه‌دهندگان','<p>مدیریت ارائه‌دهندگان خدمات سفر در این صفحه مستقل از «تنظیمات قالب» انجام می‌شود.</p><table class="widefat striped"><thead><tr><th>ارائه‌دهنده</th><th>نوع سرویس</th><th>وضعیت</th></tr></thead><tbody><tr><td>پرواز</td><td>Flight</td><td><span class="avanik-status">آماده اتصال</span></td></tr><tr><td>هتل</td><td>Hotel</td><td><span class="avanik-status">آماده اتصال</span></td></tr><tr><td>تور</td><td>Tour</td><td><span class="avanik-status">آماده اتصال</span></td></tr></tbody></table>');
    }
    public static function notifications(): void {
        self::shell('اعلان‌ها','<p>مدیریت اعلان‌ها، رخدادها و وضعیت سلامت سرویس‌ها در این بخش مستقل انجام می‌شود.</p><div class="notice notice-info inline"><p>فعلاً اعلان فعالی ثبت نشده است.</p></div>');
    }
}
