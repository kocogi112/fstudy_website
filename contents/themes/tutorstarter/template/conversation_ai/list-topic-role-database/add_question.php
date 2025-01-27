<?php
// Include WordPress functions
require_once('C:\xampp\htdocs\wordpress\wp-load.php'); // Adjust the path as necessary

global $wpdb;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the input data and sanitize it
    $id_test = wp_unslash($_POST['id_test']);
    $testname = wp_kses_post($_POST['testname']);
    $instruction = wp_kses_post($_POST['instruction']);
    $target_1 = wp_kses_post($_POST['target_1']);
    $target_2 = wp_kses_post($_POST['target_2']);
    $target_3 = wp_kses_post($_POST['target_3']);
    $topic = wp_kses_post($_POST['topic']);
    $ai_role = wp_kses_post($_POST['ai_role']);
    $user_role = wp_kses_post($_POST['user_role']);
    $difficulty = wp_unslash($_POST['difficulty']);
    $time_limit = wp_unslash($_POST['time_limit']);
    $sentence_limit = wp_unslash($_POST['sentence_limit']);
    $cover_image = wp_unslash($_POST['cover_image']);


    // Prepare the data for insertion
    $data = array(
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
        'id_test' => $id_test,

    );

    // Insert the data into the database
    $inserted = $wpdb->insert('conversation_with_ai_list', $data);

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
