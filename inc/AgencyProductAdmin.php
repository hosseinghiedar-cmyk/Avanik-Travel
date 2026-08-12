<?php
namespace Avanik;

defined('ABSPATH') || exit;

final class AgencyProductAdmin {
  public static function register(): void {
    add_shortcode('avanik_agency_product_manager', [self::class, 'render']);
    add_action('admin_post_avanik_product_save', [self::class, 'save']);
    add_action('admin_post_avanik_product_submit', [self::class, 'submit']);
  }

  public static function render(): string {
    if (!is_user_logged_in()) return '<p>لطفاً ابتدا وارد حساب شوید.</p>';
    $user = wp_get_current_user();
    if (!AgencyOnboarding::can_sell($user->ID)) return '<p>حساب شما هنوز برای فروش تأیید نشده است.</p>';
    ob_start(); ?>
    <section class="avanik-agency-product-manager" dir="rtl">
      <h2>مدیریت محصولات</h2>
      <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
        <input type="hidden" name="action" value="avanik_product_save">
        <?php wp_nonce_field('avanik_product_save'); ?>
        <p><label>نوع محصول<br><select name="type"><option value="tour">تور</option><option value="hotel">هتل</option><option value="flight">پرواز</option><option value="package">پکیج</option></select></label></p>
        <p><label>عنوان<br><input required type="text" name="title" maxlength="255"></label></p>
        <p><label>قیمت<br><input required type="number" min="0" step="0.01" name="price"></label></p>
        <p><label>ارز<br><input type="text" name="currency" value="IRR" maxlength="10"></label></p>
        <p><label>ظرفیت<br><input type="number" min="0" name="capacity" value="0"></label></p>
        <p><label>اطلاعات تکمیلی<br><textarea name="metadata" rows="5"></textarea></label></p>
        <button type="submit">ذخیره پیش‌نویس</button>
      </form>
    </section>
    <?php return (string) ob_get_clean();
  }

  public static function save(): void {
    if (!is_user_logged_in() || !check_admin_referer('avanik_product_save')) wp_die('Unauthorized');
    $user = wp_get_current_user();
    if (!AgencyOnboarding::can_sell($user->ID)) wp_die('Seller account is not approved.');
    $metadata = sanitize_textarea_field(wp_unslash($_POST['metadata'] ?? ''));
    $id = ProductRepository::create([
      'supplier_user_id' => $user->ID,
      'type' => sanitize_key(wp_unslash($_POST['type'] ?? Product::TOUR)),
      'title' => sanitize_text_field(wp_unslash($_POST['title'] ?? '')),
      'price' => (float) ($_POST['price'] ?? 0),
      'currency' => sanitize_text_field(wp_unslash($_POST['currency'] ?? 'IRR')),
      'capacity' => max(0, (int) ($_POST['capacity'] ?? 0)),
      'metadata' => ['notes' => $metadata],
    ]);
    wp_safe_redirect(add_query_arg(['product_saved' => $id], wp_get_referer() ?: home_url('/')));
    exit;
  }

  public static function submit(): void {
    if (!is_user_logged_in() || !check_admin_referer('avanik_product_submit')) wp_die('Unauthorized');
    $user = wp_get_current_user();
    if (!AgencyOnboarding::can_sell($user->ID)) wp_die('Seller account is not approved.');
    ProductRepository::submit_for_review((int) ($_POST['product_id'] ?? 0), $user->ID);
    wp_safe_redirect(wp_get_referer() ?: home_url('/'));
    exit;
  }
}
