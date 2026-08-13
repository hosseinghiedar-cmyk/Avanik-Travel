from pathlib import Path

ROOT=Path(__file__).resolve().parents[1]/'wordpress'/'avanik'

FUNCTIONS='''<?php
/**
 * Avanik Travel — v0.4.0 modern hosting bootstrap.
 */
if (!defined('ABSPATH')) exit;

define('AVANIK_VERSION', '0.4.0');
define('AVANIK_DIR', get_template_directory());
define('AVANIK_URI', get_template_directory_uri());

// Lightweight class autoloader. This keeps the existing inc/ modules available
// without requiring every PHP file in an unsafe order.
spl_autoload_register(function ($class) {
    if (strpos($class, 'Avanik\\\\') !== 0) return;
    $short = substr($class, strlen('Avanik\\\\'));
    $candidates = [
        AVANIK_DIR . '/inc/' . $short . '.php',
        AVANIK_DIR . '/inc/' . preg_replace('/(?<!^)([A-Z])/', '-$1', $short) . '.php',
    ];
    foreach ($candidates as $file) {
        if (is_file($file)) { require_once $file; return; }
    }
});

require_once AVANIK_DIR . '/inc/ThemeSettings.php';
if (class_exists('Avanik\\ThemeSettings')) Avanik\\ThemeSettings::boot();

add_action('after_setup_theme', function () {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo', ['height'=>70,'width'=>220,'flex-height'=>true,'flex-width'=>true]);
    add_theme_support('html5', ['search-form','comment-form','comment-list','gallery','caption','style','script']);
    register_nav_menus(['primary'=>'منوی اصلی آوانیک']);
});

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('avanik-font', 'https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;500;600;700;800&display=swap', [], null);
    wp_enqueue_style('avanik-style', get_stylesheet_uri(), [], AVANIK_VERSION);
    wp_enqueue_style('avanik-modern', AVANIK_URI.'/assets/css/avanik-modern-v048.css', ['avanik-style'], AVANIK_VERSION);
    wp_enqueue_script('avanik-modern', AVANIK_URI.'/assets/js/avanik-modern-v048.js', [], AVANIK_VERSION, true);
    wp_localize_script('avanik-modern','AvanikData',['ajaxUrl'=>admin_url('admin-ajax.php'),'home'=>home_url('/')]);
});

// Persian/Jalali date conversion for the public booking UI.
function avanik_jalali_date($gy,$gm,$gd){
    $g_d_m=[0,31,59,90,120,151,181,212,243,273,304,334];
    $gy2=($gm>2)?($gy+1):$gy;
    $days=355666+(365*$gy)+intdiv($gy2+3,4)-intdiv($gy2+99,100)+intdiv($gy2+399,400)+$gd+$g_d_m[$gm-1];
    $jy=-1595+33*intdiv($days,12053); $days%=12053; $jy+=4*intdiv($days,1461); $days%=1461;
    if($days>365){$jy+=intdiv($days-1,365);$days=($days-1)%365;}
    $jm=($days<186)?1+intdiv($days,31):7+intdiv($days-186,30); $jd=1+(($days<186)?($days%31):(($days-186)%30));
    return sprintf('%04d/%02d/%02d',$jy,$jm,$jd);
}
function avanik_today_jalali(){ return avanik_jalali_date((int)current_time('Y'),(int)current_time('m'),(int)current_time('j')); }

function avanik_option($key,$default=''){ return get_option('avanik_'.$key,$default); }

add_action('wp_head', function(){
    $navy=avanik_option('navy','#082B52'); $gold=avanik_option('gold','#F2B134');
    echo '<style>:root{--avanik-navy:'.esc_attr($navy).';--avanik-gold:'.esc_attr($gold).';}</style>';
});

add_action('wp_ajax_avanik_demo_search', 'avanik_demo_search');
add_action('wp_ajax_nopriv_avanik_demo_search', 'avanik_demo_search');
function avanik_demo_search(){ wp_send_json_success(['url'=>home_url('/رزرو-پرواز/')]); }

// Keep the existing application modules available when they exist.
add_action('after_setup_theme', function(){
    if (class_exists('Avanik\\Theme')) Avanik\\Theme::boot();
    if (class_exists('Avanik\\ThemeSetup')) Avanik\\ThemeSetup::register();
}, 20);
'''

