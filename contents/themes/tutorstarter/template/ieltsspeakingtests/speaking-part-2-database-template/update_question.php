<?php
// Include WordPress functions
require_once('C:\xampp\htdocs\wordpress\wp-load.php'); // Adjust the path as necessary

global $wpdb;

// Get the data from the POST request
$number = intval($_POST['number']);
$id_test = sanitize_text_field($_POST['id_test']);
$topic = sanitize_text_field($_POST['topic']);

// Escape content to retain line breaks
$question_content = wp_kses_post($_POST['question_content']);
$sample = wp_kses_post($_POST['sample']);
$important_add = wp_kses_post($_POST['important_add']);
$speaking_part = intval($_POST['speaking_part']);

// Prepare the data for updating
$data = array(
    'id_test' => $id_test,
    'topic' => $topic,
    'question_content' => $question_content,
    'sample' => $sample,
    'important_add' => $important_add,
    'speaking_part' => $speaking_part,
);

// Update the record in the database
$wpdb->update('ielts_speaking_part_2_question', $data, array('number' => $number));

// Return a response
echo json_encode(array('status' => 'success'));
?>
