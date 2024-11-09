<?php
require_once('C:\xampp\htdocs\wordpress\wp-load.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    global $wpdb;

    $number = intval($_POST['number']);
    $result = $wpdb->get_row($wpdb->prepare("SELECT * FROM digital_sat_question_bank_verbal WHERE number = %d", $number));

    if ($result) {
        // Return JSON response
        echo json_encode($result);
    } else {
        // Handle error case
        echo json_encode(array('error' => 'Record not found.'));
    }
} else {
    echo json_encode(array('error' => 'Invalid request.'));
}
?>
