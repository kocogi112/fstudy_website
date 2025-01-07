<?php
/*
 * Template Name: Doing Template Listening
 * Template Post Type: ieltslisteningtest
 
 */

 //get_header(); // Gọi phần đầu trang (header.php)

if (is_user_logged_in()) {
    $post_id = get_the_ID();
    $user_id = get_current_user_id();
    $additional_info = get_post_meta($post_id, '_ieltslisteningtest_additional_info', true);

   
$post_id = get_the_ID();
// Get the custom number field value
$custom_number = get_post_meta($post_id, '_ieltslisteningtest_custom_number', true);

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

$sql = "SELECT part, duration, number_question_of_this_part, paragraph, group_question, category FROM ielts_reading_part_1_question WHERE id_test = ?";
$sql2 = "SELECT part, duration, number_question_of_this_part, paragraph, group_question, category FROM ielts_reading_part_2_question WHERE id_test = ?";
$sql3 = "SELECT part, duration, number_question_of_this_part, paragraph, group_question, category FROM ielts_reading_part_3_question WHERE id_test = ?";


$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $custom_number); // 'i' is used for integer
$stmt->execute();
$result = $stmt->get_result();

$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("i", $custom_number); // 'i' is used for integer
$stmt2->execute();
$result2 = $stmt2->get_result();

$stmt3 = $conn->prepare($sql3);
$stmt3->bind_param("i", $custom_number); // 'i' is used for integer
$stmt3->execute();
$result3 = $stmt3->get_result();


$part = []; // Initialize the array for storing questions

while ($row = $result->fetch_assoc()) {
    // Create an associative array for each row
    $entry = [
        'part_number' => $row['part'],
        'paragraph' => $row['paragraph'],
        'number_question_of_this_part' => $row['number_question_of_this_part'],
        'duration' => $row['duration'],
    ];

    // Kiểm tra nếu group_question là chuỗi JSON hợp lệ, chuyển thành mảng hoặc đối tượng PHP
    if (!empty($row['group_question'])) {
        $entry['group_question'] = json_decode($row['group_question'], true); // Chuyển từ JSON string thành array
    } else {
        $entry['group_question'] = null; // Đặt là null nếu không có dữ liệu
    }

    // Add the entry to the $part array
    $part[] = $entry;
}




while ($row = $result2->fetch_assoc()) {
    // Create an associative array for each row
    $entry = [
        'part_number' => $row['part'],
        'paragraph' => $row['paragraph'],
        'number_question_of_this_part' => $row['number_question_of_this_part'],
        'duration' => $row['duration'],
    ];

    // Kiểm tra nếu group_question là chuỗi JSON hợp lệ, chuyển thành mảng hoặc đối tượng PHP
    if (!empty($row['group_question'])) {
        $entry['group_question'] = json_decode($row['group_question'], true); // Chuyển từ JSON string thành array
    } else {
        $entry['group_question'] = null; // Đặt là null nếu không có dữ liệu
    }

    // Add the entry to the $part array
    $part[] = $entry;
}




while ($row = $result3->fetch_assoc()) {
    // Create an associative array for each row
    $entry = [
        'part_number' => $row['part'],
        'paragraph' => $row['paragraph'],
        'number_question_of_this_part' => $row['number_question_of_this_part'],
        'duration' => $row['duration'],
    ];

    // Kiểm tra nếu group_question là chuỗi JSON hợp lệ, chuyển thành mảng hoặc đối tượng PHP
    if (!empty($row['group_question'])) {
        $entry['group_question'] = json_decode($row['group_question'], true); // Chuyển từ JSON string thành array
    } else {
        $entry['group_question'] = null; // Đặt là null nếu không có dữ liệu
    }

    // Add the entry to the $part array
    $part[] = $entry;
}




/*
// Output the quizData as JavaScript
echo '<script type="text/javascript">
const quizData = {
    part: ' . json_encode($part, JSON_UNESCAPED_SLASHES) . '
};

console.log(quizData);
</script>';
*/


// Đóng kết nối
$conn->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ielts Listening Test</title>
    <style>
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    font-size: 18px;

}


