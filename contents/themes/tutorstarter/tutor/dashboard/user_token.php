<?php
/*
 * Template Name: Gift User Token Template
 * Template Post Type: user token page
 
 */


// Database credentials (update with your own database details)
$servername = "localhost";
$username = "root";
$password = ""; // No password by default
$dbname = "wordpress";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Lấy giá trị custom_gift_id từ URL
global $wp_query;
//$custom_gift_id = $wp_query->get('custom_gift_id');


  global $wpdb;
 // Get the current user
 $current_user = wp_get_current_user();
 $current_username = $current_user->user_login;
 $user_id = $current_user->ID; // Lấy user ID

  // Thực hiện truy vấn để lấy id_video_orginal 
  $sql = "SELECT username, updated_time, token FROM user_token WHERE username = %s";
  $result = $wpdb->get_row($wpdb->prepare($sql, $current_username));

  if ($result) {
    echo "Username: " . ($result->username ?? 'Không có dữ liệu');
    echo "Time updated " . ($result->updated_time ?? 'Không có dữ liệu');
    echo "Token: " . ($result->token ?? 'Không có dữ liệu');
    echo "Lịch sử sử dụng Token: " . ($result->token_use_history ?? 'Không có dữ liệu');

  // Đóng kết nối
  $conn->close();

?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Token</title>
  <style>
   

  </style>
</head>
<body>
  <div id="container">
      <div id ="id_notification"></div>
      <div id ="timestamp"></div>
      <div class ="content">
          <h3 id ="title"></h3>
          <p id ="content"></p>
      </div>
      <div id ="content">
        <p>Mua thêm token</p>
      </div>


  </div>

  <script>
  </script>
</body>
</html>


<?php
}
else {
  echo 'Lỗi: Không tìm thấy Token nào';
}
