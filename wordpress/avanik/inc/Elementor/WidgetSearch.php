<?php
defined('ABSPATH') || exit;
namespace Avanik\Elementor;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

final class WidgetSearch extends Widget_Base {
    public function get_name() { return 'avanik_search'; }
    public function get_title() { return 'آوانیک — جستجوی سفر'; }
    public function get_icon() { return 'eicon-search'; }
    public function get_categories() { return ['avanik']; }

    protected function register_controls() {
        $this->start_controls_section('content', ['label'=>'محتوا']);
        $this->add_control('title', ['label'=>'عنوان','type'=>Controls_Manager::TEXT,'default'=>'جستجوی سفر']);
        $this->add_control('show_tabs', ['label'=>'نمایش تب‌ها','type'=>Controls_Manager::SWITCHER,'default'=>'yes']);
        $this->add_control('show_swap', ['label'=>'نمایش فلش جابه‌جایی','type'=>Controls_Manager::SWITCHER,'default'=>'yes']);
        $this->add_control('show_return', ['label'=>'نمایش تاریخ برگشت','type'=>Controls_Manager::SWITCHER,'default'=>'yes']);
        $this->add_control('show_passengers', ['label'=>'نمایش مسافران','type'=>Controls_Manager::SWITCHER,'default'=>'yes']);
        $this->add_control('button_text', ['label'=>'متن دکمه','type'=>Controls_Manager::TEXT,'default'=>'جستجو']);
        $this->end_controls_section();

        $this->start_controls_section('layout', ['label'=>'چیدمان']);
        $this->add_responsive_control('top_spacing', ['label'=>'فاصله از بالا','type'=>Controls_Manager::SLIDER,'size_units'=>['px'],'range'=>['px'=>['min'=>0,'max'=>200]],'default'=>['size'=>0]]);
        $this->add_responsive_control('field_height', ['label'=>'ارتفاع فیلد','type'=>Controls_Manager::SLIDER,'size_units'=>['px'],'range'=>['px'=>['min'=>40,'max'=>90]],'default'=>['size'=>62]]);
        $this->add_responsive_control('gap', ['label'=>'فاصله فیلدها','type'=>Controls_Manager::SLIDER,'size_units'=>['px'],'range'=>['px'=>['min'=>4,'max'=>40]],'default'=>['size'=>10]]);
        $this->end_controls_section();

        $this->start_controls_section('style', ['label'=>'ظاهر']);
        $this->add_control('card_bg', ['label'=>'رنگ پس‌زمینه','type'=>Controls_Manager::COLOR,'default'=>'#ffffff','selectors'=>['{{WRAPPER}} .avanik-builder-search'=>'background-color: {{VALUE}};']]);
        $this->add_control('radius', ['label'=>'گردی گوشه','type'=>Controls_Manager::SLIDER,'size_units'=>['px'],'range'=>['px'=>['min'=>0,'max'=>40]],'default'=>['size'=>18],'selectors'=>['{{WRAPPER}} .avanik-builder-search'=>'border-radius: {{SIZE}}px;']]);
        $this->end_controls_section();
    }

    protected function render() {
        $s=$this->get_settings_for_display();
        $style=sprintf('--av-top:%spx;--av-field-height:%spx;--av-gap:%spx;', esc_attr($s['top_spacing']['size']??0), esc_attr($s['field_height']['size']??62), esc_attr($s['gap']['size']??10));
        ?>
        <section class="avanik-builder-search" style="<?php echo esc_attr($style); ?>" dir="rtl">
          <?php if ($s['title']) : ?><h2 class="avanik-builder-title"><?php echo esc_html($s['title']); ?></h2><?php endif; ?>
          <?php if ('yes'===$s['show_tabs']) : ?><div class="avanik-search-tabs">
            <button type="button" class="avanik-search-tab active" data-service="domestic-flight">پرواز داخلی</button>
            <button type="button" class="avanik-search-tab" data-service="foreign-flight">پرواز خارجی</button>
            <button type="button" class="avanik-search-tab" data-service="domestic-tour">تور داخلی</button>
            <button type="button" class="avanik-search-tab" data-service="foreign-tour">تور خارجی</button>
          </div><?php endif; ?>
          <form class="avanik-builder-form" method="get" action="<?php echo esc_url(home_url('/flight-search')); ?>">
            <div class="avanik-builder-field" data-builder-field="origin"><button type="button" class="avanik-builder-trigger"><small>مبدا</small><span data-builder-label="origin">تهران</span></button><input type="hidden" data-builder-value="origin" name="origin" value="tehran"><div class="avanik-builder-dropdown" data-builder-dropdown="origin"></div></div>
            <?php if ('yes'===$s['show_swap']) : ?><button class="avanik-builder-swap" type="button" data-builder-swap aria-label="جابه‌جایی مبدا و مقصد">↔</button><?php endif; ?>
            <div class="avanik-builder-field" data-builder-field="destination"><button type="button" class="avanik-builder-trigger"><small>مقصد</small><span data-builder-label="destination">مشهد</span></button><input type="hidden" data-builder-value="destination" name="destination" value="mashhad"><div class="avanik-builder-dropdown" data-builder-dropdown="destination"></div></div>
            <div class="avanik-builder-field date-field"><button type="button" class="avanik-builder-trigger" data-builder-open="departure"><small>تاریخ رفت</small><span data-builder-label="departure">امروز</span></button></div>
            <?php if ('yes'===$s['show_return']) : ?><div class="avanik-builder-field date-field"><button type="button" class="avanik-builder-trigger" data-builder-open="return"><small>تاریخ برگشت</small><span data-builder-label="return">انتخاب تاریخ</span></button></div><?php endif; ?>
            <?php if ('yes'===$s['show_passengers']) : ?><div class="avanik-builder-field avanik-builder-passengers"><button type="button" class="avanik-builder-trigger" data-builder-passengers><small>مسافران</small><span data-builder-passenger-label>۱ مسافر</span></button><div class="avanik-passenger-panel"><div><span>بزرگسال</span><button type="button" data-p="adult" data-step="1">+</button><b data-count="adult">۱</b><button type="button" data-p="adult" data-step="-1">−</button></div><div><span>کودک</span><button type="button" data-p="child" data-step="1">+</button><b data-count="child">۰</b><button type="button" data-p="child" data-step="-1">−</button></div><div><span>نوزاد</span><button type="button" data-p="infant" data-step="1">+</button><b data-count="infant">۰</b><button type="button" data-p="infant" data-step="-1">−</button></div><button type="button" data-builder-passenger-done class="passenger-done">تأیید</button></div></div><?php endif; ?>
            <button class="avanik-builder-submit" type="submit"><?php echo esc_html($s['button_text']); ?></button>
          </form>
          <div class="avanik-builder-calendar" data-builder-calendar><div class="calendar-head"><button type="button" data-builder-prev>‹</button><strong data-builder-cal-title></strong><button type="button" data-builder-next>›</button></div><div class="calendar-modes"><button type="button" class="active" data-builder-mode="jalali">شمسی</button><button type="button" data-builder-mode="gregorian">میلادی</button></div><div class="calendar-grid" data-builder-cal-grid></div><button type="button" data-builder-close class="calendar-close">بستن</button></div>
        </section>
        <?php
    }
}
