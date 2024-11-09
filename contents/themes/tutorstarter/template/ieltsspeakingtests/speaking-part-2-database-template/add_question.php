<?php
// Include WordPress functions
require_once('C:\xampp\htdocs\wordpress\wp-load.php'); // Adjust the path as necessary

global $wpdb;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the input data and sanitize it
    $id_test = wp_kses_post($_POST['id_test']);
    $topic = wp_kses_post($_POST['topic']);
    $question_content = wp_kses_post($_POST['question_content']);
    $sample = wp_kses_post($_POST['sample']);
    $important_add = wp_kses_post($_POST['important_add']);
    $speaking_part = wp_kses_post($_POST['speaking_part']);

    // Prepare the data for insertion
    $data = array(
        'id_test' => $id_test,
        'topic' => $topic,
        'question_content' => $question_content,
        'sample' => $sample,
        'important_add' => $important_add,
        'speaking_part' => $speaking_part
    );

    // Insert the data into the database
    $inserted = $wpdb->insert('ielts_speaking_part_2_question', $data);

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
