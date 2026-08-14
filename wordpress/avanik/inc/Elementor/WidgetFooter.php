<?php
namespace Avanik\Elementor;
defined('ABSPATH') || exit;
use Elementor\Controls_Manager; use Elementor\Widget_Base;
final class WidgetFooter extends Widget_Base {
 public function get_name(){return 'avanik_footer';} public function get_title(){return 'آوانیک — Footer';} public function get_icon(){return 'eicon-footer';} public function get_categories(){return ['avanik'];}
 protected function register_controls(){ $this->start_controls_section('content',['label'=>'محتوا']); $this->add_control('text',['label'=>'توضیحات','type'=>Controls_Manager::TEXTAREA,'default'=>'آوانیک، همراه مطمئن سفرهای شما']); $this->add_control('copyright',['label'=>'کپی‌رایت','type'=>Controls_Manager::TEXT,'default'=>'© تمامی حقوق برای آوانیک محفوظ است.']); $this->end_controls_section(); }
 protected function render(){ $s=$this->get_settings_for_display(); echo '<footer class="avanik-builder-footer" dir="rtl"><div class="avanik-footer-inner"><div>'.esc_html($s['text']).'</div><small>'.esc_html($s['copyright']).'</small></div></footer>'; }
}