HEADER='''<?php if (!defined('ABSPATH')) exit; ?><!doctype html>
<html <?php language_attributes(); ?> dir="rtl">
<head><meta charset="<?php bloginfo('charset'); ?>"><meta name="viewport" content="width=device-width, initial-scale=1"></head>
<body <?php body_class('avanik-site'); ?>>
<?php wp_body_open(); ?>
<header class="avanik-header">
  <div class="avanik-topbar"><div class="avanik-shell avanik-topbar-inner">
    <div class="avanik-top-contact"><a href="tel:<?php echo esc_attr(avanik_option('phone','021-12345678')); ?>"><span class="avanik-icon">◔</span><?php echo esc_html(avanik_option('phone','021-12345678')); ?></a></div>
    <a class="avanik-login-link" href="<?php echo esc_url(wp_login_url()); ?>"><span class="avanik-user-icon">♙</span>ورود / ثبت‌نام</a>
  </div></div>
  <div class="avanik-nav-wrap"><div class="avanik-shell avanik-nav">
    <a class="avanik-brand" href="<?php echo esc_url(home_url('/')); ?>" aria-label="آوانیک پرواز آسیا">
      <img src="<?php echo esc_url(AVANIK_URI.'/assets/images/avanik-logo.svg'); ?>" alt="آوانیک پرواز آسیا">
    </a>
    <nav class="avanik-main-menu" aria-label="منوی اصلی">
      <?php if (has_nav_menu('primary')) { wp_nav_menu(['theme_location'=>'primary','container'=>false,'fallback_cb'=>false,'menu_class'=>'avanik-menu-list']); } else { ?>
      <ul class="avanik-menu-list">
        <li class="current-menu-item"><a href="<?php echo esc_url(home_url('/')); ?>">صفحه اصلی</a></li>
        <li><a href="<?php echo esc_url(home_url('/پروازها/')); ?>">پروازها</a></li>
        <li><a href="<?php echo esc_url(home_url('/تورهای-خارجی/')); ?>">تورهای خارجی</a></li>
        <li><a href="<?php echo esc_url(home_url('/هتل/')); ?>">هتل</a></li>
        <li><a href="<?php echo esc_url(home_url('/ویزای-مسافرتی/')); ?>">ویزای مسافرتی</a></li>
        <li><a href="<?php echo esc_url(home_url('/بلاگ/')); ?>">بلاگ</a></li>
        <li><a href="<?php echo esc_url(home_url('/درباره-ما/')); ?>">درباره ما</a></li>
        <li><a href="<?php echo esc_url(home_url('/تماس-با-ما/')); ?>">تماس با ما</a></li>
      </ul><?php } ?>
    </nav>
    <button class="avanik-mobile-toggle" type="button" aria-label="باز کردن منو">☰</button>
  </div></div>
</header>
'''

FOOTER='''<?php if (!defined('ABSPATH')) exit; ?>
<footer class="avanik-footer">
  <div class="avanik-footer-main"><div class="avanik-shell avanik-footer-grid">
    <div class="avanik-footer-brand"><img src="<?php echo esc_url(AVANIK_URI.'/assets/images/avanik-logo-white.svg'); ?>" alt="آوانیک پرواز آسیا"><p>آوانیک پرواز آسیا، ارائه‌دهنده خدمات مسافرتی و گردشگری با تجربه‌ای متفاوت، سریع و باکیفیت.</p><div class="avanik-socials">
      <a href="<?php echo esc_url(avanik_option('instagram','#')); ?>" aria-label="Instagram">◎</a><a href="<?php echo esc_url(avanik_option('telegram','#')); ?>" aria-label="Telegram">➤</a><a href="<?php echo esc_url(avanik_option('whatsapp','#')); ?>" aria-label="WhatsApp">◉</a><a href="<?php echo esc_url(avanik_option('linkedin','#')); ?>" aria-label="LinkedIn">in</a>
    </div></div>
    <div><h3>خدمات</h3><a href="<?php echo esc_url(home_url('/پروازها/')); ?>">پروازهای داخلی</a><a href="<?php echo esc_url(home_url('/پروازها/')); ?>">پروازهای خارجی</a><a href="<?php echo esc_url(home_url('/تورهای-داخلی/')); ?>">تورهای داخلی</a><a href="<?php echo esc_url(home_url('/تورهای-خارجی/')); ?>">تورهای خارجی</a><a href="<?php echo esc_url(home_url('/هتل/')); ?>">هتل</a></div>
    <div><h3>لینک‌های سریع</h3><a href="<?php echo esc_url(home_url('/')); ?>">صفحه اصلی</a><a href="<?php echo esc_url(home_url('/درباره-ما/')); ?>">درباره ما</a><a href="<?php echo esc_url(home_url('/تماس-با-ما/')); ?>">تماس با ما</a><a href="<?php echo esc_url(home_url('/سوالات-متداول/')); ?>">سوالات متداول</a><a href="<?php echo esc_url(home_url('/قوانین/')); ?>">شرایط و قوانین</a></div>
    <div class="avanik-newsletter"><h3>خبرنامه</h3><p>با عضویت در خبرنامه، از آخرین اخبار و تخفیف‌ها مطلع شوید.</p><form><input type="email" placeholder="ایمیل خود را وارد کنید"><button type="submit">عضویت</button></form></div>
  </div></div>
  <div class="avanik-footer-bottom"><div class="avanik-shell"><span>طراحی و توسعه: تیم آوانیک</span><span>کلیه حقوق این سایت محفوظ می‌باشد.</span></div></div>
</footer>
<?php wp_footer(); ?></body></html>
'''

