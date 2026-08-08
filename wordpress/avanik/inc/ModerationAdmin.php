<?php
namespace Avanik;

defined('ABSPATH') || exit;

final class ModerationAdmin {
  public static function register(): void {
    add_action('admin_menu', [self::class, 'menu']);
    add_action('admin_post_avanik_product_moderate', [self::class, 'moderate']);
  }

  public static function menu(): void {
    add_submenu_page('avanik-providers', 'Product Moderation', 'Product Moderation', 'manage_options', 'avanik-product-moderation', [self::class, 'render']);
  }

  public static function render(): void {
    if (!current_user_can('manage_options')) return;
    global $wpdb;
    $rows = $wpdb->get_results("SELECT * FROM " . ProductRepository::table_name() . " WHERE status = 'pending_review' ORDER BY created_at ASC", ARRAY_A) ?: [];
    ?>
    <div class="wrap" dir="rtl"><h1>تأیید محصولات</h1>
    <table class="widefat striped"><thead><tr><th>ID</th><th>عنوان</th><th>نوع</th><th>فروشنده</th><th>قیمت</th><th>عملیات</th></tr></thead><tbody>
    <?php if (!$rows): ?><tr><td colspan="6">محصولی در انتظار بررسی نیست.</td></tr><?php endif; ?>
    <?php foreach ($rows as $row): ?><tr>
      <td><?php echo esc_html($row['id']); ?></td><td><?php echo esc_html($row['title']); ?></td><td><?php echo esc_html($row['type']); ?></td><td><?php echo esc_html($row['supplier_user_id']); ?></td><td><?php echo esc_html($row['price'] . ' ' . $row['currency']); ?></td>
      <td>
        <form style="display:inline" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>"><input type="hidden" name="action" value="avanik_product_moderate"><input type="hidden" name="product_id" value="<?php echo esc_attr($row['id']); ?>"><input type="hidden" name="decision" value="approve"><?php wp_nonce_field('avanik_product_moderate'); ?><button class="button button-primary">تأیید</button></form>
        <form style="display:inline" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>"><input type="hidden" name="action" value="avanik_product_moderate"><input type="hidden" name="product_id" value="<?php echo esc_attr($row['id']); ?>"><input type="hidden" name="decision" value="reject"><?php wp_nonce_field('avanik_product_moderate'); ?><button class="button">رد</button></form>
      </td>
    </tr><?php endforeach; ?></tbody></table></div>
    <?php
  }

  public static function moderate(): void {
    if (!current_user_can('manage_options') || !check_admin_referer('avanik_product_moderate')) wp_die('Unauthorized');
    $id = (int) ($_POST['product_id'] ?? 0);
    $decision = sanitize_key(wp_unslash($_POST['decision'] ?? ''));
    if ($decision === 'approve') ProductModeration::approve($id);
    if ($decision === 'reject') ProductModeration::reject($id);
    wp_safe_redirect(admin_url('admin.php?page=avanik-product-moderation'));
    exit;
  }
}
