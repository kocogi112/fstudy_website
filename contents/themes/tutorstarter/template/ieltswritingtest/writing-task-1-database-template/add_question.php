<?php
// Include WordPress functions
require_once('C:\xampp\htdocs\wordpress\wp-load.php'); // Adjust the path as necessary

global $wpdb;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the input data and sanitize it
    $id_test = sanitize_text_field($_POST['id_test']);
    $task = intval($_POST['task']);
    $question_type = sanitize_textarea_field($_POST['question_type']);
    $question_content = sanitize_textarea_field($_POST['question_content']);
    $sample_writing = sanitize_textarea_field($_POST['sample_writing']);
    $important_add = sanitize_textarea_field($_POST['important_add']);
    $image_link = sanitize_textarea_field($_POST['image_link']);

    // Prepare the data for insertion
    $data = array(
        'id_test' => $id_test,
        'task' => $task,
        'question_type' => $question_type,
        'question_content' => $question_content,
        'sample_writing' => $sample_writing,
        'important_add' => $important_add,
        'image_link' => $image_link
    );

    // Insert the data into the database
    $inserted = $wpdb->insert('ielts_writing_task_1_question', $data);

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
