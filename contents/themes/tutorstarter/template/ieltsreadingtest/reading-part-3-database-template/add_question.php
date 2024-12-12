<?php
// Include WordPress functions
require_once('C:\xampp\htdocs\wordpress\wp-load.php'); // Adjust the path as necessary

global $wpdb;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the input data and sanitize it
    $id_test = wp_kses_post($_POST['id_test']);
    $part = wp_kses_post($_POST['part']);
    $duration = wp_kses_post($_POST['duration']);
    $number_question_of_this_part = wp_kses_post($_POST['number_question_of_this_part']);
    $paragraph = wp_kses_post($_POST['paragraph']);
    $group_question = wp_unslash(wp_kses_post($_POST['group_question']));
    $category = wp_kses_post($_POST['category']);

    // Prepare the data for insertion
    $data = array(
        'id_test' => $id_test,
        'part' => $part,
        'duration' => $duration,
        'number_question_of_this_part' => $number_question_of_this_part,
        'paragraph' => $paragraph,
        'group_question' => $group_question,
        'category' => $category
    );

    // Insert the data into the database
    $inserted = $wpdb->insert('ielts_reading_part_3_question', $data);

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
