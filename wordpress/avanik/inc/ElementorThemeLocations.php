<?php
namespace Avanik;
if (!defined('ABSPATH')) exit;

/**
 * Elementor Pro Theme Builder integration.
 * Elementor Pro can own the global Header and Footer while the legacy
 * templates remain a safe fallback when no Theme Builder template exists.
 */
final class ElementorThemeLocations {
    private static bool $header_rendered = false;
    private static bool $footer_rendered = false;

    public static function boot(): void {
        add_action('elementor/theme/register_locations', [self::class, 'register_locations']);
        add_action('wp_body_open', [self::class, 'render_header_at_body_open'], 1);
        add_action('wp_footer', [self::class, 'render_footer_at_wp_footer'], 1);
    }

    public static function register_locations($manager): void {
        if (!is_object($manager)) return;
        $manager->register_all_core_location();
    }

    public static function render_header_at_body_open(): void {
        if (!function_exists('elementor_theme_do_location')) return;
        if (self::$header_rendered) return;
        self::$header_rendered = (bool) elementor_theme_do_location('header');
        if (self::$header_rendered) {
            echo '<style id="avanik-elementor-header-override">.avanik-site > .avanik-header,.avanik-site > .avanik-login-modal{display:none!important}</style>';
        }
    }

    public static function render_footer_at_wp_footer(): void {
        if (!function_exists('elementor_theme_do_location')) return;
        if (self::$footer_rendered) return;
        self::$footer_rendered = (bool) elementor_theme_do_location('footer');
        if (self::$footer_rendered) {
            echo '<style id="avanik-elementor-footer-override">.avanik-site > .avanik-footer{display:none!important}</style>';
        }
    }
}
