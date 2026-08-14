<?php
namespace Avanik;
if (!defined('ABSPATH')) exit;

/**
 * Elementor Pro Theme Builder integration.
 * If Elementor Pro is active, Header and Footer templates can replace the
 * legacy PHP header/footer. Without Pro, the normal theme files remain intact.
 */
final class ElementorThemeLocations {
    public static function boot(): void {
        add_action('elementor/theme/register_locations', [self::class, 'register_locations']);
        add_action('wp_enqueue_scripts', [self::class, 'frontend_assets'], 30);
    }

    public static function register_locations($manager): void {
        if (!is_object($manager)) return;
        $manager->register_all_core_location();
    }

    public static function has_elementor_location(string $location): bool {
        return function_exists('elementor_theme_do_location') && elementor_theme_do_location($location);
    }

    public static function render_header(): bool {
        if (!function_exists('elementor_theme_do_location')) return false;
        return (bool) elementor_theme_do_location('header');
    }

    public static function render_footer(): bool {
        if (!function_exists('elementor_theme_do_location')) return false;
        return (bool) elementor_theme_do_location('footer');
    }

    public static function frontend_assets(): void {
        // Elementor handles its own template assets. This hook is intentionally
        // kept as a stable extension point for Avanik-specific builder assets.
    }
}
