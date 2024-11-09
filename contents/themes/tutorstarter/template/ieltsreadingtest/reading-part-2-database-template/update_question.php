<?php
// Include WordPress functions
require_once('C:\xampp\htdocs\wordpress\wp-load.php'); // Adjust the path as necessary

global $wpdb;

// Get the data from the POST request
$number = wp_kses_post($_POST['number']);
$id_test = wp_kses_post($_POST['id_test']);
$part = wp_kses_post($_POST['part']);
$duration = wp_kses_post($_POST['duration']);
$number_question_of_this_part = wp_kses_post($_POST['number_question_of_this_part']);
$paragraph = wp_kses_post($_POST['paragraph']);
//$group_question = wp_kses_post($_POST['group_question']);
$category = wp_kses_post($_POST['category']);

// Prepare the data for updating
$data = array(
    'id_test' => $id_test,
    'part' => $part,
    'duration' => $duration,
    'number_question_of_this_part' => $number_question_of_this_part,
    'paragraph' => $paragraph,
   // 'group_question' => $group_question,
    'category' => $category,
);

// Update the record in the database
$wpdb->update('ielts_reading_part_2_question', $data, array('number' => $number));

// Return a response
echo json_encode(array('status' => 'success'));
?>
