<?php
namespace Avanik;
defined('ABSPATH') || exit;
final class ModerationAdmin {
  public static function register(): void { add_action('admin_menu',[self::class,'menu']); }
  public static function menu(): void { add_submenu_page('avanik-providers','Product Moderation','Product Moderation','manage_options','avanik-product-moderation',[self::class,'render']); }
  public static function render(): void {
    if(!current_user_can('manage_options')) return; global $wpdb;
    $status=sanitize_key(wp_unslash($_GET['status']??'pending_review')); $allowed=[Product::PENDING_REVIEW,Product::PUBLISHED,Product::REJECTED]; if(!in_array($status,$allowed,true))$status=Product::PENDING_REVIEW;
    $rows=$wpdb->get_results($wpdb->prepare('SELECT * FROM '.ProductRepository::table_name().' WHERE status=%s ORDER BY created_at ASC',$status),ARRAY_A)?:[];
    ?>
    <div class="wrap" dir="rtl"><h1>مدیریت محصولات</h1><p><a href="<?php echo esc_url(admin_url('admin.php?page=avanik-product-moderation&status=pending_review')); ?>">در انتظار</a> | <a href="<?php echo esc_url(admin_url('admin.php?page=avanik-product-moderation&status=published')); ?>">منتشرشده</a> | <a href="<?php echo esc_url(admin_url('admin.php?page=avanik-product-moderation&status=rejected')); ?>">ردشده</a></p>
    <table class="widefat striped"><thead><tr><th>ID</th><th>عنوان</th><th>نوع</th><th>فروشنده</th><th>قیمت</th><th>دلیل رد</th><th>عملیات</th></tr></thead><tbody>
    <?php if(!$rows): ?><tr><td colspan="7">موردی وجود ندارد.</td></tr><?php endif; ?>
    <?php foreach($rows as $r): ?><tr><td><?php echo esc_html($r['id']); ?></td><td><?php echo esc_html($r['title']); ?></td><td><?php echo esc_html($r['type']); ?></td><td><?php echo esc_html($r['supplier_user_id']); ?></td><td><?php echo esc_html($r['price'].' '.$r['currency']); ?></td><td><?php echo esc_html($r['rejection_reason']??''); ?></td><td><?php if($status===Product::PENDING_REVIEW): ?><form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>"><input type="hidden" name="action" value="avanik_product_moderate"><input type="hidden" name="product_id" value="<?php echo esc_attr($r['id']); ?>"><?php wp_nonce_field('avanik_product_moderate'); ?><button class="button button-primary" name="decision" value="approve">تأیید</button><input name="rejection_reason" placeholder="دلیل رد در صورت انتخاب رد"><button class="button" name="decision" value="reject">رد</button></form><?php endif; ?></td></tr><?php endforeach; ?></tbody></table></div>
    <?php
  }
}