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
        $keys=['navy','gold','phone','instagram','telegram','whatsapp','linkedin','youtube','logo','hero','hero_title','hero_subtitle','hero_cta','show_destinations','show_why','show_social_header','animation'];
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
        add_submenu_page('avanik-dashboard','تنظیمات قالب آوانیک','تنظیمات قالب','manage_options','avanik-settings',[self::class,'settings']);
        add_menu_page('ارائه‌دهندگان خدمات','ارائه‌دهندگان','manage_options','avanik-providers',[self::class,'providers'],'dashicons-networking',4);
        add_menu_page('اعلان‌ها و رخدادها','اعلان‌ها','manage_options','avanik-notifications',[self::class,'notifications'],'dashicons-bell',5);
    }
    private static function shell($title,$content): void { echo '<div class="wrap avanik-admin-wrap" dir="rtl"><h1>'.esc_html($title).'</h1><div class="avanik-admin-card">'.$content.'</div></div>'; }
    private static function media_field($key,$label,$default='') {
        $v=get_option('avanik_'.$key,$default);
        return '<div class="avanik-field avanik-media-field"><label>'.esc_html($label).'</label><div><input id="avanik_'.esc_attr($key).'" class="avanik-setting-input" name="avanik_'.esc_attr($key).'" value="'.esc_attr($v).'" type="text"><button type="button" class="button avanik-media" data-target="avanik_'.esc_attr($key).'">انتخاب از رسانه</button></div></div>';
    }
    public static function dashboard(): void {
        self::shell('آوانیک پرواز آسیا','<div class="avanik-admin-hero"><span class="dashicons dashicons-airplane"></span><div><h2>پنل اختصاصی قالب آوانیک</h2><p>ظاهر، هدر، اسلایدر، لوگو، شبکه‌های اجتماعی و رفتارهای نمایشی سایت را از همین بخش مدیریت کنید.</p></div></div><div class="avanik-admin-links"><a href="'.esc_url(admin_url('admin.php?page=avanik-settings')).'">تنظیمات قالب</a><a href="'.esc_url(admin_url('admin.php?page=avanik-providers')).'">ارائه‌دهندگان</a><a href="'.esc_url(admin_url('admin.php?page=avanik-notifications')).'">اعلان‌ها</a></div>');
    }
    public static function settings(): void {
        $fields=[
            ['navy','رنگ اصلی سرمه‌ای','#082B52'],['gold','رنگ تأکیدی طلایی','#F2B134'],['phone','شماره تماس','021-12345678'],
            ['hero_title','عنوان اصلی اسلایدر','پرواز به استانبول'],['hero_subtitle','زیرعنوان اسلایدر','با بهترین قیمت و خدمات ویژه'],['hero_cta','متن دکمه اسلایدر','رزرو آنلاین'],
            ['instagram','اینستاگرام','#'],['telegram','تلگرام','#'],['whatsapp','واتساپ','#'],['linkedin','لینکدین','#'],['youtube','یوتیوب','#']
        ];
        $html='<form method="post" action="options.php">'; ob_start(); settings_fields('avanik_theme_options'); $html.=ob_get_clean();
        $html.='<h2>ظاهر و برند</h2>';
        foreach($fields as $f){$v=get_option('avanik_'.$f[0],$f[2]);$type=in_array($f[0],['navy','gold'])?'color':'text';$html.='<div class="avanik-field"><label>'.esc_html($f[1]).'</label><input class="avanik-setting-input" name="avanik_'.esc_attr($f[0]).'" value="'.esc_attr($v).'" type="'.$type.'"></div>';}
        $html.=self::media_field('logo','لوگوی سایت',get_template_directory_uri().'/assets/images/avanik-logo.svg');
        $html.=self::media_field('hero','تصویر اسلایدر / هرو',get_template_directory_uri().'/assets/images/hero-istanbul.svg');
        $html.='<h2>نمایش بخش‌ها</h2><div class="avanik-checks">';
        foreach([['show_social_header','نمایش شبکه‌های اجتماعی در هدر',1],['show_destinations','نمایش مقصدهای محبوب',1],['show_why','نمایش بخش چرا آوانیک',1],['animation','فعال بودن انیمیشن‌های سبک',1]] as $c){$v=(int)get_option('avanik_'.$c[0],$c[2]);$html.='<label><input type="checkbox" name="avanik_'.esc_attr($c[0]).'" value="1" '.checked(1,$v,false).'> '.esc_html($c[1]).'</label>';} $html.='</div>';
        $html.='<p><button class="button button-primary button-hero" type="submit">ذخیره تنظیمات قالب</button></p></form>';
        self::shell('تنظیمات قالب آوانیک',$html);
    }
    public static function providers(): void {
        self::shell('ارائه‌دهندگان خدمات','<p>وضعیت اتصال سرویس‌های پرواز، هتل و تور را از این بخش بررسی و مدیریت کنید.</p><table class="widefat striped"><thead><tr><th>ارائه‌دهنده</th><th>نوع سرویس</th><th>وضعیت</th></tr></thead><tbody><tr><td>ارائه‌دهنده پرواز</td><td>پرواز</td><td><span class="avanik-status">آماده اتصال</span></td></tr><tr><td>ارائه‌دهنده هتل</td><td>هتل</td><td><span class="avanik-status">آماده اتصال</span></td></tr><tr><td>ارائه‌دهنده تور</td><td>تور</td><td><span class="avanik-status">آماده اتصال</span></td></tr></tbody></table>');
    }
    public static function notifications(): void {
        self::shell('اعلان‌ها و رخدادها','<p>در این بخش هشدارها، اعلان‌های سیستمی و رخدادهای سرویس‌های آوانیک نمایش داده می‌شوند.</p><div class="notice notice-success inline"><p><strong>وضعیت سیستم:</strong> در حال حاضر اعلان فعالی ثبت نشده است.</p></div>');
    }
}
