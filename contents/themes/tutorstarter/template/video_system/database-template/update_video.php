<?php
// Include WordPress functions
require_once('C:\xampp\htdocs\wordpress\wp-load.php'); // Adjust the path as necessary

global $wpdb;

// Get the data from the POST request
$number = wp_kses_post($_POST['number']);
$id_video_orginal = wp_kses_post($_POST['id_video_orginal']);
$type_host = wp_kses_post($_POST['type_host']);
$converted_video_id = wp_kses_post($_POST['converted_video_id']);
$note = wp_kses_post($_POST['note']);
$guide_directory = wp_kses_post($_POST['guide_directory']);
$status_link = wp_kses_post($_POST['status_link']);


// Prepare the data for updating
$data = array(
    'id_video_orginal' => $id_video_orginal,
    'type_host' => $type_host,
    'converted_video_id' => $converted_video_id,
    'note' => $note,
    'guide_directory' => $guide_directory,
    'status_link' => $status_link,

);

// Update the record in the database
$wpdb->update('video_link_generator', $data, array('number' => $number));

// Return a response
echo json_encode(array('status' => 'success'));
?>