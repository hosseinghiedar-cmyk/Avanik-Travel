<?php
namespace Avanik;
if (!defined('ABSPATH')) exit;

/**
 * Elementor integration for Avanik Travel.
 * Keeps booking logic in the theme while making the homepage layout editable.
 */
final class ElementorIntegration {
    public static function boot(): void {
        add_action('elementor/widgets/register',[self::class,'register_widgets']);
        add_action('elementor/editor/after_enqueue_scripts',[self::class,'editor_assets']);
        add_action('elementor/frontend/after_enqueue_scripts',[self::class,'frontend_assets']);
        add_action('init',[self::class,'register_shortcodes']);
    }

    public static function register_shortcodes(): void {
        add_shortcode('avanik_search',[self::class,'shortcode_search']);
        add_shortcode('avanik_hero',[self::class,'shortcode_hero']);
        add_shortcode('avanik_services',[self::class,'shortcode_services']);
        add_shortcode('avanik_destinations',[self::class,'shortcode_destinations']);
        add_shortcode('avanik_why',[self::class,'shortcode_why']);
        add_shortcode('avanik_login',[self::class,'shortcode_login']);
    }

    public static function register_widgets($widgets_manager): void {
        if (!class_exists('\\Elementor\\Widget_Base')) return;
        $widgets_manager->register(new class extends \Elementor\Widget_Base {
            public function get_name(){return 'avanik_search';}
            public function get_title(){return 'آوانیک — جستجوی پرواز و تور';}
            public function get_icon(){return 'eicon-search';}
            public function get_categories(){return ['basic'];}
            protected function register_controls(){
                $this->start_controls_section('layout',['label'=>'تنظیمات نمایش']);
                $this->add_control('compact',['label'=>'حالت فشرده','type'=>\Elementor\Controls_Manager::SWITCHER,'label_on'=>'بله','label_off'=>'خیر','return_value'=>'yes','default'=>'']);
                $this->end_controls_section();
            }
            protected function render(){echo \Avanik\ElementorIntegration::shortcode_search(['compact'=>$this->get_settings_for_display('compact')]);}
        });
        $defs=[
            ['avanik_hero','آوانیک — Hero / معرفی','eicon-banner'],
            ['avanik_services','آوانیک — خدمات','eicon-apps'],
            ['avanik_destinations','آوانیک — مقصدهای محبوب','eicon-map-pin'],
            ['avanik_why','آوانیک — چرا ما؟','eicon-star'],
            ['avanik_login','آوانیک — ورود / ثبت‌نام','eicon-lock-user']
        ];
        foreach($defs as [$name,$title,$icon]){
            $widgets_manager->register(new class($name,$title,$icon) extends \Elementor\Widget_Base {
                private $widget_name; private $widget_title; private $widget_icon;
                public function __construct($name,$title,$icon,$data=[],$args=null){$this->widget_name=$name;$this->widget_title=$title;$this->widget_icon=$icon;parent::__construct($data,$args);}
                public function get_name(){return $this->widget_name;}
                public function get_title(){return $this->widget_title;}
                public function get_icon(){return $this->widget_icon;}
                public function get_categories(){return ['basic'];}
                protected function render(){echo do_shortcode('['.$this->widget_name.']');}
            });
        }
    }

    public static function editor_assets(): void { self::enqueue_shared(); }
    public static function frontend_assets(): void { self::enqueue_shared(); }
    private static function enqueue_shared(): void {
        wp_enqueue_style('avanik-ui-v042',AVANIK_URI.'/assets/css/avanik-ui-v042.css',['avanik-style'],AVANIK_VERSION);
        wp_enqueue_script('avanik-ui-v042',AVANIK_URI.'/assets/js/avanik-ui-v042.js',[],AVANIK_VERSION,true);
        wp_localize_script('avanik-ui-v042','AvanikData',['ajaxUrl'=>admin_url('admin-ajax.php'),'home'=>home_url('/')]);
    }

