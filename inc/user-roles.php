<?php
/**
 * Avanik Sprint 015 User Roles
 */

if (!defined('ABSPATH')) {
    exit;
}

function avanik_register_roles() {
    add_role(
        'avanik_customer',
        'Avanik Customer',
        array(
            'read' => true
        )
    );
}
add_action('init', 'avanik_register_roles');
