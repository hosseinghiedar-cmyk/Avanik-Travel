<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class ThemeSettings {
  private const OPTION='avanik_theme_options';
  public static function register(): void {
    add_action('admin_menu',[self::class,'admin_menu']);
    add_action('admin_init',[self::class,'register_settings']);
    add_action('admin_enqueue_scripts',[self::class,'admin_assets']);
    add_action('wp_head',[self::class,'frontend_css'],30);
    add_filter('body_class',[self::class,'body_class']);
  }
  public static function defaults(): array {
    return [
      'primary'=>'#072B5A','gold'=>'#F2B134','white'=>'#FFFFFF','text'=>'#14243A',
      'hero_title'=>'پرواز به استانبول','hero_subtitle'=>'با بهترین قیمت و خدمات ویژه',
      'phone'=>'021-12345678','support'=>'پشتیبانی ۲۴ ساعته',
      'logo_url'=>get_template_directory_uri().'/assets/images/avanik-logo-reference.png',
      'logo_white_url'=>get_template_directory_uri().'/assets/images/avanik-logo-reference-white.png',
      'hero_image'=>'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?auto=format&fit=crop&w=1800&q=82',
      'hero_image_2'=>'https://images.unsplash.com/photo-1524231757912-21f4fe3a7200?auto=format&fit=crop&w=1800&q=82',
      'hero_image_3'=>'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&w=1800&q=82',
      'hero_interval'=>6,
      'sticky_header'=>1,'animations'=>1,'show_tours'=>1,'show_why'=>1,'show_airlines'=>1,
      'instagram'=>'','telegram'=>'','whatsapp'=>'','linkedin'=>'','x'=>'','header_socials'=>1,
    ];
  }
  public static function get(string $key='') {
    $options=wp_parse_args((array)get_option(self::OPTION,[]),self::defaults());
    return $key===''?$options:($options[$key]??null);
  }
  public static function admin_menu(): void {
    add_menu_page('آوانیک','آوانیک','manage_options','avanik-theme-settings',[self::class,'render'],'dashicons-airplane',3);
    add_submenu_page('avanik-theme-settings','تنظیمات قالب آوانیک','تنظیمات قالب','manage_options','avanik-theme-settings',[self::class,'render']);
    add_submenu_page('avanik-theme-settings','راهنمای قالب آوانیک','راهنمای قالب','manage_options','avanik-theme-guide',[self::class,'guide']);
  }
  public static function register_settings(): void {
    register_setting('avanik_theme_options_group',self::OPTION,['type'=>'array','sanitize_callback'=>[self::class,'sanitize'],'default'=>self::defaults()]);
  }
  public static function admin_assets(string $hook): void {
    if(strpos($hook,'avanik-theme-settings')===false && strpos($hook,'avanik-theme-guide')===false)return;
    wp_enqueue_media();wp_enqueue_script('jquery');
    $script=<<<'JS'
jQuery(function($){$('.av-media-button').on('click',function(e){e.preventDefault();var b=$(this),t=$(b.data('target'));var f=wp.media({title:'انتخاب تصویر آوانیک',button:{text:'استفاده از تصویر'},multiple:false});f.on('select',function(){var a=f.state().get('selection').first().toJSON();t.val(a.url).trigger('change');});f.open();});});
JS;
    wp_add_inline_script('jquery',$script);
  }
  public static function sanitize($input): array {
    $d=self::defaults();$input=is_array($input)?$input:[];$out=$d;
    foreach(['primary','gold','white','text'] as $key){$c=isset($input[$key])?sanitize_hex_color($input[$key]):null;if($c)$out[$key]=$c;}
    foreach(['hero_title','hero_subtitle','phone','support','logo_url','logo_white_url','hero_image','hero_image_2','hero_image_3','instagram','telegram','whatsapp','linkedin','x'] as $key){if(isset($input[$key]))$out[$key]=in_array($key,['logo_url','logo_white_url','hero_image','hero_image_2','hero_image_3'],true)?esc_url_raw($input[$key]):sanitize_text_field($input[$key]);}
    $out['hero_interval']=max(3,min(20,absint($input['hero_interval']??6)));
    foreach(['sticky_header','animations','show_tours','show_why','show_airlines','header_socials'] as $key)$out[$key]=empty($input[$key])?0:1;
    return $out;
  }
  private static function field(string $name,string $label,string $type='text',string $help=''): void {
    $value=esc_attr((string)self::get($name));echo '<tr><th scope="row"><label for="avanik_'.$name.'">'.esc_html($label).'</label></th><td>';
    if($type==='color')echo '<input id="avanik_'.$name.'" name="avanik_theme_options['.$name.']" type="color" value="'.$value.'" class="av-admin-color"><code class="av-admin-color-code">'.$value.'</code>';
    elseif($type==='url')echo '<div class="av-media-row"><input id="avanik_'.$name.'" name="avanik_theme_options['.$name.']" type="url" value="'.$value.'" class="regular-text code"><button class="button av-media-button" data-target="#avanik_'.$name.'">انتخاب از رسانه</button></div>';
    elseif($type==='number')echo '<input id="avanik_'.$name.'" name="avanik_theme_options['.$name.']" type="number" min="3" max="20" value="'.$value.'" class="small-text">';
    else echo '<input id="avanik_'.$name.'" name="avanik_theme_options['.$name.']" type="text" value="'.$value.'" class="regular-text">';
    if($help)echo '<p class="description">'.esc_html($help).'</p>';echo '</td></tr>';
  }
  private static function toggle(string $name,string $label,string $help=''): void {echo '<tr><th scope="row">'.esc_html($label).'</th><td><label class="av-admin-switch"><input type="checkbox" name="avanik_theme_options['.$name.']" value="1" '.checked((int)self::get($name),1,false).'><span></span></label>';if($help)echo '<p class="description">'.esc_html($help).'</p>';echo '</td></tr>';}
  public static function render(): void {
    if(!current_user_can('manage_options'))return;$logo=self::get('logo_url');
    ?>
    <div class="wrap av-admin-wrap" dir="rtl"><style>
      .av-admin-wrap{max-width:1180px}.av-admin-hero{background:linear-gradient(135deg,#061f42,#0b4b87);color:#fff;border-radius:22px;padding:24px 30px;margin:20px 0;display:flex;align-items:center;justify-content:space-between;gap:24px;box-shadow:0 18px 45px rgba(7,43,90,.16)}.av-admin-hero img{width:250px;height:82px;object-fit:contain;background:rgba(255,255,255,.08);border-radius:14px;padding:8px}.av-admin-hero h1{color:#fff;margin:0 0 6px;font-size:28px}.av-admin-hero p{margin:0;opacity:.85}.av-admin-card{background:#fff;border:1px solid #e4e9ef;border-radius:18px;padding:24px;margin:18px 0;box-shadow:0 8px 28px rgba(15,35,60,.06)}.av-admin-card h2{margin-top:0;color:#072B5A;border-bottom:2px solid #F2B134;padding-bottom:10px}.av-admin-card th{width:250px;text-align:right}.av-admin-card td,.av-admin-card th{padding:15px 10px}.av-admin-color{width:70px!important;height:42px!important;padding:3px!important}.av-admin-color-code{margin-right:10px}.av-media-row{display:flex;gap:8px;align-items:center}.av-admin-switch{display:inline-flex;align-items:center}.av-admin-switch input{display:none}.av-admin-switch span{width:52px;height:28px;background:#c9d1dc;border-radius:30px;display:inline-block;position:relative;cursor:pointer}.av-admin-switch span:after{content:"";width:22px;height:22px;background:#fff;border-radius:50%;position:absolute;top:3px;right:3px;transition:.2s;box-shadow:0 2px 5px #0002}.av-admin-switch input:checked+span{background:#F2B134}.av-admin-switch input:checked+span:after{right:27px}.av-admin-submit{background:#072B5A!important;border-color:#072B5A!important;padding:8px 28px!important}.av-admin-note{border-right:4px solid #F2B134;background:#fff9ed;padding:14px 18px;border-radius:10px;margin-top:18px}.av-admin-socials{display:grid;grid-template-columns:repeat(2,1fr);gap:12px}.av-admin-socials label{display:flex;flex-direction:column;gap:6px;font-weight:700}.av-admin-socials input{width:100%;padding:8px}.av-admin-list{line-height:2}
    </style>
      <div class="av-admin-hero"><div><h1>مرکز مدیریت قالب آوانیک</h1><p>ظاهر، تصاویر، اسلایدر، شبکه‌های اجتماعی و رفتار صفحه اصلی را از همین بخش مدیریت کنید.</p></div><img src="<?php echo esc_url($logo); ?>" alt="آوانیک پرواز آسیا"></div>
      <form method="post" action="options.php"><?php settings_fields('avanik_theme_options_group'); ?>
        <div class="av-admin-card"><h2>هویت بصری</h2><table class="form-table"><?php self::field('primary','رنگ سرمه‌ای اصلی','color');self::field('gold','رنگ طلایی آوانیک','color');self::field('white','رنگ سفید','color');self::field('text','رنگ متن','color'); ?></table></div>
        <div class="av-admin-card"><h2>لوگو و تصاویر اسلایدر</h2><table class="form-table"><?php self::field('logo_url','لوگوی اصلی','url','نسخه رنگی لوگو در Header و صفحات روشن.');self::field('logo_white_url','لوگوی سفید','url','برای Footer و بخش‌های تیره.');self::field('hero_image','تصویر اسلاید اول','url');self::field('hero_image_2','تصویر اسلاید دوم','url');self::field('hero_image_3','تصویر اسلاید سوم','url');self::field('hero_interval','زمان تعویض اسلاید (ثانیه)','number'); ?></table></div>
        <div class="av-admin-card"><h2>محتوای اصلی</h2><table class="form-table"><?php self::field('hero_title','عنوان اصلی Hero');self::field('hero_subtitle','زیرعنوان Hero');self::field('phone','شماره تماس');self::field('support','متن پشتیبانی'); ?></table></div>
        <div class="av-admin-card"><h2>شبکه‌های اجتماعی Header</h2><?php self::toggle('header_socials','نمایش شبکه‌های اجتماعی در Header'); ?><div class="av-admin-socials"><?php foreach(['instagram'=>'اینستاگرام','telegram'=>'تلگرام','whatsapp'=>'واتساپ','linkedin'=>'لینکدین','x'=>'ایکس'] as $k=>$l){echo '<label>'.esc_html($l).'<input type="url" name="avanik_theme_options['.$k.']" value="'.esc_attr((string)self::get($k)).'" placeholder="https://..."></label>';} ?></div></div>
        <div class="av-admin-card"><h2>نمایش و انیمیشن</h2><table class="form-table"><?php self::toggle('sticky_header','هدر چسبان');self::toggle('animations','انیمیشن‌های سبک');self::toggle('show_tours','نمایش تورهای ویژه');self::toggle('show_why','نمایش بخش چرا آوانیک');self::toggle('show_airlines','نمایش ایرلاین‌ها'); ?></table></div>
        <div class="av-admin-note">مدیریت «ارائه‌دهندگان» و «اعلان‌ها» از این صفحه خارج شده و به منوهای مستقل آوانیک منتقل شده است.</div><?php submit_button('ذخیره تغییرات آوانیک','primary','submit',true,['class'=>'av-admin-submit']); ?>
      </form>
    </div><?php
  }
  public static function guide(): void {if(!current_user_can('manage_options'))return;echo '<div class="wrap av-admin-wrap" dir="rtl"><h1>راهنمای قالب آوانیک</h1><div class="av-admin-card"><h2>راهنمای سریع</h2><ol class="av-admin-list"><li>از منوی «آوانیک ← تنظیمات قالب» ظاهر و تصاویر را مدیریت کنید.</li><li>برای تغییر لوگو یا اسلایدر از دکمه «انتخاب از رسانه» استفاده کنید.</li><li>تعداد مسافر در جستجوی پرواز با بزرگسال، کودک و سن کودک قابل تنظیم است.</li><li>ارائه‌دهندگان و اعلان‌ها در منوهای مستقل آوانیک قرار دارند.</li></ol></div></div>';}
  public static function body_class(array $classes): array {if((int)self::get('sticky_header')===1)$classes[]='av-sticky-header-enabled';if((int)self::get('animations')===1)$classes[]='av-animations-enabled';return $classes;}
  public static function frontend_css(): void {if(is_admin())return;$o=self::get();printf('<style>:root{--av-primary:%1$s;--av-primary-dark:%1$s;--av-accent:%2$s;--av-white:%3$s;--av-text:%4$s;}</style>',esc_attr($o['primary']),esc_attr($o['gold']),esc_attr($o['white']),esc_attr($o['text']));}
}
