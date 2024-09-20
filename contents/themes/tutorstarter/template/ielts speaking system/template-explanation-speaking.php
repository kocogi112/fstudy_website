<?php
/*
 * Template Name: Result Template Speaking 
 * Template Post Type: ieltsspeakingtests
 */

get_header();
$post_id = get_the_ID();

// Get the custom number field value
$custom_number = get_post_meta($post_id, '_ieltsspeakingtests_custom_number', true);

echo "<script>console.log('Custom Number doing template: " . esc_js($custom_number) . "');</script>";

if (is_user_logged_in()) {
   echo '<h3>Samle Speaking</h3>';
} else {
    echo '<p>Please log in to view your test results.</p>';
}

get_footer();