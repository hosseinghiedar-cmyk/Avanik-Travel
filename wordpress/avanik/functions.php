<?php
/**
 * Avanik Travel — v0.4.0 modern hosting bootstrap.
 */
if (!defined('ABSPATH')) exit;

define('AVANIK_VERSION', '0.4.0');
define('AVANIK_DIR', get_template_directory());
define('AVANIK_URI', get_template_directory_uri());

// Lightweight class autoloader. Existing application modules remain available
// without requiring the entire inc directory in an unsafe order.
spl_autoload_register(function ($class) {
    if (strpos($class, 'Avanik\\') !== 0) return;
    $short = substr($class, strlen('Avanik\\'));
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
    wp_enqueue_style('avanik-modern-v048', AVANIK_URI.'/assets/css/avanik-modern-v048.css', ['avanik-style'], AVANIK_VERSION);
    wp_enqueue_script('avanik-modern-v048', AVANIK_URI.'/assets/js/avanik-modern-v048.js', [], AVANIK_VERSION, true);
    wp_localize_script('avanik-modern-v048','AvanikData',['ajaxUrl'=>admin_url('admin-ajax.php'),'home'=>home_url('/')]);
});

// Persian/Jalali conversion for the public booking UI.
function avanik_jalali_date($gy,$gm,$gd){
    $g_d_m=[0,31,59,90,120,151,181,212,243,273,304,334];
    $gy2=($gm>2)?($gy+1):$gy;
    $days=355666+(365*$gy)+intdiv($gy2+3,4)-intdiv($gy2+99,100)+intdiv($gy2+399,400)+$gd+$g_d_m[$gm-1];
    $jy=-1595+33*intdiv($days,12053); $days%=12053; $jy+=4*intdiv($days,1461); $days%=1461;
    if($days>365){$jy+=intdiv($days-1,365);$days=($days-1)%365;}
    $jm=($days<186)?1+intdiv($days,31):7+intdiv($days-186,30);
    $jd=1+(($days<186)?($days%31):(($days-186)%30));
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

add_action('after_setup_theme', function(){
    if (class_exists('Avanik\\Theme')) Avanik\\Theme::boot();
    if (class_exists('Avanik\\ThemeSetup')) Avanik\\ThemeSetup::register();
}, 20);