.content-left {
    width: 50%;
    padding: 20px;
    border-right: 1px solid #ccc;
}

.content-right {
    width: 50%;
    padding: 20px;
}

.question {
    margin-bottom: 20px;
}

.pagination-container {
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 20px 0;
}

.pagination-container button {
    margin: 0 10px;
    padding: 10px 20px;
    cursor: pointer;
}

#questions-container {
    padding: 20px;
}

#questions-container label {
    display: block;
    margin: 20px 0;
}

.answer-input {
    width: 250px;
    padding: 8px;
    margin: 5px 0;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}
/* Fixed header for question range */
.fixed-above {
    background-color: #f8f9fa; /* Màu nền cho header */
    padding: 10px 20px; /* Khoảng cách bên trong */
    display: flex;
    justify-content: space-between; /* Căn giữa cho các phần tử */
    align-items: center; /* Căn giữa theo chiều dọc */
    position: fixed; /* Để header luôn ở phía trên */
    width: 100%; /* Chiều rộng đầy đủ */
    top: 0; /* Đặt vị trí ở trên cùng */
    z-index: 1000; /* Đảm bảo header nằm trên các phần tử khác */
}
.header-content {
    display: flex;
    flex-direction: column; /* Đặt hướng dọc để timer nằm bên dưới ID */
    align-items: flex-start; /* Căn trái cho cả hai phần tử */
}

.test-taker-id {
    font-weight: bold;
}


.question-range {
    background-color: #e9ecef; /* Màu xám cho phần câu hỏi */
    padding: 14px; /* Khoảng cách bên trong */
    margin-top: 30px; /* Tăng giá trị để tránh che khuất bởi header */
    width: 97%; /* Không chiếm chiều rộng đầy đủ */
    margin: 0 auto; /* Căn giữa */
}


#content {
    display: none;
    padding-top: 70px; /* Thêm padding trên cùng để tránh che khuất bởi header */
    width: 100%;
}
#header{
    height:40px
}



/* Container that includes the quiz content */
.quiz-container {
   /* margin-top: 60px; /* Adjust to fit below the fixed header */
    margin-bottom: 30px; /* Adjust to fit above the fixed footer */
    margin-top: 10px; /* Adjust to fit above the fixed footer */

    display: flex;
    height: calc(100vh - 210px); /* Make sure the container fits within the viewport */
    overflow: hidden; /* Prevent content from overflowing the container */
    width: 100%;
}

/* Left side scrollable content (paragraphs) */
.content-left {
    width: 50%;
    padding: 20px;
    overflow-y: auto; /* Make it scrollable */
    border-right: 1px solid #ccc;
}

/* Right side scrollable content (questions) */
.test-content {
    width: 100%;
    padding: 20px;
    overflow-y: auto; /* Make it scrollable */
}

/* Paragraph container styling (optional, adjust based on content) */
#paragraph-container p {
    margin-bottom: 20px;
}

/* Questions container styling (optional, adjust based on content) */
#questions-container .question {
    margin-bottom: 25px;
}

#question-nav {
  display: inline-block;
  margin: 0 auto;
}

#question-nav span {
  cursor: pointer;
  padding: 10px;
  margin-right: 5px;
  background-color: #fff;
  border: 1px solid #ccc;
  display: inline-block;
  text-align: center;
}

#question-nav span:hover {
  background-color: #ddd;
}
/* HTML: <div class="loader"></div> */
.loader {
  width: 70px;
  aspect-ratio: 1;
  border-radius: 50%;
  border: 8px solid #514b82;
  animation: l20-1 0.8s infinite linear alternate, l20-2 1.6s infinite linear;
}

#test-prepare {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    position: fixed; /* Giữ loader cố định giữa màn hình */
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%); /* Căn giữa theo cả chiều ngang và dọc */
    height: 200px;
    z-index: 1001; /* Đảm bảo loader ở trên các phần tử khác */
}


