

<?php
get_header(); // Gọi phần đầu trang (header.php)
$post_id = get_the_ID();
$user_id = get_current_user_id();
// Get the custom number field value
$custom_number = get_post_meta($post_id, '_testsonlinesystem_custom_number', true);

/*
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['doing_text'])) {
    $textarea_content = sanitize_textarea_field($_POST['doing_text']);
    update_user_meta($user_id, "ieltswritingtests_{$post_id}_textarea", $textarea_content);

    wp_safe_redirect(get_permalink($post_id) . 'result/');
    exit;
}*/








if (is_user_logged_in()) {
    global $wpdb;

    // Get current user's username
    $current_user = wp_get_current_user();
    $current_username = $current_user->user_login;

    // Get results for the current user and specific idtest (custom_number)
    $results_query = $wpdb->prepare("
        SELECT * FROM wp_contact_us 
        WHERE username = %s 
        AND idtest = %d
        ORDER BY dateform DESC",
        $current_username,
        $custom_number
    );
    $results = $wpdb->get_results($results_query);
    if ( have_posts() ) :
        while ( have_posts() ) : the_post(); ?>
            <h1><?php the_title(); ?></h1>
            <div><?php the_content(); ?></div>



            <a href="doing">Start test</a>
            <!--<button type="submit">Submit</button>-->





        <?php
        endwhile;
    endif;

    if ($results) {
                
        


        foreach ($results as $result) {
            // Display dateform of the result
            ?>
            <h4>Test History</h4>
            <div class="test-result">
                <p style = "font-style:bold" > <?php echo esc_html($result->dateform); ?></p>
                
                <!-- Display test information -->
                <table class="table table-striped" style="border: 1px solid #ddd; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th>Test Name</th>
                            <th>Result</th>
                            <th>Time of Test</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo esc_html($result->testname); ?></td>
                            <td><?php echo esc_html($result->resulttest); ?></td>
                            <td><?php echo esc_html($result->timedotest); ?></td>
                        </tr>
                    </tbody>
                </table>
               
            </div>
            <?php
        }
    } else {
        echo `<p>You haven't done this test yet.</p>`;
    }
} else {
    echo '<p>Please log in to start the test.</p>';
}


get_footer(); // Gọi phần cuối trang (footer.php)
?>