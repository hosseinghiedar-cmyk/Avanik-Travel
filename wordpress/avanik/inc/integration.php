<?php
/**
 * Avanik Travel — Sprint 013 Integration Helpers
 *
 * Additive helper functions only.
 * Do not include this file automatically until the existing
 * functions.php integration has been reviewed.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Return a safe permalink for an Avanik page.
 *
 * @param string $slug WordPress page slug.
 * @return string
 */
function avanik_page_url( $slug ) {
    $page = get_page_by_path( sanitize_title( $slug ) );

    if ( $page instanceof WP_Post ) {
        return get_permalink( $page );
    }

    return home_url( '/' . trim( sanitize_title( $slug ), '/' ) . '/' );
}

/**
 * Check whether the current request is an Avanik page.
 *
 * @param string $slug WordPress page slug.
 * @return bool
 */
function avanik_is_page( $slug ) {
    return is_page( sanitize_title( $slug ) );
}