    public static function shortcode_hero($atts=[]): string {
        $a=shortcode_atts(['title'=>'','accent'=>'','eyebrow'=>'','subtitle'=>''],$atts);
        $ey=$a['eyebrow']!==''?$a['eyebrow']:avanik_option('hero_eyebrow','سفر بعدی شما از اینجا شروع می‌شود');
        $title=$a['title']!==''?$a['title']:avanik_option('hero_title_prefix','پرواز به');
        $accent=$a['accent']!==''?$a['accent']:avanik_option('hero_title_accent','استانبول');
        $sub=$a['subtitle']!==''?$a['subtitle']:avanik_option('hero_subtitle','با بهترین قیمت و خدمات ویژه');
        ob_start(); ?>
        <section class="avanik-hero" style="--avanik-hero-image:url('<?php echo esc_url(avanik_option('hero',AVANIK_URI.'/assets/images/hero-reference-istanbul.jpg')); ?>')"><div class="avanik-hero-bg" aria-hidden="true"></div><div class="avanik-hero-wash" aria-hidden="true"></div><div class="avanik-shell avanik-hero-content"><div class="avanik-hero-copy"><div class="avanik-eyebrow"><?php echo esc_html($ey); ?></div><h1><?php echo esc_html($title); ?> <strong><?php echo esc_html($accent); ?></strong></h1><p><?php echo esc_html($sub); ?></p></div></div></section>
        <?php return ob_get_clean();
    }

    public static function shortcode_search($atts=[]): string {
        $a=shortcode_atts(['compact'=>''],$atts); ob_start(); ?>
        <section class="avanik-search-card <?php echo $a['compact']==='yes'?'avanik-search-card--compact':''; ?>" aria-label="جستجوی خدمات سفر"><div class="avanik-shell">
        <div class="avanik-search-tabs" role="tablist"><button class="active" type="button" data-service="domestic-flight" aria-selected="true">✈ پرواز داخلی</button><button type="button" data-service="foreign-flight" aria-selected="false">✈ پرواز خارجی</button><button type="button" data-service="domestic-tour" aria-selected="false">▣ تور داخلی</button><button type="button" data-service="foreign-tour" aria-selected="false">▣ تور خارجی</button><button type="button" data-service="hotel" aria-selected="false">▤ هتل</button></div>
        <form class="avanik-search-form" onsubmit="return AvanikSearch.submit(event)">
        <?php echo self::city_field('origin','مبدا','tehran','تهران (همه فرودگاه‌ها)'); echo self::city_field('destination','مقصد','mashhad','مشهد'); echo self::date_field('departure','تاریخ رفت'); echo self::date_field('return','تاریخ برگشت'); ?>
        <button class="avanik-swap-btn" type="button" aria-label="جابجایی مبدا و مقصد"><svg viewBox="0 0 24 24"><path d="M7 7h11l-3-3M17 17H6l3 3"/></svg></button>
        <div class="avanik-passenger-field avanik-field"><span>مسافر</span><button type="button" class="avanik-passenger-trigger" aria-expanded="false"><span>♙ <b class="avanik-passenger-total">۱ مسافر</b></span><b class="avanik-chevron">⌄</b></button><div class="avanik-passenger-popover" aria-hidden="true"><?php echo self::passenger_row('adult','بزرگسال','۱۲ سال به بالا',1); ?><?php echo self::passenger_row('child','کودک','۲ تا ۱۱ سال',0); ?><?php echo self::passenger_row('infant','نوزاد','زیر ۲ سال',0); ?><button type="button" class="avanik-passenger-done">تأیید</button></div></div>
        <button class="avanik-search-btn" type="submit"><span>جستجو</span>⌕</button></form></div></section>
        <div class="avanik-date-popover" data-date-popover aria-hidden="true"><div class="avanik-date-head"><strong>انتخاب تاریخ</strong><button type="button" data-date-close>×</button></div><div class="avanik-date-switch"><button type="button" class="active" data-date-mode="jalali">شمسی</button><button type="button" data-date-mode="gregorian">میلادی</button></div><div class="avanik-calendar-head"><button type="button" data-cal-prev>‹</button><strong data-cal-title></strong><button type="button" data-cal-next>›</button></div><div class="avanik-calendar-week"><span>ش</span><span>ی</span><span>د</span><span>س</span><span>چ</span><span>پ</span><span>ج</span></div><div class="avanik-calendar-grid" data-cal-grid></div></div>
        <?php return ob_get_clean();
    }
    private static function city_field($key,$title,$value,$label): string {ob_start();?><label class="avanik-field avanik-field--location" data-city-field="<?php echo esc_attr($key); ?>"><span><?php echo esc_html($title); ?></span><div class="avanik-input-wrap"><button class="avanik-city-trigger" type="button" data-city-trigger="<?php echo esc_attr($key); ?>"><span data-city-label="<?php echo esc_attr($key); ?>"><?php echo esc_html($label); ?></span><span>⌄</span></button></div><input type="hidden" value="<?php echo esc_attr($value); ?>" data-city-value="<?php echo esc_attr($key); ?>"><div class="avanik-city-menu" data-city-menu="<?php echo esc_attr($key); ?>"></div></label><?php return ob_get_clean();}
    private static function date_field($key,$title): string {ob_start();?><label class="avanik-field avanik-field--date"><span><?php echo esc_html($title); ?></span><div class="avanik-input-wrap"><button class="avanik-date-trigger" type="button" data-date-open="<?php echo esc_attr($key); ?>"><span data-date-label="<?php echo esc_attr($key); ?>">امروز</span><span>⌄</span></button></div><input type="hidden" data-date-value="<?php echo esc_attr($key); ?>"></label><?php return ob_get_clean();}
    private static function passenger_row($key,$title,$sub,$count): string {return '<div class="avanik-passenger-row"><div><strong>'.esc_html($title).'</strong><small>'.esc_html($sub).'</small></div><div class="avanik-stepper"><button type="button" data-pass="'.esc_attr($key).'" data-step="-1">−</button><b id="'.esc_attr($key).'-count">'.esc_html($count).'</b><button type="button" data-pass="'.esc_attr($key).'" data-step="1">+</button></div></div>';}

