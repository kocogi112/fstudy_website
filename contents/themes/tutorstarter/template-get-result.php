<?php
/*
 * Template Name: Result Test Version 2 Template
 * Template Post Type: exam
 */

get_header();
$post_id = get_the_ID();

// Get the custom number field value
$custom_number = get_post_meta($post_id, '_exam_custom_number', true);

echo "<script>console.log('Custom Number doing template: " . esc_js($custom_number) . "');</script>";

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

    if ($results) {
        foreach ($results as $result) {
            // Display dateform of the result
            ?>
            <div class="test-result">
                <h3><?php echo esc_html($result->dateform); ?></h3>
                
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

                <!-- Display correct answers and user answers -->
                <table class="table table-striped" style="border: 1px solid #ddd; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th>Correct Answers</th>
                            <th>Your Answers</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo esc_html($result->correctanswer); ?></td>
                            <td><?php echo esc_html($result->useranswer); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <?php
        }
    } else {
        echo '<p>You haven\'t done this test yet.</p>';
    }
} else {
    echo '<p>Please log in to view your test results.</p>';
}

get_footer();