FRONT='''<?php get_header(); ?>
<main class="avanik-home">
<section class="avanik-hero"><div class="avanik-hero-bg"></div><div class="avanik-shell avanik-hero-content">
  <div class="avanik-hero-copy"><div class="avanik-eyebrow">سفر بعدی شما از اینجا شروع می‌شود</div><h1>پرواز به <strong>استانبول</strong></h1><p>با بهترین قیمت و خدمات ویژه</p><a class="avanik-primary-btn" href="<?php echo esc_url(home_url('/رزرو-پرواز/')); ?>">رزرو آنلاین <span>←</span></a></div>
</div></section>
<section class="avanik-search-card"><div class="avanik-shell">
  <div class="avanik-search-tabs"><button class="active">✈ پرواز داخلی</button><button>✈ پرواز خارجی</button><button>▣ تور داخلی</button><button>▣ تور خارجی</button><button>▤ هتل</button></div>
  <form class="avanik-search-form" onsubmit="return AvanikSearch.submit(event)">
    <label>مبدا<select><option>تهران (همه فرودگاه‌ها)</option><option>مشهد</option><option>شیراز</option></select></label>
    <label>مقصد<select><option>استانبول</option><option>پاریس</option><option>لندن</option><option>نیویورک</option><option>دبی</option></select></label>
    <label>تاریخ رفت<input value="<?php echo esc_attr(avanik_today_jalali()); ?>" class="jalali-date"></label>
    <label>تاریخ برگشت<input value="<?php echo esc_attr(avanik_today_jalali()); ?>" class="jalali-date"></label>
    <div class="avanik-passenger-field"><span>مسافر</span><button type="button" class="avanik-passenger-trigger">۱ مسافر <b>⌄</b></button><div class="avanik-passenger-popover">
      <div><span>بزرگسال</span><div><button type="button" data-pass="adult" data-step="-1">−</button><b id="adult-count">۱</b><button type="button" data-pass="adult" data-step="1">+</button></div></div>
      <div><span>کودک</span><div><button type="button" data-pass="child" data-step="-1">−</button><b id="child-count">۰</b><button type="button" data-pass="child" data-step="1">+</button></div></div>
    </div></div>
    <button class="avanik-search-btn" type="submit">جستجو <span>⌕</span></button>
  </form>
  <div class="avanik-search-hint">مدت‌ها انتخاب شده‌اند؛ مقصد را انتخاب کنید و بهترین گزینه‌ها را ببینید.</div>
</div></section>

<section class="avanik-section avanik-services"><div class="avanik-shell"><div class="avanik-section-title"><h2>خدمات ما</h2><span></span></div><div class="avanik-service-grid">
<?php $services=[['✈','خرید بلیط هواپیما','پروازهای داخلی و خارجی'],['▣','رزرو هتل','هتل‌های ایران و جهان'],['▱','تورهای مسافرتی','تورهای داخلی و خارجی'],['▤','ویزای مسافرتی','اخذ ویزا با بهترین قیمت'],['◈','بیمه مسافرتی','بیمه مسافرتی با پوشش کامل']]; foreach($services as $s): ?><a class="avanik-service-card" href="#"><i><?php echo $s[0]; ?></i><h3><?php echo esc_html($s[1]); ?></h3><p><?php echo esc_html($s[2]); ?></p><span>مشاهده بیشتر ←</span></a><?php endforeach; ?></div></div></section>

<section class="avanik-section avanik-destinations"><div class="avanik-shell"><div class="avanik-section-title"><h2>مقصدهای محبوب</h2><span></span></div><div class="avanik-destination-grid">
<?php $cities=[['istanbul','استانبول','استانبول','assets/images/hero-istanbul.svg'],['paris','پاریس','برج ایفل','assets/images/destination-paris.svg'],['london','لندن','لندن','assets/images/destination-london.svg'],['newyork','نیویورک','نیویورک','assets/images/destination-newyork.svg'],['dubai','دبی','دبی','assets/images/destination-dubai.svg'],['antalya','آنتالیا','آنتالیا','assets/images/destination-antalya.svg']]; foreach($cities as $c): ?><a class="avanik-destination-card" href="#"><img src="<?php echo esc_url(AVANIK_URI.'/'.$c[3]); ?>" alt="<?php echo esc_attr($c[1]); ?>"><div class="avanik-destination-overlay"><strong>تور <?php echo esc_html($c[1]); ?></strong><small><?php echo esc_html($c[2]); ?></small><span>مشاهده تورها ←</span></div></a><?php endforeach; ?></div><a class="avanik-outline-btn" href="#">مشاهده همه مقصدها</a></div></section>

<section class="avanik-why"><div class="avanik-shell"><div class="avanik-section-title light"><h2>چرا آوانیک پرواز آسیا؟</h2><span></span></div><div class="avanik-why-grid"><div><b>★</b><h3>تجربه و اعتبار</h3><p>سال‌ها تجربه در خدمات سفر و گردشگری</p></div><div><b>✓</b><h3>پرداخت امن</h3><p>امکان پرداخت آنلاین سریع و مطمئن</p></div><div><b>◇</b><h3>قیمت تضمینی</h3><p>بهترین قیمت با پشتیبانی واقعی</p></div><div><b>♧</b><h3>پشتیبانی سریع</h3><p>همراه شما قبل و بعد از سفر</p></div></div></div></section>
</main>
<?php get_footer(); ?>
'''