    public static function shortcode_services(): string {ob_start();?><section class="avanik-section avanik-services"><div class="avanik-shell"><div class="avanik-section-title"><h2>خدمات ما</h2><span></span></div><div class="avanik-service-grid"><?php $items=[['✈','خرید بلیط هواپیما','پروازهای داخلی و خارجی'],['▣','رزرو هتل','هتل‌های ایران و جهان'],['▱','تورهای مسافرتی','تورهای داخلی و خارجی'],['▤','ویزای مسافرتی','اخذ ویزا با بهترین قیمت'],['◈','بیمه مسافرتی','بیمه مسافرتی با پوشش کامل']];foreach($items as $s):?><a class="avanik-service-card" href="#"><i><?php echo $s[0];?></i><h3><?php echo esc_html($s[1]);?></h3><p><?php echo esc_html($s[2]);?></p><span>مشاهده بیشتر ←</span></a><?php endforeach;?></div></div></section><?php return ob_get_clean();}
    public static function shortcode_destinations(): string {ob_start();?><section class="avanik-section avanik-destinations"><div class="avanik-shell"><div class="avanik-section-title"><h2>مقصدهای محبوب</h2><span></span></div><div class="avanik-destination-grid"><?php $cities=[['استانبول','استانبول','assets/images/hero-istanbul.svg'],['پاریس','برج ایفل','assets/images/destination-paris.svg'],['لندن','لندن','assets/images/destination-london.svg'],['نیویورک','نیویورک','assets/images/destination-newyork.svg'],['دبی','دبی','assets/images/destination-dubai.svg'],['آنتالیا','آنتالیا','assets/images/destination-antalya.svg']];foreach($cities as $c):?><a class="avanik-destination-card" href="#"><img src="<?php echo esc_url(AVANIK_URI.'/'.$c[2]);?>" alt="<?php echo esc_attr($c[0]);?>"><div class="avanik-destination-overlay"><strong>تور <?php echo esc_html($c[0]);?></strong><small><?php echo esc_html($c[1]);?></small><span>مشاهده تورها ←</span></div></a><?php endforeach;?></div></div></section><?php return ob_get_clean();}
    public static function shortcode_why(): string {return '<section class="avanik-why"><div class="avanik-shell"><div class="avanik-section-title light"><h2>چرا آوانیک پرواز آسیا؟</h2><span></span></div><div class="avanik-why-grid"><div><b>★</b><h3>تجربه و اعتبار</h3><p>سال‌ها تجربه در خدمات سفر و گردشگری</p></div><div><b>✓</b><h3>پرداخت امن</h3><p>امکان پرداخت آنلاین سریع و مطمئن</p></div><div><b>◇</b><h3>قیمت تضمینی</h3><p>بهترین قیمت با پشتیبانی واقعی</p></div><div><b>♧</b><h3>پشتیبانی سریع</h3><p>همراه شما قبل و بعد از سفر</p></div></div></div></section>';}
    public static function shortcode_login(): string {return '<div class="avanik-login-inline"><div class="avanik-login-icon">♙</div><h2>ورود به آوانیک</h2><p>برای ادامه نام و شماره موبایل خود را وارد کنید.</p><form class="avanik-login-form"><label><span>نام</span><input type="text" name="first_name" autocomplete="given-name"></label><label><span>نام خانوادگی</span><input type="text" name="last_name" autocomplete="family-name"></label><label><span>شماره موبایل</span><input type="tel" name="mobile" inputmode="tel" autocomplete="tel"></label><button type="submit">ادامه</button></form></div>';}
}
