<?php
/** Avanik Login / Registration Form */
defined('ABSPATH') || exit;
?>
<section class="av-login-page" aria-label="ورود و ثبت نام">
  <div class="av-login-page__card">
    <div class="av-login-card__icon">♙</div>
    <h1>ورود و ثبت‌نام آوانیک</h1>
    <p>اطلاعات خود را وارد کنید تا سفرهای شما در حساب آوانیک ذخیره شود.</p>
    <form class="av-login-form" method="post" action="">
      <label><span>نام</span><input type="text" name="first_name" autocomplete="given-name" placeholder="نام خود را وارد کنید" required></label>
      <label><span>نام خانوادگی</span><input type="text" name="last_name" autocomplete="family-name" placeholder="نام خانوادگی را وارد کنید" required></label>
      <label><span>شماره موبایل</span><input type="tel" name="mobile" inputmode="tel" autocomplete="tel" placeholder="۰۹۱۲۱۲۳۴۵۶۷" required></label>
      <button class="av-btn av-btn--primary" type="submit">ادامه</button>
    </form>
    <small class="av-login-page__note">با ادامه، شرایط استفاده و حریم خصوصی آوانیک را می‌پذیرید.</small>
  </div>
</section>
