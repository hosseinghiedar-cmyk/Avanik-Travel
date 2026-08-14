<?php
namespace Avanik;
if (!defined('ABSPATH')) exit;
final class ElementorSearchWidget {
 public static function boot(): void { add_action('elementor/widgets/register',[self::class,'register']); }
 public static function register($manager): void {
  if (!class_exists('\\Elementor\\Widget_Base')) return;
  $manager->register(new class extends \Elementor\Widget_Base {
   public function get_name(){return 'avanik_search_builder';}
   public function get_title(){return 'آوانیک — جستجوی پرواز و تور';}
   public function get_icon(){return 'eicon-search';}
   public function get_categories(){return ['avanik'];}
   public function get_keywords(){return ['avanik','flight','tour','search','پرواز','تور','جستجو'];}
   protected function register_controls(){
    $this->start_controls_section('tabs',['label'=>'تب‌های جستجو']);
    $r=new \Elementor\Repeater();
    $r->add_control('label',['label'=>'عنوان','type'=>\Elementor\Controls_Manager::TEXT,'default'=>'پرواز داخلی']);
    $r->add_control('service',['label'=>'نوع','type'=>\Elementor\Controls_Manager::SELECT,'options'=>['domestic-flight'=>'پرواز داخلی','foreign-flight'=>'پرواز خارجی','domestic-tour'=>'تور داخلی','foreign-tour'=>'تور خارجی','hotel'=>'هتل'],'default'=>'domestic-flight']);
    $this->add_control('items',['label'=>'تب‌ها','type'=>\Elementor\Controls_Manager::REPEATER,'fields'=>$r->get_controls(),'default'=>[['label'=>'پرواز داخلی','service'=>'domestic-flight'],['label'=>'پرواز خارجی','service'=>'foreign-flight'],['label'=>'تور داخلی','service'=>'domestic-tour'],['label'=>'تور خارجی','service'=>'foreign-tour'],['label'=>'هتل','service'=>'hotel']],'title_field'=>'{{{ label }}}']);
    $this->end_controls_section();
    $this->start_controls_section('fields',['label'=>'فیلدها']);
    foreach(['origin'=>'مبدا','destination'=>'مقصد','swap'=>'فلش جابه‌جایی','departure'=>'تاریخ رفت','return'=>'تاریخ برگشت','passengers'=>'مسافران'] as $k=>$l)$this->add_control('show_'.$k,['label'=>'نمایش '.$l,'type'=>\Elementor\Controls_Manager::SWITCHER,'default'=>'yes']);
    $this->add_control('search_text',['label'=>'متن دکمه جستجو','type'=>\Elementor\Controls_Manager::TEXT,'default'=>'جستجو']);
    $this->end_controls_section();
    $this->start_controls_section('layout',['label'=>'چیدمان']);
    $this->add_responsive_control('gap',['label'=>'فاصله فیلدها','type'=>\Elementor\Controls_Manager::SLIDER,'size_units'=>['px'],'range'=>['px'=>['min'=>0,'max'=>40]],'default'=>['unit'=>'px','size'=>10],'selectors'=>['{{WRAPPER}} .avanik-builder-search-form'=>'gap:{{SIZE}}px;']]);
    $this->add_responsive_control('height',['label'=>'ارتفاع فیلدها','type'=>\Elementor\Controls_Manager::SLIDER,'size_units'=>['px'],'range'=>['px'=>['min'=>36,'max'=>70]],'default'=>['unit'=>'px','size'=>46],'selectors'=>['{{WRAPPER}} .avanik-search-control'=>'height:{{SIZE}}px;']]);
    $this->add_responsive_control('button_width',['label'=>'عرض دکمه جستجو','type'=>\Elementor\Controls_Manager::SLIDER,'size_units'=>['px','%'],'range'=>['px'=>['min'=>70,'max'=>240],'%'=>['min'=>8,'max'=>30]],'default'=>['unit'=>'px','size'=>130],'selectors'=>['{{WRAPPER}} .avanik-builder-search-submit'=>'width:{{SIZE}}{{UNIT}};']]);
    $this->add_responsive_control('top_margin',['label'=>'فاصله از بالا','type'=>\Elementor\Controls_Manager::SLIDER,'size_units'=>['px'],'range'=>['px'=>['min'=>0,'max'=>100]],'selectors'=>['{{WRAPPER}} .avanik-builder-search'=>'margin-top:{{SIZE}}px;']]);
    $this->end_controls_section();
    $this->start_controls_section('style',['label'=>'ظاهر']);
    $this->add_control('background',['label'=>'رنگ زمینه','type'=>\Elementor\Controls_Manager::COLOR,'selectors'=>['{{WRAPPER}} .avanik-builder-search'=>'background-color:{{VALUE}};']]);
    $this->add_control('radius',['label'=>'گردی گوشه‌ها','type'=>\Elementor\Controls_Manager::SLIDER,'size_units'=>['px'],'range'=>['px'=>['min'=>0,'max'=>40]],'selectors'=>['{{WRAPPER}} .avanik-builder-search'=>'border-radius:{{SIZE}}px;']]);
    $this->end_controls_section();
   }
   private function field($type,$label,$value=''){return '<div class="avanik-search-control avanik-builder-field" data-builder-field="'.$type.'"><input type="hidden" data-builder-value="'.$type.'" value="'.$value.'"><button type="button" class="avanik-builder-trigger" data-builder-open="'.$type.'"><span data-builder-label="'.$type.'">'.$label.'</span><span>⌄</span></button><div class="avanik-builder-dropdown" data-builder-dropdown="'.$type.'"></div></div>';}
   protected function render(){
    $s=$this->get_settings_for_display();
    echo '<section class="avanik-builder-search"><div class="avanik-search-tabs avanik-builder-tabs">';foreach(($s['items']??[]) as $i=>$tab){echo '<button type="button" class="avanik-search-tab '.($i===0?'active':'').'" data-service="'.esc_attr($tab['service']).'">'.esc_html($tab['label']).'</button>';}echo '</div><form class="avanik-builder-search-form" data-avanik-builder-search onsubmit="return window.AvanikBuilderSearch?AvanikBuilderSearch.submit(event):false;">';
    if(!empty($s['show_origin']))echo $this->field('origin','مبدا','tehran');
    if(!empty($s['show_swap']))echo '<button type="button" class="avanik-builder-swap" data-builder-swap aria-label="جابه‌جایی مبدا و مقصد">↔</button>';
    if(!empty($s['show_destination']))echo $this->field('destination','مقصد','mashhad');
    if(!empty($s['show_departure']))echo $this->field('departure','تاریخ رفت');
    if(!empty($s['show_return']))echo $this->field('return','تاریخ برگشت');
    if(!empty($s['show_passengers']))echo '<div class="avanik-search-control avanik-builder-passengers"><button type="button" class="avanik-builder-trigger" data-builder-passengers><span data-builder-passenger-label>۱ مسافر</span><span>⌄</span></button><div class="avanik-builder-passenger-menu"><div><span>بزرگسال</span><button type="button" data-p="adult" data-step="-1">−</button><b data-count="adult">۱</b><button type="button" data-p="adult" data-step="1">+</button></div><div><span>کودک</span><button type="button" data-p="child" data-step="-1">−</button><b data-count="child">۰</b><button type="button" data-p="child" data-step="1">+</button></div><div><span>نوزاد</span><button type="button" data-p="infant" data-step="-1">−</button><b data-count="infant">۰</b><button type="button" data-p="infant" data-step="1">+</button></div><button type="button" class="avanik-builder-done" data-builder-passenger-done>تأیید</button></div></div>';
    echo '<button type="submit" class="avanik-builder-search-submit">'.esc_html($s['search_text']??'جستجو').'</button></form><div class="avanik-builder-calendar" data-builder-calendar><div class="avanik-builder-calendar-head"><button type="button" data-builder-close>×</button><strong data-builder-cal-title></strong><span></span></div><div class="avanik-builder-switch"><button type="button" data-builder-mode="jalali" class="active">شمسی</button><button type="button" data-builder-mode="gregorian">میلادی</button></div><div class="avanik-builder-cal-nav"><button type="button" data-builder-prev>‹</button><div class="avanik-builder-cal-grid" data-builder-cal-grid></div><button type="button" data-builder-next>›</button></div></div></section>';
   }
  });
 }
}
