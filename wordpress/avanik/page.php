<?php
/**
 * Standard page template for Avanik Travel.
 * Elementor requires the_content() to be present in the active page template.
 */
if (!defined('ABSPATH')) {
    exit;
}
get_header();
?>
<main id="primary" class="site-main avanik-page">
    <div class="avanik-shell avanik-page-content">
        <?php
        while (have_posts()) :
            the_post();
            the_content();
        endwhile;
        ?>
    </div>
</main>
<?php get_footer(); ?>
