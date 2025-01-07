<?php
// Include WordPress functions
require_once('C:\xampp\htdocs\wordpress\wp-load.php'); // Adjust the path as necessary

global $wpdb;
   // Get the input data and sanitize it
   $number = wp_kses_post($_POST['number']);
   $id_test = wp_kses_post($_POST['id_test']);
   $context_name = wp_kses_post($_POST['context_name']);
   $instruction = wp_kses_post($_POST['instruction']);
   $target_1 = wp_kses_post($_POST['target_1']);
   $target_2 = wp_kses_post($_POST['target_2']);
   $target_3 = wp_kses_post($_POST['target_3']);
   $topic = wp_kses_post($_POST['topic']);
   $difficulty = wp_kses_post($_POST['difficulty']);
   $time_limit = wp_kses_post($_POST['time_limit']);
   $sentence_limit = wp_kses_post($_POST['sentence_limit']);
   $cover_image = wp_kses_post($_POST['cover_image']);


   // Prepare the data for insertion
   $data = array(
       'id_test' => $id_test,
       'context_name' => $context_name,
       'instruction' => $instruction,
       'target_1' => $target_1,
       'target_2' => $target_2,
       'target_3' => $target_3,
       'topic' => $topic,
       'difficulty' => $difficulty,
       'time_limit' => $time_limit,
       'sentence_limit' => $sentence_limit,
       'cover_image' => $cover_image,
       

   );

// Update the record in the database
$wpdb->update('conversation_with_ai_list', $data, array('number' => $number));

// Return a response
echo json_encode(array('status' => 'success'));
?>
