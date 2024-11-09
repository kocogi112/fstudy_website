<?php
/*
 * Template Name: Doing Template Speaking
 * Template Post Type: dictationexercise
 
 */

 //get_header(); // Gọi phần đầu trang (header.php)

if (is_user_logged_in()) {
    $post_id = get_the_ID();
    $user_id = get_current_user_id();
    $additional_info = get_post_meta($post_id, '_dictationexercise_additional_info', true);

   
$post_id = get_the_ID();
// Get the custom number field value
$custom_number = get_post_meta($post_id, '_dictationexercise_custom_number', true);

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

$sql = "SELECT type_test, testname, script_paragraph FROM dictation_question WHERE id_test = ?";


$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $custom_number); // 'i' is used for integer
$stmt->execute();
$result = $stmt->get_result();


// Fetch result and store in variables
if ($row = $result->fetch_assoc()) {
    $script_paragraph = $row['script_paragraph'];
    $type_test = $row['type_test'];
    $testname = $row['testname'];
} else {
    $script_paragraph = "No content available.";
}
// Đóng kết nối
$conn->close();

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="\wordpress\contents\themes\tutorstarter\dictation-exercise-toolkit\style.css">
    <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  </head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    
<body>
    <div class="header">
        <a class="logo">Onluyen247.net</a>
        <div class="header-right">
          <a  href="#home">Home</a>
          
        </div>
      </div>

      <div class="container">
    <div id = "before-content">
        <div class = "bf-content-setting-class" id = "bf-content-setting">
            

        </div>
    </div>
    <div id="content">
        <div id = 'left-side' class="left-side">
        <div id ="intro" style="text-align: center;">
            <h3><?php echo htmlspecialchars($testname); ?></h3>
            <p>This speech/ conversation has been collected from <?php echo htmlspecialchars($type_test); ?></p>
            <p id = 'number-sentences'>Number of sentence: </p>
            <p>Guidelines: </p>
            <button onclick ="getStart()" class="button-1">Start Now</button>
        </div>
        

    <div id ="start-dictation" style="display: none;">
        <p id="main-paragraph"><?php echo htmlspecialchars($script_paragraph); ?></p>
        <div id="navigation-buttons">
            <button id="previous" onclick="navigate('prev')">Previous</button>
            <button id="next" onclick="navigate('next')">Next</button>
        </div>
    </div>
</div>
<div id = "right-side" class = "right-side" >

    <div id ="about-speech">
         </div>
</div>

</div>

<div id ="ads" style="display:  flex;justify-content: center; align-items: center;">
    DEV TAG: Powered by Nguyen Minh Long
</div>
</div>
<script src="\wordpress\contents\themes\tutorstarter\dictation-exercise-toolkit\js/get-user-country-api.js"></script>
<script src="\wordpress\contents\themes\tutorstarter\dictation-exercise-toolkit\js/countries.js"></script>
<script src="\wordpress\contents\themes\tutorstarter\dictation-exercise-toolkit\js/script-translate.js"></script>    
<script src="\wordpress\contents\themes\tutorstarter\dictation-exercise-toolkit\script.js"></script>
  
    
</body>
</html>


<?php
} else {
    get_header();
    echo '<p>Please log in start reading test.</p>';
    get_footer();
}