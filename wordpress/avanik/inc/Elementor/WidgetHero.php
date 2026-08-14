<?php
defined('ABSPATH') || exit;
namespace Avanik\Elementor;
use Elementor\Controls_Manager;
use Elementor\Widget_Base;
final class WidgetHero extends Widget_Base {
 public function get_name(){return 'avanik_hero';} public function get_title(){return 'آوانیک — Hero';} public function get_icon(){return 'eicon-banner';} public function get_categories(){return ['avanik'];}
 protected function register_controls(){
  $this->start_controls_section('content',['label'=>'محتوا']);
  $this->add_control('eyebrow',['label'=>'متن بالایی','type'=>Controls_Manager::TEXT,'default'=>'آوانیک، همراه سفر شما']);
  $this->add_control('title',['label'=>'عنوان','type'=>Controls_Manager::TEXT,'default'=>'سفر بعدی شما از اینجا شروع می‌شود']);
  $this->add_control('highlight',['label'=>'کلمه برجسته','type'=>Controls_Manager::TEXT,'default'=>'شروع می‌شود']);
  $this->add_control('subtitle',['label'=>'توضیح','type'=>Controls_Manager::TEXTAREA,'default'=>'رزرو بلیط هواپیما، هتل و تور با آوانیک']);
  $this->add_control('background',['label'=>'تصویر پس‌زمینه','type'=>Controls_Manager::MEDIA]);
  $this->end_controls_section();
  $this->start_controls_section('style',['label'=>'ظاهر']);
  $this->add_responsive_control('height',['label'=>'ارتفاع','type'=>Controls_Manager::SLIDER,'size_units'=>['px','vh'],'range'=>['px'=>['min'=>220,'max'=>800],'vh'=>['min'=>20,'max'=>90]],'default'=>['size'=>480,'unit'=>'px'],'selectors'=>['{{WRAPPER}} .avanik-builder-hero'=>'min-height: {{SIZE}}{{UNIT}};']]);
  $this->add_control('title_color',['label'=>'رنگ عنوان','type'=>Controls_Manager::COLOR,'default'=>'#fff','selectors'=>['{{WRAPPER}} .avanik-builder-hero h1'=>'color:{{VALUE}};']]);
  $this->end_controls_section();
 }
 protected function render(){ $s=$this->get_settings_for_display(); $bg=!empty($s['background']['url'])?'style="background-image:url('.esc_url($s['background']['url']).')"':''; echo '<section class="avanik-builder-hero" '.$bg.' dir="rtl"><div class="avanik-hero-overlay"></div><div class="avanik-hero-content">'; if($s['eyebrow'])echo '<div class="avanik-hero-eyebrow">'.esc_html($s['eyebrow']).'</div>'; echo '<h1>'.esc_html($s['title']).'</h1>'; if($s['subtitle'])echo '<p>'.esc_html($s['subtitle']).'</p>'; echo '</div></section>'; }
}
