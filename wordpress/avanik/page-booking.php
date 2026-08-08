<?php
/**
 * Template Name: Avanik Booking
 */
defined('ABSPATH') || exit;
get_header();
?>

<main class="av-booking-page" dir="rtl">
  <div class="av-container">
    <header class="av-booking-page__header">
      <span>رزرو آوانیک</span>
      <h1>تکمیل رزرو</h1>
      <p>اطلاعات سفر خود را وارد کنید تا فرآیند رزرو ادامه پیدا کند.</p>
    </header>

    <?php get_template_part('template-parts/booking-form'); ?>
    <?php get_template_part('template-parts/booking-summary'); ?>
  </div>
</main>

<?php get_footer(); ?>
