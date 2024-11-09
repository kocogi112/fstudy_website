<?php
// Include WordPress functions
require_once('C:\xampp\htdocs\wordpress\wp-load.php'); // Adjust the path as necessary

global $wpdb;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the input data and sanitize it
    $id_test = wp_kses_post($_POST['id_test']);
    $type_test = wp_kses_post($_POST['type_test']);
    $testname = wp_kses_post($_POST['testname']);
    $script_paragraph = wp_kses_post($_POST['script_paragraph']);
    

    // Prepare the data for insertion
    $data = array(
        'id_test' => $id_test,
        'type_test' => $type_test,
        'testname' => $testname,
        'script_paragraph' => $script_paragraph,
       
    );

    // Insert the data into the database
    $inserted = $wpdb->insert('dictation_question', $data);

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
