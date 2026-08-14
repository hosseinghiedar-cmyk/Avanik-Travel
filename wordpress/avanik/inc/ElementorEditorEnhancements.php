<?php
namespace Avanik;
if (!defined('ABSPATH')) exit;

/** Small Elementor editor enhancements kept separate from booking logic. */
final class ElementorEditorEnhancements {
    public static function boot(): void {
        add_action('elementor/elements/categories_registered', [self::class, 'register_category']);
    }

    public static function register_category($elements_manager): void {
        if (!is_object($elements_manager)) return;
        $elements_manager->add_category('avanik', [
            'title' => 'آوانیک',
            'icon'  => 'fa fa-plane',
        ]);
    }
}
