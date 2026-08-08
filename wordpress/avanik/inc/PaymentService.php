<?php
namespace Avanik;

defined('ABSPATH') || exit;

final class PaymentService {
  public static function initialize(array $data) {
    if (!is_user_logged_in()) {
      return new \WP_Error('avanik_auth_required', 'ورود کاربر برای شروع پرداخت الزامی است.');
    }

    $booking_id = sanitize_text_field($data['booking_id'] ?? '');
    $amount = (float) ($data['amount'] ?? 0);
    if ($booking_id === '' || $amount <= 0) {
      return new \WP_Error('avanik_invalid_payment', 'اطلاعات پرداخت معتبر نیست.');
    }

    $transaction_id = PaymentRepository::create($data);
    return $transaction_id ? PaymentRepository::find($transaction_id) : new \WP_Error('avanik_payment_create_failed', 'تراکنش پرداخت ایجاد نشد.');
  }

  public static function mark_paid(string $transaction_id, string $gateway_reference = '') {
    global $wpdb;
    $payment = PaymentRepository::find($transaction_id);
    if (!$payment || (int) $payment['customer_id'] !== get_current_user_id()) {
      return new \WP_Error('avanik_payment_not_found', 'تراکنش پرداخت پیدا نشد.');
    }

    $updated = $wpdb->update(
      PaymentRepository::table_name(),
      ['status' => Payment::STATUS_PAID, 'gateway_reference' => sanitize_text_field($gateway_reference), 'updated_at' => current_time('mysql')],
      ['transaction_id' => $transaction_id],
      ['%s', '%s', '%s'],
      ['%s']
    );

    return $updated !== false ? PaymentRepository::find($transaction_id) : new \WP_Error('avanik_payment_update_failed', 'وضعیت پرداخت به‌روزرسانی نشد.');
  }
}
