<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class ElementorIntegration {
    public static function boot(): void {
        add_action('wp_enqueue_scripts', [__CLASS__, 'assets'], 20);
        add_action('elementor/elements/categories_registered', [__CLASS__, 'category']);
        add_action('elementor/widgets/register', [__CLASS__, 'widgets']);
    }
    public static function assets(): void {
        $dir = get_template_directory_uri();
        $file = get_template_directory() . '/assets/css/avanik-elementor-builder.css';
        $js = get_template_directory() . '/assets/js/elementor-builder.js';
        if (file_exists($file)) wp_enqueue_style('avanik-elementor', $dir . '/assets/css/avanik-elementor-builder.css', ['avanik-style'], (string) filemtime($file));
        if (file_exists($js)) wp_enqueue_script('avanik-elementor', $dir . '/assets/js/elementor-builder.js', [], (string) filemtime($js), true);
    }
    public static function category($elements_manager): void { $elements_manager->add_category('avanik', ['title'=>'آوانیک','icon'=>'fa fa-plane']); }
    public static function widgets($widgets_manager): void {
        if (!did_action('elementor/loaded')) return;
        require_once __DIR__ . '/Elementor/WidgetSearch.php'; require_once __DIR__ . '/Elementor/WidgetHero.php'; require_once __DIR__ . '/Elementor/WidgetHeader.php'; require_once __DIR__ . '/Elementor/WidgetFooter.php';
        $widgets_manager->register(new \Avanik\Elementor\WidgetSearch()); $widgets_manager->register(new \Avanik\Elementor\WidgetHero()); $widgets_manager->register(new \Avanik\Elementor\WidgetHeader()); $widgets_manager->register(new \Avanik\Elementor\WidgetFooter());
    }
}
ElementorIntegration::boot();