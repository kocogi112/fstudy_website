<?php
// Include WordPress functions
require_once('C:\xampp\htdocs\wordpress\wp-load.php'); // Adjust the path as necessary

global $wpdb;

// Get the data from the POST request
$number = wp_kses_post($_POST['number']);
$id_test = wp_kses_post($_POST['id_test']);
$subject = wp_kses_post($_POST['subject']);
$year = wp_kses_post($_POST['year']);
$testname = wp_kses_post($_POST['testname']);
$link_file = wp_kses_post($_POST['link_file']);
$time = wp_kses_post($_POST['time']);
$number_question = wp_kses_post($_POST['number_question']);

$testcode = wp_unslash(wp_kses_post($_POST['testcode']));

// Prepare the data for updating
$data = array(
    'id_test' => $id_test,
    'subject' => $subject,
    'year' => $year,
    'testname' => $testname,
    'link_file' => $link_file,

    'time' => $time,
    'number_question' => $number_question,
    'testcode' => $testcode,
);

// Update the record in the database
$wpdb->update('thptqg_question', $data, array('number' => $number));

// Return a response
echo json_encode(array('status' => 'success'));
?>
