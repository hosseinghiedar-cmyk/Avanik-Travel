<?php
namespace Avanik;
if (!defined('ABSPATH')) exit;

/**
 * Editable Avanik presentation widgets. Booking/search logic remains in
 * ElementorIntegration and the theme JS; these controls only change presentation.
 */
final class ElementorBuilderWidgets {
    public static function boot(): void {
        add_action('elementor/widgets/register', [self::class, 'register']);
    }

    public static function register($manager): void {
        if (!class_exists('\\Elementor\\Widget_Base')) return;
        $manager->register(new class extends \Elementor\Widget_Base {
            public function get_name(){return 'avanik_hero_builder';}
            public function get_title(){return 'آوانیک — Hero قابل ویرایش';}
            public function get_icon(){return 'eicon-banner';}
            public function get_categories(){return ['avanik'];}
            protected function register_controls(){
                $this->start_controls_section('content',['label'=>'محتوای Hero']);
                $this->add_control('eyebrow',['label'=>'متن بالایی','type'=>\Elementor\Controls_Manager::TEXT,'default'=>'سفر بعدی شما از اینجا شروع می‌شود']);
                $this->add_control('title',['label'=>'عنوان','type'=>\Elementor\Controls_Manager::TEXT,'default'=>'پرواز به']);
                $this->add_control('accent',['label'=>'کلمه برجسته','type'=>\Elementor\Controls_Manager::TEXT,'default'=>'استانبول']);
                $this->add_control('subtitle',['label'=>'توضیح','type'=>\Elementor\Controls_Manager::TEXT,'default'=>'با بهترین قیمت و خدمات ویژه']);
                $this->add_control('background',['label'=>'تصویر پس‌زمینه','type'=>\Elementor\Controls_Manager::MEDIA,'default'=>['url'=>AVANIK_URI.'/assets/images/hero-reference-istanbul.jpg']]);
                $this->end_controls_section();
                $this->start_controls_section('layout',['label'=>'چیدمان']);
                $this->add_responsive_control('content_width',['label'=>'عرض محتوا','type'=>\Elementor\Controls_Manager::SLIDER,'size_units'=>['%','px'],'range'=>['%'=>['min'=>30,'max'=>100],'px'=>['min'=>300,'max'=>1400]],'default'=>['unit'=>'%','size'=>100],'selectors'=>['{{WRAPPER}} .avanik-hero-content'=> 'max-width: {{SIZE}}{{UNIT}};']]);
                $this->add_responsive_control('top_offset',['label'=>'فاصله از بالا','type'=>\Elementor\Controls_Manager::SLIDER,'size_units'=>['px'],'range'=>['px'=>['min'=>0,'max'=>300]],'selectors'=>['{{WRAPPER}} .avanik-hero-content'=> 'padding-top: {{SIZE}}px;']]);
                $this->end_controls_section();
                $this->start_controls_section('style',['label'=>'استایل']);
                $this->add_control('title_color',['label'=>'رنگ عنوان','type'=>\Elementor\Controls_Manager::COLOR,'selectors'=>['{{WRAPPER}} .avanik-hero-copy h1'=> 'color: {{VALUE}};']]);
                $this->add_control('accent_color',['label'=>'رنگ کلمه برجسته','type'=>\Elementor\Controls_Manager::COLOR,'selectors'=>['{{WRAPPER}} .avanik-hero-copy h1 strong'=> 'color: {{VALUE}};']]);
                $this->end_controls_section();
            }
            protected function render(){
                $s=$this->get_settings_for_display();
                $bg=!empty($s['background']['url'])?$s['background']['url']:avanik_option('hero',AVANIK_URI.'/assets/images/hero-reference-istanbul.jpg');
                echo '<section class="avanik-hero" style="--avanik-hero-image:url(\''.esc_url($bg).'\')"><div class="avanik-hero-bg" aria-hidden="true"></div><div class="avanik-hero-wash" aria-hidden="true"></div><div class="avanik-shell avanik-hero-content"><div class="avanik-hero-copy"><div class="avanik-eyebrow">'.esc_html($s['eyebrow']).'</div><h1>'.esc_html($s['title']).' <strong>'.esc_html($s['accent']).'</strong></h1><p>'.esc_html($s['subtitle']).'</p></div></div></section>';
            }
        });
        $manager->register(new class extends \Elementor\Widget_Base {
            public function get_name(){return 'avanik_header_builder';}
            public function get_title(){return 'آوانیک — Header قابل ویرایش';}
            public function get_icon(){return 'eicon-header';}
            public function get_categories(){return ['avanik'];}
            protected function register_controls(){
                $this->start_controls_section('brand',['label'=>'لوگو']);
                $this->add_control('logo',['label'=>'لوگو','type'=>\Elementor\Controls_Manager::MEDIA,'default'=>['url'=>AVANIK_URI.'/assets/images/avanik-logo-v041.svg']]);
                $this->add_responsive_control('logo_width',['label'=>'عرض لوگو','type'=>\Elementor\Controls_Manager::SLIDER,'size_units'=>['px'],'range'=>['px'=>['min'=>50,'max'=>300]],'default'=>['unit'=>'px','size'=>120],'selectors'=>['{{WRAPPER}} .avanik-builder-logo img'=>'width:{{SIZE}}{{UNIT}};']]);
                $this->end_controls_section();
                $this->start_controls_section('menu',['label'=>'منو']);
                $r=new \Elementor\Repeater();
                $r->add_control('label',['label'=>'عنوان','type'=>\Elementor\Controls_Manager::TEXT,'default'=>'صفحه اصلی']);
                $r->add_control('url',['label'=>'لینک','type'=>\Elementor\Controls_Manager::URL,'default'=>['url'=>'#'],'show_external'=>true]);
                $this->add_control('items',['label'=>'آیتم‌های منو','type'=>\Elementor\Controls_Manager::REPEATER,'fields'=>$r->get_controls(),'default'=>[['label'=>'صفحه اصلی','url'=>['url'=>home_url('/')]],['label'=>'پروازها','url'=>['url'=>home_url('/پروازها/')]],['label'=>'تورهای خارجی','url'=>['url'=>home_url('/تورهای-خارجی/')]],['label'=>'هتل','url'=>['url'=>home_url('/هتل/')]],['label'=>'ویزای مسافرتی','url'=>['url'=>home_url('/ویزای-مسافرتی/')]],['label'=>'درباره ما','url'=>['url'=>home_url('/درباره-ما/')]],['label'=>'تماس با ما','url'=>['url'=>home_url('/تماس-با-ما/')]]],'title_field'=>'{{{ label }}}']);
                $this->add_responsive_control('top_offset',['label'=>'فاصله از بالا','type'=>\Elementor\Controls_Manager::SLIDER,'size_units'=>['px'],'range'=>['px'=>['min'=>0,'max'=>100]],'default'=>['unit'=>'px','size'=>15],'selectors'=>['{{WRAPPER}} .avanik-builder-header'=>'padding-top:{{SIZE}}px;']]);
                $this->add_responsive_control('menu_gap',['label'=>'فاصله آیتم‌ها','type'=>\Elementor\Controls_Manager::SLIDER,'size_units'=>['px'],'range'=>['px'=>['min'=>0,'max'=>80]],'default'=>['unit'=>'px','size'=>25],'selectors'=>['{{WRAPPER}} .avanik-builder-menu'=>'gap:{{SIZE}}px;']]);
                $this->end_controls_section();
            }
            protected function render(){
                $s=$this->get_settings_for_display();echo '<header class="avanik-builder-header"><div class="avanik-shell avanik-builder-inner"><a class="avanik-builder-logo" href="'.esc_url(home_url('/')).'"><img src="'.esc_url($s['logo']['url']??AVANIK_URI.'/assets/images/avanik-logo-v041.svg').'" alt="آوانیک"></a><nav class="avanik-builder-menu">';foreach(($s['items']??[]) as $i){$url=$i['url']['url']??'#';echo '<a href="'.esc_url($url).'">'.esc_html($i['label']??'').'</a>';}echo '</nav><button class="avanik-header-user" type="button" data-login-open aria-label="ورود"><svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="3.5"></circle><path d="M4.5 20c.8-3.4 3.2-5.3 7.5-5.3s6.7 1.9 7.5 5.3"></path></svg></button></div></header>';
            }
        });
        $manager->register(new class extends \Elementor\Widget_Base {
            public function get_name(){return 'avanik_footer_builder';}
            public function get_title(){return 'آوانیک — Footer قابل ویرایش';}
            public function get_icon(){return 'eicon-footer';}
            public function get_categories(){return ['avanik'];}
            protected function register_controls(){
                $this->start_controls_section('content',['label'=>'محتوا']);
                $this->add_control('description',['label'=>'توضیحات','type'=>\Elementor\Controls_Manager::TEXTAREA,'default'=>'آوانیک پرواز آسیا، ارائه‌دهنده خدمات مسافرتی و گردشگری با تجربه‌ای متفاوت، سریع و باکیفیت.']);
                $this->add_control('copyright',['label'=>'متن کپی‌رایت','type'=>\Elementor\Controls_Manager::TEXT,'default'=>'کلیه حقوق این سایت محفوظ می‌باشد.']);
                $this->end_controls_section();
            }
            protected function render(){ $s=$this->get_settings_for_display(); echo '<footer class="avanik-footer"><div class="avanik-footer-main"><div class="avanik-shell avanik-footer-grid"><div class="avanik-footer-brand"><img src="'.esc_url(AVANIK_URI.'/assets/images/avanik-logo-white.svg').'" alt="آوانیک پرواز آسیا"><p>'.esc_html($s['description']).'</p></div><div><h3>خدمات</h3><a href="'.esc_url(home_url('/پروازها/')).'">پروازهای داخلی</a><a href="'.esc_url(home_url('/پروازها/')).'">پروازهای خارجی</a><a href="'.esc_url(home_url('/تورهای-داخلی/')).'">تورهای داخلی</a><a href="'.esc_url(home_url('/تورهای-خارجی/')).'">تورهای خارجی</a><a href="'.esc_url(home_url('/هتل/')).'">هتل</a></div><div><h3>لینک‌های سریع</h3><a href="'.esc_url(home_url('/')).'">صفحه اصلی</a><a href="'.esc_url(home_url('/درباره-ما/')).'">درباره ما</a><a href="'.esc_url(home_url('/تماس-با-ما/')).'">تماس با ما</a></div></div></div><div class="avanik-footer-bottom"><div class="avanik-shell"><span>'.esc_html($s['copyright']).'</span></div></div></footer>'; }
        });
    }
}