@keyframes l20-1{
   0%    {clip-path: polygon(50% 50%,0       0,  50%   0%,  50%    0%, 50%    0%, 50%    0%, 50%    0% )}
   12.5% {clip-path: polygon(50% 50%,0       0,  50%   0%,  100%   0%, 100%   0%, 100%   0%, 100%   0% )}
   25%   {clip-path: polygon(50% 50%,0       0,  50%   0%,  100%   0%, 100% 100%, 100% 100%, 100% 100% )}
   50%   {clip-path: polygon(50% 50%,0       0,  50%   0%,  100%   0%, 100% 100%, 50%  100%, 0%   100% )}
   62.5% {clip-path: polygon(50% 50%,100%    0, 100%   0%,  100%   0%, 100% 100%, 50%  100%, 0%   100% )}
   75%   {clip-path: polygon(50% 50%,100% 100%, 100% 100%,  100% 100%, 100% 100%, 50%  100%, 0%   100% )}
   100%  {clip-path: polygon(50% 50%,50%  100%,  50% 100%,   50% 100%,  50% 100%, 50%  100%, 0%   100% )}
}
@keyframes l20-2{ 
  0%    {transform:scaleY(1)  rotate(0deg)}
  49.99%{transform:scaleY(1)  rotate(135deg)}
  50%   {transform:scaleY(-1) rotate(0deg)}
  100%  {transform:scaleY(-1) rotate(-135deg)}
}
/* Fixed bottom navigation for questions */
.fixed-bottom {
    position: fixed;
    bottom: 0;
    width: 100%;
    background-color: #f1f1f1;
    border-top: 1px solid #ccc;
    text-align: center;
    z-index: 1000;
}

#part-navigation {
    display: flex;
    justify-content: space-between; /* Ensure even spacing */
    width: 100%;
    align-items: center; /* Align buttons vertically */
}

#part-navigation-button, #submit-btn {
    flex-grow: 1; /* Share remaining width equally for parts */
    padding: 10px;
    margin: 5px;
    background-color: #f1f1f1;
    border: 1px solid #ccc;
    cursor: pointer;
    border-radius: 5px;
    text-align: center;
    height: 40px; /* Fixed height for consistency */
    display: flex;
    justify-content: center;
    align-items: center;
}

#submit-btn {
    width: 150px; /* Fixed width for Submit button */
    flex-grow: 0; /* Prevent it from expanding */
}


#part-navigation button.active {
    background-color: #0073e6;
    color: white;
    border: 1px solid #0073e6;
}
img{
    max-width: 100%;
}

