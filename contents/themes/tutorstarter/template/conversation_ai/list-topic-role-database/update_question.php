<?php
// Include WordPress functions
require_once('C:\xampp\htdocs\wordpress\wp-load.php'); // Adjust the path as necessary

global $wpdb;
   // Get the input data and sanitize it
   $number = wp_kses_post($_POST['number']);
   $id_test = wp_unslash($_POST['id_test']);
   $testname = wp_unslash($_POST['testname']);
   $instruction = wp_unslash($_POST['instruction']);
   $target_1 = wp_unslash($_POST['target_1']);
   $target_2 = wp_unslash($_POST['target_2']);
   $target_3 = wp_unslash($_POST['target_3']);
   $topic = wp_unslash($_POST['topic']);
   $ai_role = wp_unslash($_POST['ai_role']);
   $user_role = wp_unslash($_POST['user_role']);
   $difficulty = wp_unslash($_POST['difficulty']);
   $time_limit = wp_unslash($_POST['time_limit']);
   $sentence_limit = wp_unslash($_POST['sentence_limit']);
   $cover_image = wp_unslash($_POST['cover_image']);
   $sentence_limit = wp_unslash($_POST['sentence_limit']);
   $cover_image = wp_unslash($_POST['cover_image']);
   $token_need = wp_unslash($_POST['token_need']);
   $role_access = wp_unslash($_POST['role_access']);
   $time_allow = wp_unslash($_POST['time_allow']);


   // Prepare the data for insertion
   $data = array(
       'id_test' => $id_test,
       'testname' => $testname,
       'instruction' => $instruction,
       'target_1' => $target_1,
       'target_2' => $target_2,
       'target_3' => $target_3,
       'topic' => $topic,
       'ai_role' => $ai_role,
       'user_role' => $user_role,
       'difficulty' => $difficulty,
       'time_limit' => $time_limit,
       'sentence_limit' => $sentence_limit,
       'cover_image' => $cover_image,
       'token_need' => $token_need,
       'role_access' => $role_access,
       'time_allow' => $time_allow,


   );

// Update the record in the database
$wpdb->update('conversation_with_ai_list', $data, array('number' => $number));

// Return a response
echo json_encode(array('status' => 'success'));
?>
