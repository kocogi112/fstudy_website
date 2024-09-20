

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
    $results = $wpdb->get_results($results_query); ?>
     <style>
    * {
      box-sizing: border-box;
    }

    /* Create two equal columns that floats next to each other */
    .column {
      float: left;
      width: 50%;
      padding: 10px;
    }

    /* Clear floats after the columns */
    .row:after {
      content: "";
      display: table;
      clear: both;
    }

    /* Additional styles */
    .table {
      width: 100%;
      border: 1px solid #ddd;
      border-collapse: collapse;
    }

    .table th, .table td {
      border: 1px solid #ddd;
      padding: 8px;
    }

    </style>

<?php
    if ( have_posts() ) :
        while ( have_posts() ) : the_post(); ?>
            <h1><?php the_title(); ?></h1>
            <div><?php the_content(); ?></div>



            <div class="row">
                <div class="column" style="background-color:#aaa;">
                    <h2>Do Real Test</h2>
                    <a href="doing">Start test</a>
                    <p>* By choosing this kind, you can test yourself with real accomodation (With time required)</p>
                </div>
                <div class="column" style="background-color:#bbb;">
                    <h2>Practice this test with suitable accomodation</h2>
                    <form action="doing" method="get">
                        <label style="font-size: 18px;"  for="timer"><b> Choose time for test </b></label>

                        <select id="timer" name="option">
                            <option value="1000000">Unlimited time</option>
                            <option value="60">1 minutes</option>
                            <option value="1800">30 minutes</option>
                            <option value="2700">45 minutes</option>
                            <option value="3600">60 minutes</option>
                            <option value="4500">75 minutes</option>
                            <option value="5400">90 minutes</option>
                            <option value="6300">105 minutes</option>
                            <option value="7200">120 minutes</option>
                            <option value="9000">150 minutes</option>
                            <option value="10800">180 minutes</option>
                        </select><br><br>      
                        <button type="submit" value="Start test">Start test</button>
                    </form>
                    <p>*By choosing this kind, you can change time for this test, then you can practice it with suitable level</p>
                </div>
            </div>


            
            <!--<button type="submit">Submit</button>-->

            





        <?php
        endwhile;
    endif;

    if ($results) {
                
        
        echo ' <h4>Your Test History</h4>';

        foreach ($results as $result) {
            // Display dateform of the result
            ?>
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