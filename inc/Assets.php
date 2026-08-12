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

        wp_enqueue_style(
            'avanik-style',
            get_template_directory_uri() . '/assets/css/main.css',
            [],
            $theme->get('Version')
        );

        wp_enqueue_script(
            'avanik-main',
            get_template_directory_uri() . '/assets/js/main.js',
            [],
            $theme->get('Version'),
            true
        );
    }
}

Assets::init();
