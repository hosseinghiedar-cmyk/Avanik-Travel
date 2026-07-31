<?php

namespace Avanik;

defined('ABSPATH') || exit;

class Theme
{
    public static function boot(): void
    {
        add_action('after_setup_theme', [self::class, 'setup']);
    }

    public static function setup(): void
    {
        load_theme_textdomain('avanik', get_template_directory() . '/languages');

        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');
        add_theme_support('custom-logo');
        add_theme_support('menus');
        add_theme_support('html5');
        add_theme_support('responsive-embeds');

        register_nav_menus([
            'primary' => __('Primary Menu', 'avanik'),
            'footer'  => __('Footer Menu', 'avanik'),
        ]);
    }
}
