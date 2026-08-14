<?php

namespace Avanik;

defined('ABSPATH') || exit;

class Assets
{
    public static function init(): void
    {
        add_action('wp_enqueue_scripts', [self::class, 'enqueue']);
    }

    public static function enqueue(): void
    {
        $theme = wp_get_theme();
        $version = $theme->get('Version');

        wp_enqueue_style('avanik-style', get_template_directory_uri() . '/assets/css/main.css', [], $version);
        wp_enqueue_script('avanik-main', get_template_directory_uri() . '/assets/js/main.js', [], $version, true);

        if (is_front_page()) {
            wp_enqueue_style('avanik-home-ui', get_template_directory_uri() . '/assets/css/home-ui.css', ['avanik-style'], $version);
            wp_enqueue_script('avanik-home-ui', get_template_directory_uri() . '/assets/js/home-ui.js', [], $version, true);
        }
    }
}

Assets::init();
