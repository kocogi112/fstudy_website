<?php
/*
 * Template Name: Gift Receive Template
 * Template Post Type: gift receive page
 */

get_header(); // Gọi phần đầu trang (header.php)
$user_id = get_current_user_id();
$current_username = wp_get_current_user()->user_login;

global $wpdb;

// Fetch user token from the user_token table
$sql = "SELECT token, updated_time FROM user_token WHERE username = %s";
$user_token_result = $wpdb->get_row($wpdb->prepare($sql, $current_username));

if ($user_token_result) {
    $user_token = $user_token_result->token;
    $updated_time = $user_token_result->updated_time;

} else {
    $user_token = 'Không tìm thấy token cho người dùng này';
    $updated_time = 'Không tìm thấy updated_time cho người dùng này';

}

// Display user token information
echo "<h2>Số token hiện tại của bạn: " . esc_html($user_token) . "</h2>
<h2>Cập nhật: " . esc_html($updated_time) . "</h2>
";

global $wp_query;
$custom_gift_id = $wp_query->get('custom_gift_id');

// Check if custom_gift_id exists
if ($custom_gift_id) {
    // Query to fetch gift details
    $sql = "SELECT id_gift, gift_send, date_created, date_expired, user_receive FROM gift_from_admin WHERE id_gift = %s";
    $result = $wpdb->get_row($wpdb->prepare($sql, $custom_gift_id));

    if ($result) {
        echo "<div>ID: " . esc_html($result->id_gift ?? 'Không có dữ liệu') . "</div>";
        echo "<div>Gift Send: " . esc_html($result->gift_send ?? 'Không có dữ liệu') . "</div>";
        echo "<div>Date Created: " . esc_html($result->date_created ?? 'Không có dữ liệu') . "</div>";
        echo "<div>Date Expired: " . esc_html($result->date_expired ?? 'Không có dữ liệu') . "</div>";
        echo "<div>User Receive: " . esc_html($result->user_receive ?? 'Không có dữ liệu') . "</div>";
    } else {
        echo 'Lỗi: Không tìm thấy phần quà nào';
    }
} else {
    echo 'Không tìm thấy phần quà nào.';
}

get_footer();
?>
