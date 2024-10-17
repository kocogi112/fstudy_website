<?php
// Include WordPress functions
require_once('C:\xampp\htdocs\wordpress\wp-load.php'); // Adjust the path as necessary

global $wpdb;

// Get the data from the POST request
$number = sanitize_textarea_field($_POST['number']);
$id_test = sanitize_text_field($_POST['id_test']);
$part = sanitize_text_field($_POST['part']);
$duration = intval($_POST['duration']);
$number_question_of_this_part = sanitize_textarea_field($_POST['number_question_of_this_part']);
$paragraph = sanitize_textarea_field($_POST['paragraph']);
$group_question = sanitize_textarea_field($_POST['group_question']);
$category = sanitize_textarea_field($_POST['category']);

// Prepare the data for updating
$data = array(
    'id_test' => $id_test,
    'part' => $part,
    'duration' => $duration,
    'number_question_of_this_part' => $number_question_of_this_part,
    'paragraph' => $paragraph,
    'group_question' => $group_question,
    'category' => $category,
);

// Update the record in the database
$wpdb->update('ielts_reading_part_1_question', $data, array('number' => $number));

// Return a response
echo json_encode(array('status' => 'success'));
?>
