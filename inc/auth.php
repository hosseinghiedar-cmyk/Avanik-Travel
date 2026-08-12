<?php
/**
 * Avanik Sprint 015 Authentication Foundation
 */

if (!defined('ABSPATH')) {
    exit;
}

function avanik_is_customer_logged_in() {
    return is_user_logged_in();
}