THEME_SETTINGS='''<?php
namespace Avanik;
if (!defined('ABSPATH')) exit;
final class ThemeSettings {
    public static function boot(): void { add_action('admin_menu',[self::class,'menus'],9); add_action('admin_init',[self::class,'register']); add_action('admin_enqueue_scripts',[self::class,'assets']); }
    public static function register(): void {
        $keys=['navy','gold','phone','instagram','telegram','whatsapp','linkedin','logo','hero'];
        foreach($keys as $key) register_setting('avanik_theme_options','avanik_'.$key,['sanitize_callback'=>'sanitize_text_field']);
    }
    public static function assets($hook): void { if(strpos($hook,'avanik')===false) return; wp_enqueue_media(); wp_enqueue_style('avanik-admin',''.get_template_directory_uri().'/assets/css/avanik-admin.css',['dashicons'], '0.4.0'); wp_enqueue_script('avanik-admin',''.get_template_directory_uri().'/assets/js/avanik-admin.js', ['jquery'], '0.4.0', true); }
    public static function menus(): void {
        add_menu_page('آوانیک پرواز آسیا','آوانیک','manage_options','avanik-dashboard',[self::class,'dashboard'],'dashicons-airplane',3);
        add_submenu_page('avanik-dashboard','تنظیمات قالب','تنظیمات قالب','manage_options','avanik-settings',[self::class,'settings']);
        add_menu_page('ارائه‌دهندگان','ارائه‌دهندگان','manage_options','avanik-providers',[self::class,'providers'],'dashicons-networking',4);
        add_menu_page('اعلان‌ها','اعلان‌ها','manage_options','avanik-notifications',[self::class,'notifications'],'dashicons-bell',5);
    }
    private static function shell($title,$content): void { echo '<div class="wrap avanik-admin-wrap" dir="rtl"><h1>'.esc_html($title).'</h1><div class="avanik-admin-card">'.$content.'</div></div>'; }
    public static function dashboard(): void { self::shell('آوانیک پرواز آسیا','<p>پنل اختصاصی مدیریت قالب آوانیک. از منوی <b>تنظیمات قالب</b> ظاهر سایت را مدیریت کنید.</p>'); }
    public static function settings(): void {
        $fields=[['navy','رنگ سرمه‌ای','#082B52'],['gold','رنگ طلایی','#F2B134'],['phone','شماره تماس','021-12345678'],['instagram','لینک اینستاگرام','#'],['telegram','لینک تلگرام','#'],['whatsapp','لینک واتساپ','#'],['linkedin','لینک لینکدین','#'],['logo','آدرس لوگو',get_template_directory_uri().'/assets/images/avanik-logo.svg'],['hero','آدرس تصویر Hero',get_template_directory_uri().'/assets/images/hero-istanbul.svg']];
        $html='<form method="post" action="options.php">'; ob_start(); settings_fields('avanik_theme_options'); $html.=ob_get_clean();
        foreach($fields as $f){$v=get_option('avanik_'.$f[0],$f[2]); $type=in_array($f[0],['navy','gold'])?'color':'text'; $html.='<div class="avanik-field"><label>'.esc_html($f[1]).'</label><input class="avanik-setting-input" name="avanik_'.esc_attr($f[0]).'" value="'.esc_attr($v).'" type="'.$type.'">'.(in_array($f[0],['logo','hero'])?'<button type="button" class="button avanik-media" data-target="avanik_'.esc_attr($f[0]).'">انتخاب تصویر</button>':'').'</div>';}
        $html.='<p><button class="button button-primary button-hero" type="submit">ذخیره تنظیمات قالب</button></p></form>'; self::shell('تنظیمات قالب آوانیک',$html);
    }
    public static function providers(): void { self::shell('ارائه‌دهندگان','<p>مدیریت اتصال‌دهندگان خدمات سفر در این بخش قرار می‌گیرد. این صفحه مستقل از تنظیمات قالب است.</p><table class="widefat striped"><thead><tr><th>ارائه‌دهنده</th><th>وضعیت</th></tr></thead><tbody><tr><td>پرواز</td><td><span class="avanik-status">آماده اتصال</span></td></tr><tr><td>هتل</td><td><span class="avanik-status">آماده اتصال</span></td></tr><tr><td>تور</td><td><span class="avanik-status">آماده اتصال</span></td></tr></tbody></table>'); }
    public static function notifications(): void { self::shell('اعلان‌ها','<p>مدیریت اعلان‌ها و وضعیت سلامت سرویس‌ها در این بخش مستقل انجام می‌شود.</p><div class="notice notice-info inline"><p>فعلاً اعلان فعالی ثبت نشده است.</p></div>'); }
}
'''