.audio-player {
      margin: 20px auto;
      background: #fff;
      border: 1px solid #ddd;
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      padding: 10px;
      display: flex;
      align-items: center;
      gap: 15px;
    }
    audio {
      flex: 1;
      outline: none;
      border-radius: 10px;
    }
    /* Ẩn nút download và menu playback speed mặc định */
    audio::-webkit-media-controls-enclosure {
      overflow: hidden;
    }
    audio::-webkit-media-controls-download-button,
    audio::-webkit-media-controls-playback-rate-button {
      display: none;
    }
    .controls {
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .controls button, .controls select {
      padding: 8px 12px;
      border: 1px solid #ddd;
      border-radius: 5px;
      background: #007bff;
      color: #fff;
      cursor: pointer;
      font-size: 14px;
      outline: none;
      transition: background 0.3s ease;
    }
    .controls button:hover, .controls select:hover {
      background: #0056b3;
    }
    .controls select {
      background: #fff;
      color: #333;
    }

    </style>
</head>
<script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>

<body onload="main()">
    <div id = "test-prepare">
        <div class="loader"></div>
        <h3>Your test will begin shortly</h3>
        <div style="display: none;" id="date" style="visibility:hidden;"></div>
        <div style="display: none;" id="title" style="visibility:hidden;"><?php the_title(); ?></div>
        <div  style="display: none;"  id="id_test"  style="visibility:hidden;"><?php echo esc_html($custom_number);?></div>

    </div>


    <div id="header" class="fixed-above">
        <div class="header-content">
            
            <div class="test-taker-id">Test taker ID</div>
            <span id="timer" class="timer" style="font-weight: bold"></span>
        </div>
    </div>
    
    
    <div id="content" style="display: none;">
            <div id="question-range-of-part" class="question-range">Part 1<br>Read the text and answer questions 1–13.</div>
            <div class="audio-player">
                <audio id="audio" controls>
                    <source id="audio-source" src="" type="audio/mpeg">
                    Your browser does not support the audio element.
                </audio>
                <div class="controls">
                  <button id="reload">Reload</button>
                  <select id="speed">
                    <option value="0.5">0.5x</option>
                    <option value="1" selected>1x (Normal)</option>
                    <option value="1.5">1.5x</option>
                    <option value="2">2x</option>
                  </select>
                </div>
              </div>


        <div class="quiz-container">
            

           
            <div class="test-content">
                <div id="questions-container">
                    <!-- Questions will be loaded dynamically -->
                </div>

             <div class="pagination-container">
                    <button id="prev-btn" style="display: none;" >Previous</button>
                    
                    <button id="next-btn" style="display: none;" >Next</button>
                    <h5  id="time-result"></h5>

                    <h5 id ="useranswerdiv">Here: </h5>
                     <!-- giấu form send kết quả bài thi -->


    
  
     <span id="message" style="display:none" ></span>
     <form id="saveReadingResult" >
             <div class="card">
                 <div class="card-header">Form lưu kết quả</div>
                 <div class="card-body" >
    
        <div class = "form-group" >
             <input   type="text" id="resulttest" name="resulttest" placeholder="Kết quả"  class="form-control form_data" />
             <span id="result_error" class="text-danger" ></span>
    
        </div>
    
    
        <div class = "form-group">
             <input type="text" id="dateform" name="dateform" placeholder="Ngày"  class="form-control form_data"  />
             <span id="date_error" class="text-danger" ></span>
        </div>
    
         
    
        <div class = "form-group"  >
             <input type="text" id="timedotest" name="timedotest" placeholder="Thời gian làm bài"  class="form-control form_data" />
             <span id="time_error" class="text-danger"></span>
        </div>
    
        <div class = "form-group" >
             <input type="text" id="idtest" name="idtest" placeholder="Id test"  class="form-control form_data" />
             <span id="idtest_error" class="text-danger" ></span>
        </div>
    
        <div class = "form-group" >
             <input type="text" id="idcategory" name="idcategory" placeholder="Id category"  class="form-control form_data" />
             <span id="idcategory_error" class="text-danger"></span>
        </div>
    
         <div class = "form-group"   >
             <input type="text"  id="testname" name="testname" placeholder="Test Name"  class="form-control form_data" />
             <span id="testname_error" class="text-danger"></span>
        </div>
        <div class = "form-group"   >
            <input type="text"  id="useranswer" name="useranswer" placeholder="User Answer"  class="form-control form_data" />
            <span id="useranswer_error" class="text-danger"></span>
       </div>
    
        <div class = "form-group"   >
            <input type="text"  id="correctpercentage" name="correctpercentage" placeholder="Correct percentage"  class="form-control form_data" />
            <span id="correctanswer_error" class="text-danger"></span>  
        </div>
    
    
      
    
      
    
    
    
    
        </div>
    
       <div class="card-footer">
           <!--  <button type="button" name="submit" id="submit" class="btn btn-primary" onclick="save_data(); return false;">Save</button>-->
                          <td><input type="submit" id="submit" name="submit"/></td> 
    
         </div>
             
     </div>
     <div id="result_msg" ></div>
    </form>
    <!-- kết thúc send form -->

                </div> 

                

                <div id="results-container"></div>
            </div>       


        </div>
        

        <div id="question-nav-container" class="fixed-bottom">
            <!-- <span id="part-label"></span>
            <div id="question-nav"></div> -->
            <div id="part-navigation">
            </div>

        </div>
    </div>

    
    <!-- kết thúc send form -->
        
     <script src="\wordpress\contents\themes\tutorstarter\ielts-listening-toolkit\script1.js"></script>  


</body>
</html>


<?php
} else {
    get_header();
    echo '<p>Please log in start reading test.</p>';
    get_footer();
}