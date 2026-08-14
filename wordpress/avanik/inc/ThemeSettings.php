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
        add_submenu_page('avanik-dashboard','تنظیمات قالب آوانیک','تنظیمات قالب','manage_options','avanik-settings',[self::class,'settings'],'manage_options');
        add_menu_page('ارائه‌دهندگان','ارائه‌دهندگان','manage_options','avanik-providers',[self::class,'providers'],'dashicons-networking',4);
        add_menu_page('اعلان‌ها','اعلان‌ها','manage_options','avanik-notifications',[self::class,'notifications'],'dashicons-bell',5);
    }
    private static function shell(string $title,string $content): void {echo '<div class="wrap avanik-admin-wrap" dir="rtl"><h1>'.esc_html($title).'</h1><div class="avanik-admin-card">'.$content.'</div></div>';}
    public static function dashboard(): void {self::shell('آوانیک پرواز آسیا','<p>پنل اختصاصی آوانیک. تنظیمات ظاهری قالب در <b>آوانیک ← تنظیمات قالب</b> قرار دارد و مدیریت ارائه‌دهندگان و اعلان‌ها از منوهای مستقل انجام می‌شود.</p>');}
    public static function settings(): void {
        $fields=[
          ['hero_eyebrow','متن بالای عنوان هرو','سفر بعدی شما از اینجا شروع می‌شود','text'],['hero_title_prefix','بخش اول عنوان هرو','پرواز به','text'],['hero_title_accent','کلمه طلایی عنوان','استانبول','text'],['hero_subtitle','زیرعنوان هرو','با بهترین قیمت و خدمات ویژه','text'],['hero_button','متن دکمه هرو','رزرو آنلاین','text'],
          ['navy','رنگ سرمه‌ای','#082B52','color'],['gold','رنگ طلایی','#F2B134','color'],['phone','شماره تماس','021-12345678','text'],
          ['logo','لوگوی سایت',get_template_directory_uri().'/assets/images/avanik-logo.svg','media'],['hero','تصویر هرو / اسلایدر',get_template_directory_uri().'/assets/images/hero-reference-istanbul.jpg','media'],
          ['instagram','اینستاگرام','#','text'],['telegram','تلگرام','#','text'],['whatsapp','واتساپ','#','text'],['linkedin','لینکدین','#','text']
        ];
        $html='<form method="post" action="options.php">';ob_start();settings_fields('avanik_theme_options');$html.=ob_get_clean();
        $html.='<div class="avanik-settings-section"><h2>محتوای هرو</h2><p>عنوان و تصویر بخش اول سایت را از همین قسمت تغییر دهید.</p></div>';
        foreach($fields as $f){$v=get_option('avanik_'.$f[0],$f[2]);$html.='<div class="avanik-field"><label>'.esc_html($f[1]).'</label><div class="avanik-field-control"><input class="avanik-setting-input" name="avanik_'.esc_attr($f[0]).'" value="'.esc_attr($v).'" type="'.esc_attr($f[3]==='color'?'color':'text').'">'.($f[3]==='media'?'<button type="button" class="button avanik-media" data-target="avanik_'.esc_attr($f[0]).'">انتخاب از رسانه‌ها</button><div class="avanik-media-preview"><img src="'.esc_url($v).'" alt="پیش‌نمایش"></div>':'').'</div></div>';}
        $html.='<p><button class="button button-primary button-hero" type="submit">ذخیره تنظیمات قالب</button></p></form>';self::shell('تنظیمات قالب آوانیک',$html);
    }
    public static function providers(): void {self::shell('ارائه‌دهندگان','<p>مدیریت ارائه‌دهندگان خدمات سفر مستقل از تنظیمات ظاهری قالب است.</p><table class="widefat striped"><thead><tr><th>ارائه‌دهنده</th><th>نوع سرویس</th><th>وضعیت</th></tr></thead><tbody><tr><td>پرواز</td><td>پرواز داخلی و خارجی</td><td><span class="avanik-status">آماده اتصال</span></td></tr><tr><td>هتل</td><td>رزرو هتل</td><td><span class="avanik-status">آماده اتصال</span></td></tr><tr><td>تور</td><td>تورهای مسافرتی</td><td><span class="avanik-status">آماده اتصال</span></td></tr></tbody></table>');}
    public static function notifications(): void {self::shell('اعلان‌ها','<p>اعلان‌ها و وضعیت سرویس‌ها در این صفحه مستقل مدیریت می‌شوند.</p><div class="notice notice-info inline"><p>فعلاً اعلان فعالی ثبت نشده است.</p></div>');}
}