CSS='''/* Avanik Travel v0.4.8 — modern lightweight RTL layer */
:root{--avanik-navy:#082B52;--avanik-gold:#F2B134;--avanik-ink:#10233d;--avanik-muted:#6d7b8f;--avanik-bg:#f7f9fc;--avanik-radius:22px;--avanik-shadow:0 18px 45px rgba(8,43,82,.10)}
*{box-sizing:border-box}html{scroll-behavior:smooth}body.avanik-site{margin:0;background:#fff;color:var(--avanik-ink);font-family:Vazirmatn,Arial,sans-serif;line-height:1.9}a{text-decoration:none;color:inherit}.avanik-shell{width:min(1180px,calc(100% - 40px));margin:auto}
.avanik-topbar{height:40px;background:var(--avanik-navy);color:#fff;font-size:13px}.avanik-topbar-inner{height:40px;display:flex;align-items:center;gap:30px;justify-content:flex-start}.avanik-top-contact a,.avanik-login-link{display:flex;align-items:center;gap:8px;opacity:.96}.avanik-icon,.avanik-user-icon{font-size:18px;line-height:1}.avanik-nav-wrap{background:rgba(255,255,255,.96);backdrop-filter:blur(16px);position:relative;z-index:50;box-shadow:0 5px 25px rgba(8,43,82,.05)}.avanik-nav{min-height:92px;display:flex;align-items:center;gap:42px}.avanik-brand{display:flex;align-items:center;margin-right:auto;order:1}.avanik-brand img{width:190px;height:auto;display:block}.avanik-main-menu{order:2;flex:1}.avanik-menu-list{list-style:none;margin:0;padding:0;display:flex;align-items:center;justify-content:flex-start;gap:28px}.avanik-menu-list li{position:relative}.avanik-menu-list a{font-size:14px;font-weight:600;padding:30px 0;display:block;white-space:nowrap}.avanik-menu-list .current-menu-item a:after{content:"";position:absolute;bottom:17px;right:0;left:0;height:3px;background:var(--avanik-gold);border-radius:9px}.avanik-mobile-toggle{display:none;border:0;background:none;font-size:28px}
.avanik-hero{min-height:430px;position:relative;overflow:hidden;background:linear-gradient(110deg,#f5f9ff,#d9ecff)}.avanik-hero-bg{position:absolute;inset:0;background:url('../images/hero-istanbul.svg') center/cover no-repeat}.avanik-hero:after{content:"";position:absolute;inset:0;background:linear-gradient(90deg,rgba(255,255,255,.96) 0%,rgba(255,255,255,.72) 38%,rgba(255,255,255,.05) 70%)}.avanik-hero-content{position:relative;z-index:2;min-height:430px;display:flex;align-items:center}.avanik-hero-copy{width:52%;padding:35px 0}.avanik-eyebrow{font-size:15px;font-weight:600;color:var(--avanik-navy);margin-bottom:5px}.avanik-hero h1{font-size:54px;line-height:1.2;margin:0 0 10px;font-weight:800;color:var(--avanik-navy)}.avanik-hero h1 strong{color:var(--avanik-gold)}.avanik-hero p{font-size:21px;margin:0 0 28px;font-weight:600}.avanik-primary-btn{display:inline-flex;align-items:center;gap:18px;background:var(--avanik-gold);color:#16263b;padding:13px 30px;border-radius:9px;font-weight:800;box-shadow:0 10px 25px rgba(242,177,52,.25);transition:.25s}.avanik-primary-btn:hover{transform:translateY(-3px);box-shadow:0 16px 35px rgba(242,177,52,.32)}
.avanik-search-card{margin-top:-58px;position:relative;z-index:10}.avanik-search-card>.avanik-shell{background:#fff;border-radius:22px;box-shadow:var(--avanik-shadow);padding:22px 28px}.avanik-search-tabs{display:flex;justify-content:center;gap:16px;margin-bottom:20px}.avanik-search-tabs button{border:0;background:transparent;padding:10px 24px;border-radius:9px;font-family:inherit;font-weight:700;color:#26374b;cursor:pointer}.avanik-search-tabs button.active{background:var(--avanik-navy);color:#fff}.avanik-search-form{display:grid;grid-template-columns:1.25fr 1.15fr 1fr 1fr 1.05fr auto;gap:12px;align-items:end}.avanik-search-form label,.avanik-passenger-field{font-size:12px;font-weight:600;color:#68768a;display:flex;flex-direction:column;gap:5px}.avanik-search-form select,.avanik-search-form input,.avanik-passenger-trigger{height:49px;border:1px solid #dfe5ec;background:#fff;border-radius:10px;padding:0 13px;font:inherit;color:#182a40;outline:none}.avanik-search-form select:focus,.avanik-search-form input:focus,.avanik-passenger-trigger:focus{border-color:var(--avanik-gold);box-shadow:0 0 0 3px rgba(242,177,52,.12)}.avanik-passenger-field{position:relative}.avanik-passenger-trigger{cursor:pointer;text-align:right}.avanik-passenger-popover{display:none;position:absolute;top:76px;right:0;width:260px;background:#fff;border:1px solid #e5e9ef;border-radius:16px;box-shadow:0 20px 50px rgba(8,43,82,.18);padding:14px;z-index:30}.avanik-passenger-field.open .avanik-passenger-popover{display:block}.avanik-passenger-popover>div{display:flex;justify-content:space-between;align-items:center;padding:10px 2px;border-bottom:1px solid #edf0f4}.avanik-passenger-popover>div:last-child{border-bottom:0}.avanik-passenger-popover button{width:31px;height:31px;border:1px solid #dce3eb;border-radius:8px;background:#fff;cursor:pointer}.avanik-passenger-popover div div{display:flex;gap:9px;align-items:center}.avanik-search-btn{height:49px;border:0;border-radius:10px;background:var(--avanik-navy);color:#fff;padding:0 27px;font:700 14px Vazirmatn;cursor:pointer;white-space:nowrap}.avanik-search-hint{font-size:12px;color:#7a8796;margin-top:12px}
.avanik-section{padding:72px 0}.avanik-section-title{text-align:center;margin-bottom:32px}.avanik-section-title h2{margin:0;font-size:27px;font-weight:800}.avanik-section-title span{display:block;width:52px;height:3px;background:var(--avanik-gold);margin:9px auto 0;border-radius:9px}.avanik-service-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:18px}.avanik-service-card{background:#fff;border:1px solid #edf0f4;border-radius:20px;padding:25px 18px;text-align:center;box-shadow:0 12px 28px rgba(13,38,66,.06);transition:.28s}.avanik-service-card:hover{transform:translateY(-8px);box-shadow:0 20px 45px rgba(13,38,66,.12);border-color:rgba(242,177,52,.35)}.avanik-service-card i{font-style:normal;font-size:36px;color:var(--avanik-navy)}.avanik-service-card h3{font-size:17px;margin:8px 0 4px}.avanik-service-card p{font-size:12px;color:var(--avanik-muted);margin:0 0 10px}.avanik-service-card span{font-size:12px;color:#1f78d1;font-weight:700}
.avanik-destinations{background:var(--avanik-bg)}.avanik-destination-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:22px}.avanik-destination-card{height:255px;position:relative;border-radius:20px;overflow:hidden;background:#d9e6f3;box-shadow:0 12px 32px rgba(8,43,82,.10);transition:.3s}.avanik-destination-card:hover{transform:translateY(-7px);box-shadow:0 22px 48px rgba(8,43,82,.16)}.avanik-destination-card img{width:100%;height:100%;object-fit:cover;display:block;transition:.5s}.avanik-destination-card:hover img{transform:scale(1.04)}.avanik-destination-overlay{position:absolute;inset:auto 0 0;padding:25px 20px 18px;color:#fff;background:linear-gradient(transparent,rgba(3,24,48,.90));display:flex;flex-direction:column;align-items:flex-start}.avanik-destination-overlay strong{font-size:21px}.avanik-destination-overlay small{font-size:12px;opacity:.9}.avanik-destination-overlay span{margin-top:8px;font-size:12px;color:#ffd77a}.avanik-outline-btn{display:block;width:max-content;margin:28px auto 0;border:1px solid var(--avanik-navy);border-radius:9px;padding:9px 28px;color:var(--avanik-navy);font-weight:700}.avanik-why{background:linear-gradient(120deg,#06294f,#0a365f);color:#fff;padding:60px 0}.avanik-section-title.light h2{color:#fff}.avanik-why-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:30px;text-align:center}.avanik-why-grid b{display:grid;place-items:center;width:58px;height:58px;border:2px solid #fff;border-radius:50%;margin:auto;color:var(--avanik-gold);font-size:25px}.avanik-why-grid h3{margin:10px 0 2px;color:var(--avanik-gold);font-size:17px}.avanik-why-grid p{margin:0;font-size:12px;color:#dbe8f5}
.avanik-footer{background:#0b1e31;color:#fff}.avanik-footer-main{padding:60px 0 35px}.avanik-footer-grid{display:grid;grid-template-columns:1.6fr 1fr 1fr 1.25fr;gap:45px}.avanik-footer-brand img{width:205px;margin-bottom:12px}.avanik-footer h3{font-size:16px;margin:0 0 13px}.avanik-footer p,.avanik-footer a{font-size:12px;color:#c8d2dd}.avanik-footer-grid>div:not(.avanik-footer-brand) a{display:block;margin:5px 0}.avanik-socials{display:flex;gap:10px;margin-top:15px}.avanik-socials a{width:34px;height:34px;border:1px solid rgba(255,255,255,.35);border-radius:50%;display:grid!important;place-items:center;color:#fff!important;font-size:13px!important}.avanik-newsletter form{display:flex;flex-direction:column;gap:8px}.avanik-newsletter input{height:43px;border:0;border-radius:7px;padding:0 12px;font-family:inherit}.avanik-newsletter button{height:43px;background:var(--avanik-gold);border:0;border-radius:7px;font-family:inherit;font-weight:800;cursor:pointer}.avanik-footer-bottom{border-top:1px solid rgba(255,255,255,.1);padding:13px 0}.avanik-footer-bottom .avanik-shell{display:flex;justify-content:space-between;font-size:11px;color:#91a2b5}
.avanik-admin-wrap{max-width:1100px}.avanik-admin-card{background:#fff;border:1px solid #e5eaf0;border-radius:18px;padding:25px;box-shadow:0 12px 30px rgba(0,0,0,.05)}.avanik-field{display:grid;grid-template-columns:220px 1fr auto;gap:12px;align-items:center;border-bottom:1px solid #edf0f3;padding:15px 0}.avanik-field label{font-weight:700}.avanik-setting-input{width:100%;max-width:620px}.avanik-status{display:inline-block;background:#e8f7ef;color:#157347;padding:3px 10px;border-radius:20px;font-size:12px}
@media(max-width:980px){.avanik-menu-list{gap:14px}.avanik-menu-list a{font-size:12px}.avanik-hero h1{font-size:43px}.avanik-search-form{grid-template-columns:repeat(2,1fr)}.avanik-service-grid{grid-template-columns:repeat(3,1fr)}.avanik-footer-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:700px){.avanik-shell{width:min(100% - 24px,1180px)}.avanik-nav{min-height:72px}.avanik-brand img{width:150px}.avanik-main-menu{display:none}.avanik-mobile-toggle{display:block}.avanik-hero{min-height:400px}.avanik-hero-content{min-height:400px}.avanik-hero-copy{width:100%;text-align:center;padding-top:110px}.avanik-hero:after{background:rgba(255,255,255,.72)}.avanik-hero h1{font-size:38px}.avanik-search-card{margin-top:-30px}.avanik-search-card>.avanik-shell{padding:18px}.avanik-search-tabs{overflow:auto;justify-content:flex-start}.avanik-search-form{grid-template-columns:1fr}.avanik-destination-grid{grid-template-columns:1fr}.avanik-service-grid{grid-template-columns:repeat(2,1fr)}.avanik-why-grid{grid-template-columns:repeat(2,1fr)}.avanik-footer-grid{grid-template-columns:1fr}.avanik-footer-bottom .avanik-shell{flex-direction:column;gap:5px}.avanik-topbar-inner{justify-content:space-between}}
'''

