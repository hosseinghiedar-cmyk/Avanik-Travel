<?php

namespace Avanik;

defined('ABSPATH') || exit;

function asset_url(string $path = ''): string
{
    return get_template_directory_uri() . '/assets/' . ltrim($path, '/');
}
