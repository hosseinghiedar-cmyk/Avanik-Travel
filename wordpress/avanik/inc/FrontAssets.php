<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class FrontAssets {
  public static function register(): void {
    add_action('wp_head',[self::class,'css'],25);
  }
  public static function css(): void {
    if (is_admin()) return;
    $o=class_exists(ThemeSettings::class)?ThemeSettings::get():[];
    $images=[];
    foreach(['hero_image','hero_image_2','hero_image_3'] as $key){$url=esc_url($o[$key]??'');if($url)$images[]='url("'.$url.'")';}
    if(!$images)return;
    $interval=max(3,min(20,absint($o['hero_interval']??6)));
    echo '<style>:root{--av-hero-1:'.$images[0].';--av-hero-2:'.($images[1]??$images[0]).';--av-hero-3:'.($images[2]??$images[0]).';--av-hero-interval:'.$interval.'s}</style>';
  }
}
