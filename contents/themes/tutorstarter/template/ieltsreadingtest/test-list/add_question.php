<?php
// Include WordPress functions
require_once('C:\xampp\htdocs\wordpress\wp-load.php'); // Adjust the path as necessary

global $wpdb;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get and sanitize input data
    $id_test = wp_kses_post($_POST['id_test']);
    $testname = wp_kses_post($_POST['testname']);
    $test_type = wp_kses_post($_POST['test_type']);
    $question_choose = wp_kses_post($_POST['question_choose']);
    $tag = wp_kses_post($_POST['tag']);
    $book = wp_kses_post($_POST['book']);


    // Prepare data for insertion
    $data = array(
        'id_test' => $id_test,
        'testname' => $testname,
        'test_type' => $test_type,
        'question_choose' => $question_choose,
        'tag' => $tag,
        'book' => $book,
    );

    // Insert the data into the database
    $inserted = $wpdb->insert('ielts_reading_test_list', $data);

    if ($inserted) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $wpdb->last_error;
    }

    // Redirect back to the main page
    wp_redirect('index.php');
    exit;
}
?>