<?php
defined('ABSPATH') || exit;
?>

<form class="av-booking-form" method="post">
  <div class="av-booking-form__field">
    <label for="av-booking-origin">مبدأ</label>
    <input id="av-booking-origin" name="origin" type="text" autocomplete="off">
  </div>
  <div class="av-booking-form__field">
    <label for="av-booking-destination">مقصد</label>
    <input id="av-booking-destination" name="destination" type="text" autocomplete="off">
  </div>
  <div class="av-booking-form__field">
    <label for="av-booking-date">تاریخ سفر</label>
    <input id="av-booking-date" name="travel_date" type="date">
  </div>
  <button class="av-btn av-btn--primary" type="submit">ادامه رزرو</button>
</form>
