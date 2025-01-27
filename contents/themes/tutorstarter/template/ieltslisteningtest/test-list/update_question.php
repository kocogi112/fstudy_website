<?php
// Include WordPress functions
require_once('C:\xampp\htdocs\wordpress\wp-load.php'); // Adjust the path as necessary

global $wpdb;

// Get the data from the POST request
$number = wp_kses_post($_POST['number']);
$id_test = wp_kses_post($_POST['id_test']);
$testname = wp_kses_post($_POST['testname']);
$test_type = wp_kses_post($_POST['test_type']);
$question_choose = wp_kses_post($_POST['question_choose']);
$tag = wp_kses_post($_POST['tag']);
$book = wp_kses_post($_POST['book']);



// Prepare the data for updating
$data = array(
    'id_test' => $id_test,
    'testname' => $testname,
    'test_type' => $test_type,
    'question_choose' => $question_choose,
    'tag' => $tag,
  'book' => $book,
);

// Update the record in the database
$wpdb->update('ielts_listening_test_list', $data, array('number' => $number));

// Return a response
echo json_encode(array('status' => 'success'));
?>
