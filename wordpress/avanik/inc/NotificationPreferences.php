<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class NotificationPreferences {
 private const OPT='avanik_notification_preferences';
 public static function register(): void { add_shortcode('avanik_notification_preferences',[self::class,'shortcode']); add_action('admin_post_avanik_save_notification_preferences',[self::class,'save']); add_filter('avanik_notification_recipients',[self::class,'recipients'],10,3); }
 private static function defaults(): array { return ['email'=>true,'sms'=>false,'whatsapp'=>false,'internal'=>true]; }
 public static function get(int $user_id): array { return wp_parse_args((array)get_user_meta($user_id,self::OPT,true),self::defaults()); }
 public static function enabled(int $user_id,string $channel): bool { $p=self::get($user_id); return !empty($p[$channel]); }
 public static function recipients(string $event,array $payload,array $context=[]): array { $out=[]; foreach(['customer_id'=>'customer','agency_id'=>'agency','admin_id'=>'admin'] as $key=>$role){ $uid=absint($payload[$key]??$context[$key]??0); if($uid && self::role_allowed($event,$role))$out[$role]=['user_id'=>$uid,'channels'=>self::get($uid)]; } return $out; }
 private static function role_allowed(string $event,string $role): bool { return (bool)apply_filters('avanik_notification_role_allowed',true,$event,$role); }
 public static function shortcode(): string { if(!is_user_logged_in())return '<p>Please log in.</p>'; $p=self::get(get_current_user_id()); ob_start(); echo '<form method="post" action="'.esc_url(admin_url('admin-post.php')).'"><input type="hidden" name="action" value="avanik_save_notification_preferences">'.wp_nonce_field('avanik_save_notification_preferences','_wpnonce',true,false).'<h3>Notification Preferences</h3>'; foreach($p as $channel=>$enabled){echo '<p><label><input type="checkbox" name="p['.esc_attr($channel).']" value="1" '.checked($enabled,true,false).'> '.esc_html(ucfirst($channel)).'</label></p>';} echo '<button class="button">Save</button></form>'; return ob_get_clean(); }
 public static function save(): void { if(!is_user_logged_in())wp_die('Forbidden',403); check_admin_referer('avanik_save_notification_preferences'); $base=self::defaults(); $in=(array)($_POST['p']??[]); foreach($base as $k=>$_)$base[$k]=!empty($in[$k]); update_user_meta(get_current_user_id(),self::OPT,$base); wp_safe_redirect(wp_get_referer()?:home_url('/')); exit; }
}