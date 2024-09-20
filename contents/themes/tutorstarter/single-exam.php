

<?php
get_header(); // Gọi phần đầu trang (header.php)
$post_id = get_the_ID();
$user_id = get_current_user_id();
// Get the custom number field value
$custom_number = get_post_meta($post_id, '_exam_custom_number', true);

/*
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['doing_text'])) {
    $textarea_content = sanitize_textarea_field($_POST['doing_text']);
    update_user_meta($user_id, "ieltswritingtests_{$post_id}_textarea", $textarea_content);

    wp_safe_redirect(get_permalink($post_id) . 'result/');
    exit;
}*/




if ( have_posts() ) :
    while ( have_posts() ) : the_post(); ?>
        <h1><?php the_title(); ?></h1>
        <div><?php the_content(); ?></div>



        <a href="start">Start do test</a>
        <!--<button type="submit">Submit</button>-->





    <?php
    endwhile;
endif;

get_footer(); // Gọi phần cuối trang (footer.php)
?>