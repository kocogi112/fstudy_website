<?php
// Include WordPress functions
require_once('C:\xampp\htdocs\wordpress\wp-load.php'); // Adjust the path as necessary

global $wpdb;

// Get the data from the POST request
$number = wp_kses_post($_POST['number']);

$id_question = wp_kses_post($_POST['id_question']);

$type_question = wp_kses_post($_POST['type_question']);

$question_content = stripslashes($_POST['question_content']);

$answer_1 = wp_kses_post($_POST['answer_1']);

$answer_2 = wp_kses_post($_POST['answer_2']);
$answer_3 = wp_kses_post($_POST['answer_3']);
$answer_4 = wp_kses_post($_POST['answer_4']);
if ($_POST['type_question'] === 'completion') {
    $correct_answer = $_POST['custom_correct_answer'];
} else {
    $correct_answer = $_POST['correct_answer'];
}

$explanation = wp_kses_post($_POST['explanation']);
$image_link = wp_kses_post($_POST['image_link']);

// Prepare the data for updating
$data = array(
    'id_question' => $id_question,
    'type_question' => $type_question,
    'question_content' => $question_content,
    'answer_1' => $answer_1,
    'answer_2' => $answer_2,
    'answer_3' => $answer_3,
    'answer_4' => $answer_4,
    'correct_answer' => $correct_answer,
    'explanation' => $explanation,
    'image_link' => $image_link,
);

// Update the record in the database
$wpdb->update('digital_sat_question_bank_math', $data, array('number' => $number));

// Return a response
echo json_encode(array('status' => 'success'));




?>