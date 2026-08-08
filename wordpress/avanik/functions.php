<?php
defined('ABSPATH') || exit;
require_once __DIR__ . '/inc/Navigation.php';
require_once __DIR__ . '/inc/Theme.php';
require_once __DIR__ . '/inc/Booking.php';
require_once __DIR__ . '/inc/BookingMeta.php';
require_once __DIR__ . '/inc/BookingTemplate.php';
require_once __DIR__ . '/inc/BookingRepository.php';
require_once __DIR__ . '/inc/BookingSchema.php';
require_once __DIR__ . '/inc/BookingLifecycle.php';
\Avanik\BookingLifecycle::register();
\Avanik\Theme::boot();
