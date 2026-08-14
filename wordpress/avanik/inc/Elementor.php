<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class ElementorIntegration {
    public static function boot(): void {
        add_action('wp_enqueue_scripts', [__CLASS__, 'assets']);
        add_action('elementor/elements/categories_registered', [__CLASS__, 'category']);
        add_action('elementor/widgets/register', [__CLASS__, 'widgets']);
    }
    public static function assets(): void {
        wp_register_style('avanik-elementor', get_template_directory_uri() . '/assets/css/elementor-builder.css', [], '0.6.0');
        wp_register_script('avanik-elementor', get_template_directory_uri() . '/assets/js/elementor-builder.js', [], '0.6.0', true);
        if (did_action('elementor/loaded')) { wp_enqueue_style('avanik-elementor'); wp_enqueue_script('avanik-elementor'); }
    }
    public static function category($elements_manager): void { $elements_manager->add_category('avanik', ['title'=>'آوانیک','icon'=>'fa fa-plane']); }
    public static function widgets($widgets_manager): void {
        if (!did_action('elementor/loaded')) return;
        require_once __DIR__ . '/Elementor/WidgetSearch.php'; require_once __DIR__ . '/Elementor/WidgetHero.php'; require_once __DIR__ . '/Elementor/WidgetHeader.php'; require_once __DIR__ . '/Elementor/WidgetFooter.php';
        $widgets_manager->register(new \Avanik\Elementor\WidgetSearch()); $widgets_manager->register(new \Avanik\Elementor\WidgetHero()); $widgets_manager->register(new \Avanik\Elementor\WidgetHeader()); $widgets_manager->register(new \Avanik\Elementor\WidgetFooter());
    }
}
ElementorIntegration::boot();