JS='''(function(){
  const q=s=>document.querySelector(s);
  const $$=s=>document.querySelectorAll(s);
  const passenger=q('.avanik-passenger-field');
  let adult=1, child=0;
  function refresh(){const a=q('#adult-count'),c=q('#child-count'),t=q('.avanik-passenger-trigger'); if(a)a.textContent=adult; if(c)c.textContent=child; if(t)t.innerHTML=(adult+child)+' مسافر <b>⌄</b>';}
  if(passenger){q('.avanik-passenger-trigger').addEventListener('click',()=>passenger.classList.toggle('open')); $$('.avanik-passenger-popover button').forEach(b=>b.addEventListener('click',()=>{const type=b.dataset.pass,step=Number(b.dataset.step); if(type==='adult')adult=Math.max(1,adult+step);else child=Math.max(0,child+step);refresh();})); document.addEventListener('click',e=>{if(!passenger.contains(e.target))passenger.classList.remove('open')}); refresh();}
  $$('.avanik-search-tabs button').forEach(b=>b.addEventListener('click',()=>{$$('.avanik-search-tabs button').forEach(x=>x.classList.remove('active'));b.classList.add('active')}));
  window.AvanikSearch={submit:function(e){e.preventDefault(); const url=(window.AvanikData&&AvanikData.home)||'/'; window.location.href=url+'?avanik_search=1'; return false;}};
  const obs=new IntersectionObserver(es=>es.forEach(e=>{if(e.isIntersecting)e.target.classList.add('is-visible')}),{threshold:.12}); $$('.avanik-service-card,.avanik-destination-card,.avanik-why-grid>div').forEach(el=>{el.classList.add('avanik-reveal');obs.observe(el)});
})();
'''

