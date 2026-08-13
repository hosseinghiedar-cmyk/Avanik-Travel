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
        $text=['phone','hero_title_1','hero_title_2','hero_title_3','hero_subtitle_1','hero_subtitle_2','hero_subtitle_3','hero_cta'];
        $urls=['instagram','telegram','whatsapp','linkedin','youtube','logo','hero_slide_1','hero_slide_2','hero_slide_3','destination_istanbul','destination_paris','destination_dubai','destination_antalya','destination_london','destination_newyork','destination_kish'];
        foreach($text as $key) register_setting('avanik_theme_options','avanik_'.$key,['sanitize_callback'=>'sanitize_text_field']);
        foreach($urls as $key) register_setting('avanik_theme_options','avanik_'.$key,['sanitize_callback'=>'esc_url_raw']);
        foreach(['navy','gold'] as $key) register_setting('avanik_theme_options','avanik_'.$key,['sanitize_callback'=>'sanitize_hex_color']);
        foreach(['show_social_header','show_destinations','show_why','animation'] as $key) register_setting('avanik_theme_options','avanik_'.$key,['sanitize_callback'=>[self::class,'checkbox']]);
    }
    public static function checkbox($value): int { return empty($value) ? 0 : 1; }
    public static function assets($hook): void {
        if(strpos((string)$hook,'avanik')===false)return;
        wp_enqueue_media();
        wp_enqueue_style('avanik-admin',get_template_directory_uri().'/assets/css/avanik-admin.css',['dashicons'],'0.4.0');
        wp_enqueue_script('avanik-admin',get_template_directory_uri().'/assets/js/avanik-admin.js',['jquery'],'0.4.0',true);
    }
    public static function menus(): void {
        add_menu_page('آوانیک پرواز آسیا','آوانیک','manage_options','avanik-dashboard',[self::class,'dashboard'],'dashicons-airplane',3);
        add_submenu_page('avanik-dashboard','تنظیمات قالب آوانیک','تنظیمات قالب','manage_options','avanik-settings',[self::class,'settings']);
        add_submenu_page('avanik-dashboard','ارائه‌دهندگان خدمات','ارائه‌دهندگان خدمات','manage_options','avanik-providers',[self::class,'providers']);
        add_submenu_page('avanik-dashboard','اعلان‌ها و رخدادها','اعلان‌ها و رخدادها','manage_options','avanik-notifications',[self::class,'notifications']);
    }
    private static function shell(string $title,string $content): void {
        echo '<div class="wrap avanik-admin-wrap" dir="rtl"><h1>'.esc_html($title).'</h1><div class="avanik-admin-card">'.$content.'</div></div>';
    }
    private static function text_field(string $key,string $label,string $default=''): string {
        $v=get_option('avanik_'.$key,$default);
        return '<div class="avanik-field"><label>'.esc_html($label).'</label><input class="avanik-setting-input" name="avanik_'.esc_attr($key).'" value="'.esc_attr($v).'" type="text"></div>';
    }
    private static function url_field(string $key,string $label,string $default='#'): string {
        $v=get_option('avanik_'.$key,$default);
        return '<div class="avanik-field"><label>'.esc_html($label).'</label><input class="avanik-setting-input" name="avanik_'.esc_attr($key).'" value="'.esc_attr($v).'" type="url" dir="ltr" placeholder="https://"></div>';
    }
    private static function media_field(string $key,string $label,string $default=''): string {
        $v=get_option('avanik_'.$key,$default);
        return '<div class="avanik-field avanik-media-field"><label>'.esc_html($label).'</label><div class="avanik-media-row"><input id="avanik_'.esc_attr($key).'" class="avanik-setting-input" name="avanik_'.esc_attr($key).'" value="'.esc_attr($v).'" type="url" dir="ltr"><button type="button" class="button avanik-media" data-target="avanik_'.esc_attr($key).'">انتخاب تصویر</button></div><small>تصویر پیشنهادی: WebP/JPEG فشرده و با عرض حدود ۱۸۰۰ پیکسل برای اسلایدر.</small></div>';
    }
    private static function toggle(string $key,string $label,int $default=1): string {
        $v=(int)get_option('avanik_'.$key,$default);
        return '<label class="avanik-toggle"><input type="checkbox" name="avanik_'.esc_attr($key).'" value="1" '.checked(1,$v,false).'> <span>'.esc_html($label).'</span></label>';
    }
    public static function dashboard(): void {
        self::shell('آوانیک پرواز آسیا','<div class="avanik-admin-hero"><span class="dashicons dashicons-airplane"></span><div><h2>مرکز مدیریت آوانیک</h2><p>تمام تنظیمات ظاهری و محتوای نمایشی قالب از همین بخش مدیریت می‌شود.</p></div></div><div class="avanik-admin-links"><a href="'.esc_url(admin_url('admin.php?page=avanik-settings')).'">تنظیمات قالب</a><a href="'.esc_url(admin_url('admin.php?page=avanik-providers')).'">ارائه‌دهندگان خدمات</a><a href="'.esc_url(admin_url('admin.php?page=avanik-notifications')).'">اعلان‌ها و رخدادها</a></div>');
    }
    public static function settings(): void {
        $html='<form method="post" action="options.php">';
        ob_start();settings_fields('avanik_theme_options');$html.=ob_get_clean();
        $html.='<div class="avanik-settings-section"><h2>برند و رنگ‌بندی</h2><p>هویت بصری سایت را بدون ورود به تنظیمات عمومی وردپرس مدیریت کنید.</p>';
        foreach([['navy','رنگ اصلی سرمه‌ای','#082B52'],['gold','رنگ تأکیدی طلایی','#F2B134']] as $f){$html.='<div class="avanik-field"><label>'.esc_html($f[1]).'</label><input class="avanik-setting-input" name="avanik_'.esc_attr($f[0]).'" value="'.esc_attr(get_option('avanik_'.$f[0],$f[2])).'" type="color"></div>';}
        $html.=self::media_field('logo','لوگوی اصلی سایت',AVANIK_URI.'/assets/images/avanik-logo.svg');
        $html.='</div>';
        $html.='<div class="avanik-settings-section"><h2>اسلایدر صفحه اصلی</h2><p>برای هر سه اسلاید، تصویر، عنوان و متن جداگانه انتخاب کنید.</p>';
        $slides=[1=>'اسلاید اول',2=>'اسلاید دوم',3=>'اسلاید سوم'];
        foreach($slides as $i=>$label){$html.='<div class="avanik-slide-box"><h3>'.esc_html($label).'</h3>'.$this_media('hero_slide_'.$i,'تصویر اسلاید '.$i,'').$this_text('hero_title_'.$i,'عنوان اسلاید','').$this_text('hero_subtitle_'.$i,'زیرعنوان اسلاید','').'</div>';}
        $html.=$this_text('hero_cta','متن دکمه اسلایدر','رزرو آنلاین').'</div>';
        $html.='<div class="avanik-settings-section"><h2>شبکه‌های اجتماعی هدر</h2><p>لینک شبکه‌ها را وارد کنید تا آیکون واقعی و سبک آن‌ها در هدر نمایش داده شود.</p>';
        foreach([['instagram','اینستاگرام'],['telegram','تلگرام'],['whatsapp','واتساپ'],['linkedin','لینکدین'],['youtube','یوتیوب']] as $s)$html.=self::url_field($s[0],$s[1],'#');
        $html.=self::toggle('show_social_header','نمایش شبکه‌های اجتماعی در هدر',1).'</div>';
        $html.='<div class="avanik-settings-section"><h2>تصاویر مقصدها</h2><p>تصاویر کارت‌های مقصد از همین بخش قابل تعویض هستند.</p>';
        foreach([['istanbul','استانبول'],['paris','پاریس'],['dubai','دبی'],['antalya','آنتالیا'],['london','لندن'],['newyork','نیویورک'],['kish','کیش']] as $d)$html.=self::media_field('destination_'.$d[0],'تصویر '.$d[1]);
        $html.='</div>';
        $html.='<div class="avanik-settings-section"><h2>تماس و نمایش</h2>'.$this_text('phone','شماره تماس','021-12345678').'<div class="avanik-toggle-grid">'.self::toggle('show_destinations','نمایش مقصدهای محبوب',1).self::toggle('show_why','نمایش بخش چرا آوانیک',1).self::toggle('animation','فعال بودن انیمیشن‌های سبک و سریع',1).'</div></div>';
        $html.='<p><button class="button button-primary button-hero" type="submit">ذخیره تنظیمات آوانیک</button></p></form>';
        self::shell('تنظیمات قالب آوانیک',$html);
    }
    private static function this_text(string $key,string $label,string $default=''): string { return self::text_field($key,$label,$default); }
    private static function this_media(string $key,string $label,string $default=''): string { return self::media_field($key,$label,$default); }
    public static function providers(): void {
        self::shell('ارائه‌دهندگان خدمات','<div class="avanik-admin-hero avanik-admin-hero--compact"><span class="dashicons dashicons-networking"></span><div><h2>مدیریت ارائه‌دهندگان</h2><p>اتصال‌دهنده‌های پرواز، هتل و تور در این بخش مدیریت خواهند شد.</p></div></div><table class="widefat striped avanik-admin-table"><thead><tr><th>سرویس</th><th>ارائه‌دهنده</th><th>وضعیت</th><th>عملیات</th></tr></thead><tbody><tr><td>پرواز داخلی و خارجی</td><td>سامانه پرواز آوانیک</td><td><span class="avanik-status avanik-status--ready">آماده اتصال</span></td><td><button type="button" class="button" disabled>تنظیم اتصال</button></td></tr><tr><td>هتل</td><td>سامانه هتل آوانیک</td><td><span class="avanik-status avanik-status--ready">آماده اتصال</span></td><td><button type="button" class="button" disabled>تنظیم اتصال</button></td></tr><tr><td>تور و پکیج</td><td>سامانه تور آوانیک</td><td><span class="avanik-status avanik-status--ready">آماده اتصال</span></td><td><button type="button" class="button" disabled>تنظیم اتصال</button></td></tr></tbody></table><p class="description">این پنل برای اتصال APIهای واقعی آماده شده است؛ اطلاعات دسترسی سرویس‌ها در مرحله اتصال عملیاتی وارد می‌شود.</p>');
    }
    public static function notifications(): void {
        self::shell('اعلان‌ها و رخدادها','<div class="avanik-admin-hero avanik-admin-hero--compact"><span class="dashicons dashicons-bell"></span><div><h2>اعلان‌ها و رخدادهای آوانیک</h2><p>هشدارهای اتصال سرویس، وضعیت رزرو و رخدادهای مهم در این بخش جمع می‌شوند.</p></div></div><div class="avanik-notification-empty"><span class="dashicons dashicons-yes-alt"></span><strong>اعلان فعالی وجود ندارد</strong><p>سیستم اعلان آوانیک آماده دریافت رخدادهای واقعی سرویس‌هاست.</p></div>');
    }
}
