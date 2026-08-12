<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class PassengerForm {
    public static function render(array $product, array $passenger = []): string {
        $fields = PassengerRequirements::for_product($product);
        $labels = [
            'first_name' => 'نام',
            'last_name' => 'نام خانوادگی',
            'phone' => 'موبایل',
            'email' => 'ایمیل',
            'national_id' => 'کد ملی',
            'nationality' => 'ملیت',
            'date_of_birth' => 'تاریخ تولد',
            'passport_no' => 'شماره پاسپورت',
            'passport_expiry' => 'تاریخ انقضای پاسپورت',
        ];
        ob_start();
        ?>
        <div class="avanik-passenger-form" dir="rtl">
            <?php foreach ($fields as $field):
                $label = $labels[$field] ?? $field;
                $type = $field === 'email'
                    ? 'email'
                    : (($field === 'date_of_birth' || $field === 'passport_expiry') ? 'date' : 'text');
                $required = in_array($field, ['first_name', 'last_name', 'passport_no', 'passport_expiry'], true);
                ?>
                <p>
                    <label>
                        <?php echo esc_html($label); ?>
                        <?php if ($required): ?>
                            <span aria-hidden="true">*</span>
                        <?php endif; ?>
                        <br>
                        <input type="<?php echo esc_attr($type); ?>"
                               name="passenger[<?php echo esc_attr($field); ?>]"
                               value="<?php echo esc_attr($passenger[$field] ?? ''); ?>">
                    </label>
                </p>
            <?php endforeach; ?>
        </div>
        <?php
        return (string) ob_get_clean();
    }
}