ADMINJS='''jQuery(function($){$(document).on('click','.avanik-media',function(){const target=$(this).data('target'),frame=wp.media({title:'انتخاب تصویر',button:{text:'استفاده از تصویر'},multiple:false});frame.on('select',function(){const u=frame.state().get('selection').first().toJSON().url;$('input[name="'+target+'"]').val(u);});frame.open();});});'''
ADMINCSS='''.avanik-admin-wrap{font-family:Vazirmatn,Arial,sans-serif}.avanik-admin-wrap h1{color:#082B52}.avanik-admin-card{max-width:1000px}.avanik-admin-card .button-primary{background:#082B52;border-color:#082B52}.avanik-admin-card .button-primary:hover{background:#0d3d70;border-color:#0d3d70}.avanik-field input[type=color]{width:70px;height:40px;padding:2px}.avanik-field .button{margin-right:5px}'''

# Lightweight landmark SVGs: bundled, fast and independent of third-party image CDNs.
SVGs={
'destination-paris.svg':'''<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 900 500"><defs><linearGradient id="g" x1="0" y1="0" x2="0" y2="1"><stop stop-color="#8ec9f5"/><stop offset="1" stop-color="#f8d6b2"/></linearGradient></defs><rect width="900" height="500" fill="url(#g)"/><circle cx="730" cy="105" r="60" fill="#fff4d8" opacity=".65"/><path fill="#5d6b7d" d="M0 390h900v110H0z"/><path fill="#26394e" d="M420 380l25-170 10-110 10 110 25 170h-70zm25-180h20l-5 180h-10z"/><path fill="#394b60" d="M350 380h200l-70-30h-60z"/></svg>''',
'destination-london.svg':'''<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 900 500"><defs><linearGradient id="g" x1="0" y1="0" x2="0" y2="1"><stop stop-color="#9ed4ef"/><stop offset="1" stop-color="#e7edf1"/></linearGradient></defs><rect width="900" height="500" fill="url(#g)"/><path fill="#aab8c5" d="M0 395h900v105H0z"/><path fill="#b84d42" d="M130 365h180v30H130z"/><path fill="#9f3e35" d="M145 300h35v65h-35zm55 45h28v20h-28zm42-45h40v65h-40z"/><path fill="#263f58" d="M490 385v-170h18v-70h12v70h18v170z"/><circle cx="515" cy="185" r="31" fill="#f5e9d0" stroke="#263f58" stroke-width="8"/><path stroke="#263f58" stroke-width="8" d="M515 154v62M484 185h62"/><path fill="#54758e" d="M0 405h900v95H0z"/></svg>''',
'destination-newyork.svg':'''<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 900 500"><defs><linearGradient id="g" x1="0" y1="0" x2="0" y2="1"><stop stop-color="#79bde8"/><stop offset="1" stop-color="#d9effa"/></linearGradient></defs><rect width="900" height="500" fill="url(#g)"/><path fill="#2d4660" d="M0 370h70v-100h50v100h35V235h55v135h50V180h65v190h45V220h55v150h45V120h70v250h45V250h60v120h60V195h70v175h70v130H0z"/><path fill="#5b748b" d="M0 400h900v100H0z"/><path fill="#f3c85f" d="M110 120l35 110h-22l-13-45-13 45h-22z"/><rect x="126" y="120" width="8" height="100" fill="#f3c85f"/></svg>''',
'destination-dubai.svg':'''<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 900 500"><defs><linearGradient id="g" x1="0" y1="0" x2="0" y2="1"><stop stop-color="#54b7e8"/><stop offset="1" stop-color="#f2d08c"/></linearGradient></defs><rect width="900" height="500" fill="url(#g)"/><path fill="#dfbf77" d="M0 410q220-70 450 0t450 0v90H0z"/><path fill="#6f8493" d="M470 405l20-260 12-90 13 90 22 260h-67zm-45 0l15-190 10 190h-25zm95 0l10-210 12 210h-22z"/><path fill="#40576c" d="M0 405h900v95H0z" opacity=".7"/></svg>''',
'destination-antalya.svg':'''<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 900 500"><defs><linearGradient id="g" x1="0" y1="0" x2="0" y2="1"><stop stop-color="#5fc2ef"/><stop offset="1" stop-color="#f4e2a6"/></linearGradient></defs><rect width="900" height="500" fill="url(#g)"/><path fill="#1f8fb8" d="M0 300q130-60 260 0t260 0 260 0 120 0v200H0z"/><path fill="#e9d3a1" d="M0 350q180-50 330 0t330 0 240 0v150H0z"/><path fill="#d0b47c" d="M0 330h900v20H0z"/></svg>'''
}

for rel,data in [('functions.php',FUNCTIONS),('header.php',HEADER),('footer.php',FOOTER),('front-page.php',FRONT),('inc/ThemeSettings.php',THEME_SETTINGS),('assets/css/avanik-modern-v048.css',CSS),('assets/js/avanik-modern-v048.js',JS),('assets/js/avanik-admin.js',ADMINJS),('assets/css/avanik-admin.css',ADMINCSS)]:
    p=ROOT/rel; p.parent.mkdir(parents=True,exist_ok=True); p.write_text(data,encoding='utf-8')
for name,data in SVGs.items():
    p=ROOT/'assets/images'/name; p.parent.mkdir(parents=True,exist_ok=True); p.write_text(data,encoding='utf-8')
print('Applied Avanik modern v0.4.8 design files.')
