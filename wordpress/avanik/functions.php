<?php
/** Avanik Travel v0.4.1 design refinement bootstrap. */
if (!defined('ABSPATH')) exit;
define('AVANIK_VERSION','0.4.1');
define('AVANIK_DIR',get_template_directory());
define('AVANIK_URI',get_template_directory_uri());
$avanik_prefix='Avanik'.chr(92);
spl_autoload_register(function($class){$prefix='Avanik'.chr(92);if(strpos($class,$prefix)!==0)return;$short=substr($class,strlen($prefix));foreach([AVANIK_DIR.'/inc/'.$short.'.php',AVANIK_DIR.'/inc/'.preg_replace('/(?<!^)([A-Z])/','-$1',$short).'.php'] as $file){if(is_file($file)){require_once $file;return;}}});
require_once AVANIK_DIR.'/inc/ThemeSettings.php';
$settingsClass=$avanik_prefix.'ThemeSettings';if(class_exists($settingsClass))$settingsClass::boot();
add_action('after_setup_theme',function(){add_theme_support('title-tag');add_theme_support('post-thumbnails');add_theme_support('custom-logo',['height'=>70,'width'=>220,'flex-height'=>true,'flex-width'=>true]);add_theme_support('html5',['search-form','comment-form','comment-list','gallery','caption','style','script']);register_nav_menus(['primary'=>'منوی اصلی آوانیک']);});
add_action('wp_enqueue_scripts',function(){
    wp_enqueue_style('avanik-font','https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;500;600;700;800;900&display=swap',[],null);
    wp_enqueue_style('avanik-style',get_stylesheet_uri(),[],AVANIK_VERSION);
    wp_enqueue_style('avanik-modern-v048',AVANIK_URI.'/assets/css/avanik-modern-v048.css',['avanik-style'],AVANIK_VERSION);
    wp_enqueue_style('avanik-reference-v041',AVANIK_URI.'/assets/css/avanik-reference-v041.css',['avanik-modern-v048'],AVANIK_VERSION);
    wp_enqueue_script('avanik-reference-v041',AVANIK_URI.'/assets/js/avanik-reference-v041.js',[],AVANIK_VERSION,true);
    wp_localize_script('avanik-reference-v041','AvanikData',['ajaxUrl'=>admin_url('admin-ajax.php'),'home'=>home_url('/')]);
});
function avanik_jalali_date($gy,$gm,$gd){$g=[0,31,59,90,120,151,181,212,243,273,304,334];$gy2=($gm>2)?$gy+1:$gy;$d=355666+365*$gy+intdiv($gy2+3,4)-intdiv($gy2+99,100)+intdiv($gy2+399,400)+$gd+$g[$gm-1];$jy=-1595+33*intdiv($d,12053);$d%=12053;$jy+=4*intdiv($d,1461);$d%=1461;if($d>365){$jy+=intdiv($d-1,365);$d=($d-1)%365;}$jm=$d<186?1+intdiv($d,31):7+intdiv($d-186,30);$jd=1+($d<186?$d%31:($d-186)%30);return sprintf('%04d/%02d/%02d',$jy,$jm,$jd);}
function avanik_today_jalali(){return avanik_jalali_date((int)current_time('Y'),(int)current_time('m'),(int)current_time('j'));}
function avanik_option($key,$default=''){return get_option('avanik_'.$key,$default);}
add_action('wp_head',function(){$n=avanik_option('navy','#082B52');$g=avanik_option('gold','#F2B134');echo '<style>:root{--avanik-navy:'.esc_attr($n).';--avanik-gold:'.esc_attr($g).';}</style>';});
add_action('wp_ajax_avanik_demo_search','avanik_demo_search');add_action('wp_ajax_nopriv_avanik_demo_search','avanik_demo_search');function avanik_demo_search(){wp_send_json_success(['url'=>home_url('/رزرو-پرواز/')]);}
add_action('after_setup_theme',function()use($avanik_prefix){$themeClass=$avanik_prefix.'Theme';$setupClass=$avanik_prefix.'ThemeSetup';/* Theme::boot */if(class_exists($themeClass))$themeClass::boot();/* ThemeSetup::register */if(class_exists($setupClass))$setupClass::register();},20);
