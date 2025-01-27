<?php
// Include WordPress functions
require_once('C:\xampp\htdocs\wordpress\wp-load.php'); // Adjust the path as necessary

global $wpdb;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the input data and sanitize it
    $id_test = wp_kses_post($_POST['id_test']);
    $type_test = wp_kses_post($_POST['type_test']);
    $testname = wp_kses_post($_POST['testname']);
    $id_video = wp_kses_post($_POST['id_video']);
    $transcript = wp_unslash($_POST['transcript']);


    // Prepare the data for insertion
    $data = array(
        'id_test' => $id_test,
        'type_test' => $type_test,
        'testname' => $testname,
        'id_video' => $id_video,
        'transcript' => $transcript,

    );

    // Insert the data into the database
    $inserted = $wpdb->insert('shadowing_question', $data);

    if ($inserted) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $wpdb->last_error; // Fetch last error if any
    }

    // Redirect back to the main page
    wp_redirect('index.php');
    exit; // Always call exit after redirecting
}
?>
