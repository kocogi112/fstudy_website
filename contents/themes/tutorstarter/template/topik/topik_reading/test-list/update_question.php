<?php
// Include WordPress functions
require_once('C:\xampp\htdocs\wordpress\wp-load.php'); // Adjust the path as necessary

global $wpdb;

// Get the data from the POST request
    $number = wp_kses_post($_POST['number']);
    $id_test = wp_kses_post($_POST['id_test']);
    $testname = wp_kses_post($_POST['testname']);
    $testcode = wp_kses_post($_POST['testcode']);
    $correct_answer = wp_kses_post($_POST['correct_answer']);
    $permissive_management = wp_kses_post($_POST['permissive_management']);
    $token_need = wp_unslash($_POST['token_need']);
    $role_access = wp_unslash($_POST['role_access']);
    $time_allow = wp_unslash($_POST['time_allow']);
    $test_type = wp_kses_post($_POST['test_type']);
    $time = wp_kses_post($_POST['time']);



// Prepare the data for updating
$data = array(
        'id_test' => $id_test,
        'testname' => $testname,
        'testcode' => $testcode,
        'correct_answer' => $correct_answer,
        'permissive_management' => $permissive_management,
        'test_type' => $test_type,
        'time' => $time,

        'token_need' => $token_need,
        'role_access' => $role_access,
        'time_allow' => $time_allow,
);

// Update the record in the database
$wpdb->update('topik_reading_test_list', $data, array('number' => $number));

// Return a response
echo json_encode(array('status' => 'success'));
?>
