<?php
/*
 * Template Name: Doing Template Writing
 * Template Post Type: ieltswritingtests
 
 */


if (is_user_logged_in()) {
    $post_id = get_the_ID();
    $user_id = get_current_user_id();

$post_id = get_the_ID();

// Get the custom number field value
//$custom_number = get_post_meta($post_id, '_ieltswritingtests_custom_number', true);
$custom_number = intval(get_query_var('id_test'));

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

$id_test = $custom_number;
 // Get current time (hour, minute, second)
 $hour = date('H'); // Giờ
 $minute = date('i'); // Phút
 $second = date('s'); // Giây

 // Generate random two-digit number
 $random_number = rand(10, 99);
 // Handle user_id and id_test error, set to "00" if invalid
 if (!$user_id) {
    $user_id = '00'; // Set user_id to "00" if invalid
}

if (!$id_test) {
    $id_test = '00'; // Set id_test to "00" if invalid
}


 // Create result_id
 $result_id = $hour . $minute . $second . $id_test . $user_id . $random_number;

 echo "<script> 
        var resultId = '" . $result_id . "';
        console.log('Result ID: ' + resultId);
    </script>";



// Fetch the question_choose and time for the given id_test
$sql = "SELECT question_choose,testname, time, test_type, tag,book  FROM ielts_writing_test_list WHERE id_test = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $custom_number); // 'i' is used for integer
$stmt->execute();
$result = $stmt->get_result();

$question_choose = [];
$time = ''; // Variable to store the time

if ($row = $result->fetch_assoc()) {
    // Split the question_choose string into an array
    $question_choose = explode(',', $row['question_choose']);
    // Get the time directly from the query result
    $time = $row['time'];
    $testname = $row['testname'];
    $test_type = $row['test_type'];
}
add_filter('document_title_parts', function ($title) use ($testname) {
    $title['title'] = $testname; // Use the $testname variable from the outer scope
    return $title;
});


get_header(); // Gọi phần đầu trang (header.php)
// Prepare an array to store the questions
$questions = [];
$topic = ''; // Variable to store the topic

// Fetch Task 1 questions based on question_choose
if (!empty($question_choose)) {
    $placeholders = implode(',', array_fill(0, count($question_choose), '?'));
    $sql1 = "SELECT id_test, task, question_type, question_content, image_link, sample_writing, important_add 
              FROM ielts_writing_task_1_question WHERE id_test IN ($placeholders)";
    $stmt1 = $conn->prepare($sql1);
    $stmt1->bind_param(str_repeat("i", count($question_choose)), ...$question_choose); // Bind as integers
    $stmt1->execute();
    $result1 = $stmt1->get_result();

    while ($row = $result1->fetch_assoc()) {
        $questions[] = [
            "question" => $row['question_content'],
            "part" => $row['task'],
            "sample_essay" => $row['sample_writing'],
            "image" => $row['image_link'],
            "id_question" => $row['id_test'], // Use id_test for id_question

        ];
    }

    // Fetch Task 2 questions based on question_choose
    $sql2 = "SELECT id_test, task, topic, question_type, question_content, sample_writing, important_add 
              FROM ielts_writing_task_2_question WHERE id_test IN ($placeholders)";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param(str_repeat("i", count($question_choose)), ...$question_choose); // Bind as integers
    $stmt2->execute();
    $result2 = $stmt2->get_result();

    while ($row = $result2->fetch_assoc()) {
        $questions[] = [
            "question" => $row['question_content'],
            "part" => $row['task'],
            "sample_essay" => $row['sample_writing'],
            "id_question" => $row['id_test'], // Use id_test for id_question

            "image" => '',
        ];

        // Capture the topic (assuming the same topic for all rows)
        if (!$topic) {
            $topic = $row['topic'];
        }
    }
}

// Output the quizData as JavaScript
echo '<script type="text/javascript">
const quizData = {
    "title": "' . htmlspecialchars($testname) . '",
    "testtype": "' . htmlspecialchars($test_type) . '",
    "duration": "' . htmlspecialchars($time) . '",
    "questions": ' . json_encode($questions) . '

};
console.log("Bài thi: ", quizData);
</script>';



// Truy vấn dữ liệu từ bảng order_and_prompt_api_list
$sql3 = "SELECT list_name_endpoint_order, last_use_end_point 
          FROM order_and_prompt_api_list 
          WHERE number = 1";
$result3 = $conn->query($sql3);

$now_end_point = '';
if ($result3->num_rows > 0) {
    while ($row = $result3->fetch_assoc()) {
        $list_name_endpoint_order = json_decode($row['list_name_endpoint_order'], true);
        $last_use_end_point = $row['last_use_end_point'];

        if (is_array($list_name_endpoint_order)) {
            // Tìm id của last_use_end_point
            $current_id = null;
            foreach ($list_name_endpoint_order as $item) {
                if ($item['name'] === $last_use_end_point) {
                    $current_id = $item['id'];
                    break;
                }
            }

            // Tìm name kế tiếp
            $now_end_point = '';
            if ($current_id !== null) {
                // Nếu tìm thấy last_use_end_point, lấy id kế tiếp
                $next_id = $current_id + 1;
                $now_end_point = array_reduce($list_name_endpoint_order, function ($carry, $item) use ($next_id) {
                    return ($item['id'] === $next_id) ? $item['name'] : $carry;
                }, '');
            }

            // Nếu không tìm thấy name kế tiếp (id cuối cùng), lấy id 1
            if (!$now_end_point) {
                foreach ($list_name_endpoint_order as $item) {
                    if ($item['id'] === 1) {
                        $now_end_point = $item['name'];
                        break;
                    }
                }
            }

            // Tính next_end_point
            $next_end_point = '';
            if ($now_end_point) {
                foreach ($list_name_endpoint_order as $item) {
                    if ($item['name'] === $now_end_point) {
                        $current_id = $item['id'];
                        break;
                    }
                }

                if ($current_id !== null) {
                    $next_id = $current_id + 1;
                    $next_end_point = array_reduce($list_name_endpoint_order, function ($carry, $item) use ($next_id) {
                        return ($item['id'] === $next_id) ? $item['name'] : $carry;
                    }, '');
                }

                if (!$next_end_point) {
                    foreach ($list_name_endpoint_order as $item) {
                        if ($item['id'] === 1) {
                            $next_end_point = $item['name'];
                            break;
                        }
                    }
                }
            }
        }
    }
}


// Xuất biến JavaScript
echo '<script type="text/javascript">
var now_end_point = "' . htmlspecialchars($now_end_point) . '";
var next_end_point_for_update = "' . htmlspecialchars($next_end_point) . '";



</script>';




// Nếu tìm thấy now_end_point, kiểm tra bảng api_key_route
if ($now_end_point) {
    $sql4 = "SELECT name_end_point, api_endpoint_url, api_key, updated_time, type, all_time_use_number, today_time_use_number 
             FROM api_key_route 
             WHERE name_end_point = ?";
    $stmt = $conn->prepare($sql4);
    $stmt->bind_param("s", $now_end_point);
    $stmt->execute();
    $result4 = $stmt->get_result();

    if ($result4->num_rows > 0) {
        // Xuất dữ liệu ra console
        while ($row = $result4->fetch_assoc()) {
            echo '<script type="text/javascript">
            var url_end_point = "' . htmlspecialchars($row['api_endpoint_url']) . '";
            var all_time_use = "'. (int)$row['all_time_use_number'] .  '";
            var today_use = "'  . (int)$row['today_time_use_number'] . '";
            var type_gate = "' . htmlspecialchars($row['type']) . '";

            //console.log("API Endpoint URL: ", "' . htmlspecialchars($row['api_endpoint_url']) . '");
            //console.log("Updated Time: ", "' . htmlspecialchars($row['updated_time']) . '");
            //console.log("Type: ", "' . htmlspecialchars($row['type']) . '");
            //console.log("All Time Use Number: ", ' . (int)$row['all_time_use_number'] . ');
            //console.log("Today Time Use Number: ", ' . (int)$row['today_time_use_number'] . ');
            </script>';
        }
    } else {
        echo '<script type="text/javascript">console.log("No matching endpoint found in api_key_route.");</script>';
    }
    $stmt->close();
} else {
    echo '<script type="text/javascript">console.log("No now_end_point found.");</script>';
}

// Close the database connection

// Close the database connection
$conn->close();

    ?>
  


<html lang="en">
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://smtpjs.com/v3/smtp.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    <script type="text/javascript" src="http://localhost/wordpress/contents/themes/tutorstarter/ielts-writing-toolkit/function/handwriting/handwriting.js"></script>

  
    

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ielts Writing Tests</title>
    <link rel="stylesheet" href="http://localhost/wordpress/contents/themes/tutorstarter/ielts-writing-toolkit/style/style_9.css">
    
</head>
<style>
    

.tooltip {
    position:relative;
    cursor: pointer;
    background-color: yellow;

  }
 
  .tooltip .tooltiptext {
    visibility: hidden;
    width: 150px;
    background-color: #555;
    color: #fff;
    text-align: center;
    border-radius: 6px;
    padding: 10px;
    position: absolute;
    z-index: 1;
    bottom: 125%; /* Position above the span */
    left: 50%;
    margin-left: -75px; /* Center the tooltip */
    opacity: 0;
    transition: opacity 0.3s;
  }
  
  .tooltip .tooltiptext::after {
    content: "";
    position: absolute;
    top: 100%; /* Arrow at the bottom */
    left: 50%;
    margin-left: -5px;
    border-width: 5px;
    border-style: solid;
    border-color: #555 transparent transparent transparent;
  }
  
  .tooltip.active .tooltiptext {
    visibility: visible;
    opacity: 1;
  }
  
  .tooltiptext button {
    background-color: #4CAF50; /* Green */
    border: none;
    color: white;
    padding: 5px 10px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    margin: 2px 0;
    font-size: 12px;
    border-radius: 4px;
    cursor: pointer;
  }
  
  .tooltiptext button:hover {
    background-color: #3e8e41;
  }

#highlight-icon-modify{
    width : 20px;
    height: 20px;
}
.tooltiptext img {
    display: inline-block;
    width: 20px;  /* Set the desired width */
    height: 20px; /* Set the desired height */
    margin-right: 5px; /* Add some space between images */
    cursor: pointer; /* Change cursor to pointer on hover */
    vertical-align: middle; /* Align images vertically in the middle */
}






    .container {
            margin-bottom: 60px; /* Ensure space for the time-remaining-container */
            display: flex;
}


.buttonsidebar{
    background-color: transparent;
              padding: 8px 12px 8px 12px;
              border: none;
          color: black;
              font-family: sf pro text, -apple-system, BlinkMacSystemFont, Roboto,
                  segoe ui, Helvetica, Arial, sans-serif, apple color emoji,
                  segoe ui emoji, segoe ui symbol;
              margin-top: 20px;
              font-weight: 700;
              font-size: 20px;
}
.buttonsidebar img{
    width: 22px;
    height: 22px;
}
.h1-text{
    font-weight:bold;
    font-size: 25px;
}


.quiz-container {
    padding:10px;
    overflow: auto;
    height: calc(100% - 35px);
    visibility: visible;
    position: absolute;
    left: 0;
    width: 100%;
  } 

.overall_band_test_container-css{
    width: 100%;
}

  #time-remaining-container {
    width: 100%;
    height: 35px;
    display: flex;
    justify-content: center;
    align-items: center; 
    position: fixed;
    bottom: 0;
    background-color: black;
    color: white;
    padding: 0px;
    left: 0;
}

.question-side-img{
    width: 85%;
}


.chart_overall_full{
    display: flex;
    flex-direction: row;
}

.left_chart {
    width: 70%;
    padding: 10px;
}

.right_chart {
    width: 30%;
    padding: 10px;
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

</style>
<body onload="main()">
    
    <div class="container"  id ="container">
        <div class="main-block">


        <div id = "test-prepare">
            <div class="loader"></div>
            <h3>Your test will begin shortly</h3>
            <div style="display: none;" id="date" style="visibility:hidden;"></div>
            <div  style="display: none;"  id="id_test"  style="visibility:hidden;"><?php echo esc_html($custom_number);?></div>
            <div  style="display: none;"  id="title"  style="visibility:hidden;"></div>


        </div>
         


            <div id="basic-info"  style="display:none">
                <div style="display: flex;">
                    <b style="margin-right: 5px;">Description </b>
                    <div id="description"></div>
                </div>
                <div style="display: flex;">
                    <b style="margin-right: 5px;">Thời gian làm bài: </b>
                    <div id="duration"></div>
                </div>
                <div style="display: flex;">
                    <b style="margin-right: 5px;">ID Test:</b>
                    <div id="id_test_div"></div>
                </div>
                <div style="display: flex;">
                    <b style="margin-right: 5px;">ID Category: </b>
                    <div id="id_category"></div>
                </div>
                





        <div id ="info-div"  style="display:none">
                <div id="band-score"></div> <!--Ex: 8.0-->
                <div id="band-score-expand"></div><!--Ex: 8.0 (Task1): 7.5 Task2: 8.0-->

                <div id="date-div"></div>
                <div id="type_test"></div>
                <div id="time-result" ></div>
                <div id="data-save-task-1" ></div>
                <div id="data-save-task-2" ></div>

                <div id="user-essay-task-1"></div>
                <div id="user-essay-task-2"></div>
                <div id="summary-essay-task-1"></div>
                <div id="summary-essay-task-2"></div>
                <div id="breakdown-task-1"></div>
                <div id="breakdown-task-2"></div>
                <div id="details-comment-task-1"></div>
                <div id="details-comment-task-2"></div>


                <div id="number-of-question-div" ></div>
                <div id="id-category-div"></div>
                <div id="question-test-div" ></div>
                <div id="user-essay-div"></div>
                <div id="sample-essay-div" ></div>
                <div id="overall-band-div" ></div>
                <div id="time-do-test-div" ></div>
                <div id="summarize-test-div" ></div>
                <div id="overall-band-and-comment-div" ></div>
                <div id="analysis-test-div" ></div>
            </div>

                <div style="display: flex;">
                    <b style="margin-right: 5px;">Số câu hỏi: </b>
                    <div id="number-questions"></div>
                </div>
                <div style="display: flex;">
                    <b style="margin-right: 5px;">Percentage of correct answers that pass the test: </b>
                    <div id="pass_percent"></div>
                </div>
                <div style="display: flex;">
                    <b style="margin-right: 5px;">List of question: </b>
                    <button id = "show-list-popup-btn">Show list</button>
                </div>

                <div id= "show-list-popup" class="popup">
                    <div class="popup-content-1">
                
                        <span class="close" onclick="closeListQuestion()">&times;</span>
                         <p class ="h1-text" >Danh sách câu hỏi</p>
                         <div id="question-list"></div>
                    </div>
                 </div>


                 
            </div>
            <button class="button-3" style="display:none"  id="start-test" onclick="showLoadingPopup()">Bắt đầu làm bài</button>
            
            
         <!-- New add sửa đổi 3/8/2024-->

            <div class = "fixedrightsmallbuttoncontainer" style ="display:none">
                <button  class ="buttonsidebar"  id="report-error"><img  width="22px" height="22px" src="http://localhost/wordpress/contents/themes/tutorstarter/ielts-writing-toolkit/assets/images/report.png" ></button><br>  
                   <button class ="buttonsidebar"  id="full-screen-button">⛶</button><br>
                    <button  onclick=" DarkMode()" class ="buttonsidebar"><img width="30px" height="30px" src="http://localhost/wordpress/contents/themes/tutorstarter/ielts-writing-toolkit/assets/images/dark-mode.png"></img></button><br>
                </div>
                
         
         
         <div id="report-error-popup" class="popup-report">
                    
            <div class="popup-content-report">
         
         
         <span class="close" onclick="closeReportErorPopup()">&times;</span>
                <section class ="contact">
                <p class ="h1-text" style="text-align: center;" > Báo lỗi đề thi, đáp án </p>
         
         
                <form action="#" reset()>
                <div class="input-box">
                    <div class="input-field field">
                <input type="text" id ="name" class="item" placeholder="Tên của bạn" autocomplete="off">
                            <div class="error-txt">Không được bỏ trống ô này</div>
         
            </div>
         
            <div class="input-field field">
                <input type="email" class="item" id="email" placeholder="Nhập email của bạn" autocomplete="off">
                <div class="error-txt email">Không được bỏ trống ô này</div>
         
            </div>
         </div>
         
         <div class="input-box">
                    <div class="input-field field">
                <input id="testnamereport" class="item" type="text" name="testnamereport" autocomplete="off" placeholder="Nhập tên đề thi báo lỗi" >
                            <div class="error-txt">Không được bỏ trống ô này</div>
         
            </div>
         
         
                    <div class="input-field field">
         
                <input type="text" name="testnumberreport" id="testnumberreport" class="item" autocomplete="off" placeholder="Bạn muốn báo lỗi câu số mấy" >
                            <div class="error-txt">Không được bỏ trống ô này</div>
         
            </div>
         </div>
         
         <div class ="textarea-field field">
                <textarea id = "descriptionreport" autocomplete="off" rows="4" name="description" placeholder="Mô tả thêm về lỗi" class="item"> </textarea>
                <div class="error-txt">Không được bỏ trống ô này</div>
            </div>
                  <div class="g-recaptcha" data-sitekey="6Lc9TNkpAAAAAKCIsj_j8Ket1UH5nBwoO2DmKZ_e
         "></div>
         
                <button type="submit">Gửi báo lỗi</button>
            </form>
         </section>
            </div>
         </div>
         
         
         
         
         
                
                
         </div>
         <!-- End new add sửa đổi 3/8/2024-->
  
            <div id="quiz-container" class ="quiz-container"></div>
            
        </div>
        
        <div id="time-remaining-container">
            <div id="button-container">
                <div id="clock-block">
                    <h3 id="countdown"></h3>
                </div>
                
            </div>
            <div id = "navigation-button" style="display: none;" >
                <button id="prev-button"  onclick="showPrevQuestion()">Quay lại</button>
                <button id="next-button"  onclick="showNextQuestion()">Tiếp theo</button>

                
            </div>

            <div id = "submit-button-container">  
                <button onclick="preSubmitTest()" style="display: none;" id="submit-button">Nộp bài làm</button>                
            </div>
        </div>

        <div id="canvas-container"></div>

 <!-- Next button -->




        <div id="loading-popup" style="display: none;">
            <div id="loading-spinner"></div>
        </div>
    </div>
  <!-- giấu form send kết quả bài thi -->


    
  <!--
  <span id="message" style="display: none;"></span>
  <form id="frmContactUs"  style="display: none;">
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
 
 
     <div class = "form-group">
          <input type="text" id="numberofquestionform" name="numberofquestionform" placeholder="Số câu hỏi"  class="form-control form_data"  />
          <span id="date_error" class="text-danger" ></span>
    </div>

    <div class = "form-group" >
        <input type="text" id="idtest" name="idtest" placeholder="Id test"  class="form-control form_data" />
        <span id="idtest_error" class="text-danger" ></span>
   </div>

    
 
      
 
     <div class = "form-group"  >
          <input type="text" id="timedotest" name="timedotest" placeholder="Thời gian làm bài"  class="form-control form_data" />
          <span id="time_error" class="text-danger"></span>
     </div>
 
    
 
     <div class = "form-group" >
          <input type="text" id="idcategory" name="idcategory" placeholder="Id category"  class="form-control form_data" />
          <span id="idcategory_error" class="text-danger"></span>
     </div>
 
      <div class = "form-group"   >
          <input type="text"  id="testname" name="testname" placeholder="Test Name"  class="form-control form_data" />
          <span id="testname_error" class="text-danger"></span>
     </div>
 
     </div>
 
    <div class="card-footer" style="display: none;">
        <button type="button" name="submit" id="submit" class="btn btn-primary" onclick="save_data(); return false;">Save</button>
                       <td><input type="submit" id="submit" name="submit"/></td> 
 
      </div>
          
  </div>
  <div id="result_msg" style="display: none;"></div>
 </form>-->
 <!-- kết thúc send form -->	

</div>
 <div id ="result-full-page">
        <div class="tab">
            <button class="tablinks" onclick="changeTabResult(event, 'result-page-tabs-1')">Overall</button>
            <button class="tablinks" onclick="changeTabResult(event, 'result-page-tabs-2')">Your test</button>
            <button class="tablinks" onclick="changeTabResult(event, 'result-page-tabs-3')">Sample</button>
            <button class="tablinks" onclick="changeTabResult(event, 'result-page-tabs-4')">Practice</button>


        </div>

        <div id ="result-page-tabs-1" class="tabcontent">
            <h3>Result </h3>
            <div style="display: none;" id="date" style="visibility:hidden;"></div>

            <div id ="overall_band_test_container" class ="overall_band_test_container-css" style="display:none">

                <div id ="full_band_chart" class="chart_overall_full">
                    <div class ="left_chart">
                        <h3 style="color:red">FULL OVERALL</h3>
                        <canvas id="barchartfull" style="width:100%;max-width:600px"></canvas>
                    </div>
                    <div class ="right_chart">
                        <div id="detail_score"></div>
                    </div>


                </div>
                <div id ="area-final">
                    <button class ="button-3">Rent teacher to mark this essay</button>
                    <button onclick="window.print();" class ="button-3">Print your essay</button>

                </div>
                

            
            </div>
            <div id ="final-result"></div>
            <div id ="breakdown"></div>
            <div id ="band-decriptor"></div>
            <h3>General Comment</h3>
            <div id ="general-comment"></div>

        </div>
        <div id="result-page-tabs-2" class="tabcontent">
            <div id="question-buttons-container"></div>
            <div id="tab2-user-essay-container"></div>
        </div>




        <div id ="result-page-tabs-3"class="tabcontent">
            <h3>Sample Answer</h3>
            <div id = "sample-tab-container"></div>

        </div>
        <div id ="result-page-tabs-4"class="tabcontent">
                <h3>This is tab 4 result</h3>
                <div id="userResult"></div>  
                <div id="userBandDetail"></div>            
                <div id="userAnswerAndComment"></div>   
                
                    <!-- giấu form send kết quả bài thi -->


                    
                
                <span id="message"></span>
                <form id="saveUserWritingTest" >
                        <div class="card">
                            <div class="card-header">Form lưu kết quả 11/5</div>
                            <div class="card-body" >

                    


                    <div class = "form-group">
                        <input type="text" id="dateform" name="dateform" placeholder="Ngày"  class="form-control form_data"  />
                        <span id="date_error" class="text-danger" ></span>
                    </div>

                    

                    <div class = "form-group" >
                        <input type="text" id="idtest" name="idtest" placeholder="Id test"  class="form-control form_data" />
                        <span id="idtest_error" class="text-danger" ></span>
                    </div>

                 

                    <div class = "form-group"   >
                        <input type="text"  id="testname" name="testname" placeholder="Test Name"  class="form-control form_data" />
                        <span id="testname_error" class="text-danger"></span>
                    </div>
                    <div class = "form-group"   >
                        <input type="text"  id="test_type" name="test_type" placeholder="Test Type"  class="form-control form_data" />
                        <span id="testname_error" class="text-danger"></span>
                    </div>
                    <div class = "form-group"  >
                        <input type="text" id="timedotest" name="timedotest" placeholder="Thời gian làm bài"  class="form-control form_data" />
                        <span id="time_error" class="text-danger"></span>
                    </div>
                    
                    <div class = "form-group"   >
                        <input type="text"  id="band-score-form" name="band-score-form" placeholder="Band Score Overall"  class="form-control form_data" />
                        <span id="correctanswer_error" class="text-danger"></span>  
                    </div>
                    <div class = "form-group"   >
                        <input type="text"  id="band-score-expand-form" name="band-score-expand-form" placeholder="Band Score Overall - Expand"  class="form-control form_data" />
                        <span id="correctanswer_error" class="text-danger"></span>  
                    </div>

                    <div class = "form-group"   >
                        <textarea type="text"  id="task1userform" name="task1userform" placeholder="User Task 1"  class="form-control form_data" ></textarea>
                        <span id="correctanswer_error" class="text-danger"></span>  
                    </div>
                    <div class = "form-group"   >
                        <textarea type="text"  id="task2userform" name="task2userform" placeholder="User Task 2"  class="form-control form_data" ></textarea>
                        <span id="correctanswer_error" class="text-danger"></span>  
                    </div>

                    <div class = "form-group"   >
                        <textarea type="text"  id="task1summaryuserform" name="task1summaryuserform" placeholder="Summary User Task 1"  class="form-control form_data" ></textarea>
                        <span id="correctanswer_error" class="text-danger"></span>  
                    </div>

                    <div class = "form-group"   >
                        <textarea type="text"  id="task2summaryuserform" name="task2summaryuserform" placeholder="Summary User Task 2"  class="form-control form_data" ></textarea>
                        <span id="correctanswer_error" class="text-danger"></span>  
                    </div>
                    <div class = "form-group"   >
                        <textarea type="text"  id="task1breakdownform" name="task1breakdownform" placeholder="User Breakdown Task 1"  class="form-control form_data" ></textarea>
                        <span id="correctanswer_error" class="text-danger"></span>  
                    </div>
                    <div class = "form-group"   >
                        <textarea type="text"  id="task2breakdownform" name="task2breakdownform" placeholder="User Breakdown Task 2"  class="form-control form_data" ></textarea>
                        <span id="correctanswer_error" class="text-danger"></span>  
                    </div>
                    <div class = "form-group"   >
                        <textarea type="text"  id="datasaveformtask1" name="datasaveformtask1" placeholder="datasave task 1"  class="form-control form_data" ></textarea>
                        <span id="correctanswer_error" class="text-danger"></span>  
                    </div>
                    <div class = "form-group"   >
                        <textarea type="text"  id="datasaveformtask2" name="datasaveformtask2" placeholder="datasave task 2"  class="form-control form_data" ></textarea>
                        <span id="correctanswer_error" class="text-danger"></span>  
                    </div>

                    <div class = "form-group"   >
                        <textarea type="text"  id="task1detailscommentform" name="task1detailscommentform" placeholder="User Breakdown Task 1"  class="form-control form_data" ></textarea>
                        <span id="correctanswer_error" class="text-danger"></span>  
                    </div>
                    <div class = "form-group"   >
                        <textarea type="text"  id="task2detailscommentform" name="task2detailscommentform" placeholder="User Breakdown Task 2"  class="form-control form_data" ></textarea>
                        <span id="correctanswer_error" class="text-danger"></span>  
                    </div>

                    <div class = "form-group"   >
                        <textarea type="text"  id="testsavenumber" name="testsavenumber" placeholder="testsavenumber"  class="form-control form_data" ></textarea>
                        <span id="testsavenumber_error" class="text-danger"></span>  
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
        
    </div>
    
    <script >
    


    // function save data qua ajax
    jQuery('#saveUserWritingTest').submit(function(event) {
    event.preventDefault(); // Prevent the default form submission
    
     var link = "<?php echo admin_url('admin-ajax.php'); ?>";
    
     var form = jQuery('#saveUserWritingTest').serialize();
     var formData = new FormData();
     formData.append('action', 'save_user_result_ielts_writing');
     formData.append('save_user_result_ielts_writing', form);
    
     jQuery.ajax({
         url: link,
         data: formData,
         processData: false,
         contentType: false,
         type: 'post',
         success: function(result) {
             jQuery('#submit').attr('disabled', false);
             if (result.success == true) {
                 jQuery('#saveUserWritingTest')[0].reset();
             }
             jQuery('#result_msg').html('<span class="' + result.success + '">' + result.data + '</span>');
         }
     });
    });
    
             //end
    

document.addEventListener("DOMContentLoaded", function () {
    document.addEventListener("submitForm", function () {
        setTimeout(function () {
            let form = document.getElementById("saveUserWritingTest");
            form.submit(); // This should work now that there's no conflict
        }, 2000); 
    });
});


//end new adding


 
                    document.getElementById("title").innerHTML = quizData.title;
                    document.getElementById("type_test").innerHTML = quizData.testtype;
                    var questionList = document.getElementById("question-list");
                    questionList.innerHTML = ""; // Clear any existing content

                for (let i = 0; i < quizData.questions.length; i++) {
                    questionList.innerHTML += `Question ${i + 1}: ${quizData.questions[i].question}<br>`;
                }
              

               
                    
            

const currentDate = new Date();

            // Get day, month, and year
const day = currentDate.getDate();
const month = currentDate.getMonth() + 1; // Adding 1 because getMonth() returns zero-based month index
const year = currentDate.getFullYear();

            // Display the date
const dateElement = document.getElementById('date-div');
dateElement.innerHTML = `${year}-${month}-${day}`;


        
        document.getElementById("show-list-popup-btn").addEventListener('click', openListQuestion);
        
        // Close the draft popup when the close button is clicked
        function closeListQuestion() {
            document.getElementById('show-list-popup').style.display = 'none';
        }

        function openListQuestion() {
            document.getElementById('show-list-popup').style.display = 'block';
          
        }




function openDraft(index) {
  var x = document.getElementById("draft-"+index);
  if (x.style.display === "block") {
    x.style.display = "none";
  } else {
    x.style.display = "block";
  }
}

function changeTabResult(evt, tabResultName) {
                var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(tabResultName).style.display = "block";
        evt.currentTarget.className += " active";
        }

        // Automatically click the first tab when the page loads
        document.addEventListener('DOMContentLoaded', function() {
        document.querySelector('.tablinks').click();
    });
        
    



let currentQuestionIndex = 0;
let userMarkEssay;
function main() {
    
    setTimeout(function(){
        console.log("Show Test!");
        startTest();
    }, 2000);
    
    


    //MathJax.Hub.Queue(["Typeset", MathJax.Hub]);
    if (quizData.description !== "")
        document.getElementById("description").innerHTML = quizData.description;

    
    document.getElementById("duration").innerHTML = formatTime(quizData.duration);
    document.getElementById("pass_percent").innerHTML = quizData.pass_percent + "%";
    document.getElementById("number-questions").innerHTML = quizData.number_questions + " question(s)";
    document.getElementById("id_test_div").innerHTML = quizData.id_test;

    let contentQuestions = "";
   

 for (let i = 0; i < quizData.questions.length; i++) {
    let questionId = quizData.questions[i].id;
    let part = quizData.questions[i].part;
    let sampleEssay= quizData.questions[i].sample_essay;
    let questioncontent = quizData.questions[i].question;
    let explanationQuiz = quizData.questions[i].explanations;



        // if need bookmark, add to line class="question"<image src="bookmark-1.png" id="imageToggle" onclick="rememberQuestion(${i + 1})"></image>             

    contentQuestions += `


    <div class="questions" id="question-${i}" style="display:none;" >
        



        <div id ="current_band_detail_div-${questionId}" style="display:none;"></div>
        <div class="quiz-section">
            <div class="question-side">
                <p class="header-question">Question ${(i + 1)}:  </p>
                <div id="navigation-buttons" display='none'>
                <!-- Next button -->
           
        
                </div>
                <div id = "questionContextArea-${i}" >
                    <p class="question"  ><p style="font-style:italic"> Writing task ${part} </p> <b>${questioncontent}</b><img class="question-side-img"  src="${quizData.questions[i].image}"></p>
                </div>
                <button onclick="openDraft(${i})" class ='open-draft-button'>Open Draft</button>

                <textarea class="draft" id="draft-${i}"></textarea>

                <div id ="sample-essay-area-${i}" style="display:none;"><p class ="h1-text" style='color:red'>Sample Essay:</p> <br>${sampleEssay} </div> 

                <div id="overall-band-${questionId}" class="overall-band" style="display:none;"></div>
            </div>


            <div class="answer-side" id = "userEssayTab2-${i}" >
                <div class="answer-list">
                    <button id="input_normal" style ="display:none" onclick="toggleInputMode(${i}, 'normal')">Nhập văn bản bình thường</button>
                    <button id="input_handwrite" style ="display:none" onclick="toggleInputMode(${i}, 'handwrite')">Chuyển sang dạng viết (Phù hợp với thí sinh thi paper exam)</button>
                    <textarea spellcheck="false" class="exam_area" placeholder="You should start your essay here..." id="question-${i}-input" oninput="updateWordCount(${i})"></textarea>
                   <!--16/8/2024 -->
                <div class ="userEssayContainer" id = "userEssayCheckContainer${i+1}">
                    <h3>Your Essay</h3>
                    <div  id ="userEssayCheck-${i+1}" ></div>
                </div>
                <!--16/8/2024 -->
                    <div id="word-count-${i}" class="word-count">Word count: 0 Sentence count: 0 Paragraph: 0</div>
                    <button style="display:none" onclick="checkIncorrectSpellings(${i})" >Check</button>
                    <div id ="correction-${i}"></div>
               
                
                    </div>
                <div class="explain-zone" id="explanation-${questionId}" style="display:none;">
                    <p><b>Giải thích: </b>${explanationQuiz}</p>
                </div>

                <div id="summarize-${i}" style="display:none;"> </div>
                <div id="breakdown-${i}" style="display:none;"> </div>

                <div id="detail-cretaria-${i}" style="display:none;"> </div>
                <div id="recommendation-${i}" style="display:none;"> </div>

            </div>
        </div>
    </div>`;

   
    
}



    document.getElementById("quiz-container").innerHTML = contentQuestions;
    
    document.getElementById("quiz-container").style.display = 'none';
    document.getElementById("submit-button").style.display = 'none';
    document.getElementById("clock-block").style.display = 'none';

   // addEventListenersToInputs();
}




 




/*
function toggleInputMode(questionIndex, mode) {
    const textarea = document.getElementById(`question-${questionIndex}-input`);
    const canvasContainerId = `canvas-container-${questionIndex}`;
    let canvasContainer = document.getElementById(canvasContainerId);



    if (mode === 'handwrite') {
        
        if (!canvasContainer) {
            canvasContainer = document.createElement('div');
            canvasContainer.id = canvasContainerId;
            canvasContainer.innerHTML = `
                <div class = "canvas_exam_area">
                         <canvas id="canvas-${questionIndex}"   width="590px" height = "700px" style="border: 1px solid; cursor: pointer;"></canvas>
                </div>
                <div class='buttons'>
                    <button onclick="recognizeCanvas(${questionIndex})">Recognize</button>
                    <button onclick="eraseCanvas(${questionIndex})">Erase</button>
                    <button onclick="undoCanvas(${questionIndex})">Undo</button>
                    <button onclick="redoCanvas(${questionIndex})">Redo</button>
                    <button onclick="toggleEraser(${questionIndex})">Toggle Eraser</button>
                    <button onclick="drawLines(${questionIndex})">Draw Lines</button>
                </div>
                <div>Result: <span id='result-${questionIndex}'></span></div>
            `;
            textarea.parentNode.insertBefore(canvasContainer, textarea.nextSibling);
            initializeCanvas(questionIndex);






        }
        textarea.style.display = 'none';
        canvasContainer.style.display = 'block';
    } 
    
    
    else {
        if (canvasContainer) {
            canvasContainer.style.display = 'none';
        }
        textarea.style.display = 'block';
    }



    
} */
let undoStack = [];
let redoStack = [];

function initializeCanvas(questionIndex) {
    const canvasElement = document.getElementById(`canvas-${questionIndex}`);
    const canvas = new handwriting.Canvas(canvasElement, 3);
    canvas.setCallBack((data, err) => {
        if (err) throw err;
        document.getElementById(`result-${questionIndex}`).innerHTML = data;
    });

    canvas.setLineWidth(5);
    canvas.setOptions({
        language: "en",
        numOfReturn: 1
    });

    canvasElement.canvasObj = canvas;

    // Add drawing event listeners
    let isDrawing = false;

    canvasElement.addEventListener('mousedown', () => {
        isDrawing = true;
        saveState(canvasElement);
    });

    canvasElement.addEventListener('mousemove', (e) => {
        if (isDrawing) {
            canvasElement.canvasObj.stroke(e);
        }
    });

    canvasElement.addEventListener('mouseup', () => {
        if (isDrawing) {
            recognizeCanvas(questionIndex);
            isDrawing = false;
        }
    });

    canvasElement.addEventListener('mouseout', () => {
        if (isDrawing) {
            recognizeCanvas(questionIndex);
            isDrawing = false;
        }
    });
}

function saveState(canvasElement) {
    undoStack.push(canvasElement.toDataURL());
    if (undoStack.length > 50) { // Limit the undo stack size
        undoStack.shift();
    }
    redoStack = []; // Clear the redo stack
}

function restoreState(stack, canvasElement) {
    if (stack.length > 0) {
        const ctx = canvasElement.getContext('2d');
        const canvasState = stack.pop();
        const img = new Image();
        img.src = canvasState;
        img.onload = () => {
            ctx.clearRect(0, 0, canvasElement.width, canvasElement.height);
            ctx.drawImage(img, 0, 0);
            recognizeCanvas(0); // Automatically call recognizeCanvas
        };
    }
}

function recognizeCanvas(questionIndex) {
    document.getElementById(`canvas-${questionIndex}`).canvasObj.recognize();
}

function eraseCanvas(questionIndex) {
    const canvasElement = document.getElementById(`canvas-${questionIndex}`);
    const ctx = canvasElement.getContext('2d');
    ctx.clearRect(0, 0, canvasElement.width, canvasElement.height);
    saveState(canvasElement); // Save state after erasing
    recognizeCanvas(questionIndex);
}

function undoCanvas(questionIndex) {
    const canvasElement = document.getElementById(`canvas-${questionIndex}`);
    if (undoStack.length > 0) {
        redoStack.push(canvasElement.toDataURL());
        restoreState(undoStack, canvasElement);
    }
}

function redoCanvas(questionIndex) {
    const canvasElement = document.getElementById(`canvas-${questionIndex}`);
    if (redoStack.length > 0) {
        undoStack.push(canvasElement.toDataURL());
        restoreState(redoStack, canvasElement);
    }
}

function toggleEraser(questionIndex) {
    const canvasElement = document.getElementById(`canvas-${questionIndex}`);
    const canvas = canvasElement.canvasObj;
    canvas.isEraser = !canvas.isEraser;
    canvas.setLineWidth(canvas.isEraser ? 30 : 5);
    canvas.setPenColor(canvas.isEraser ? "white" : "black");
}

function drawLines(questionIndex) {
    const canvasElement = document.getElementById(`canvas-${questionIndex}`);
    const ctx = canvasElement.getContext("2d");
    ctx.lineWidth = 1;
    ctx.strokeStyle = "#e0e0e0";
    const lineHeight = 40;
    for (let y = lineHeight; y < canvasElement.height; y += lineHeight) {
        ctx.beginPath();
        ctx.moveTo(0, y);
        ctx.lineTo(canvasElement.width, y);
        ctx.stroke();
    }
    saveState(canvasElement); // Save state after drawing lines
}

// Call initializeCanvas for question 0
//initializeCanvas(0);

/*
document.getElementById('input_handwrite').addEventListener('click', () => toggleInputMode(currentQuestionIndex, 'handwrite'));
document.getElementById('input_normal').addEventListener('click', () => toggleInputMode(currentQuestionIndex, 'normal'));
*/

//submitTest();

function showLoadingPopup() {
    let timerInterval;
    Swal.fire({
        title: "Đang tải bài thi",
        html: "Vui lòng đợi trong giây lát.",
        timer: 1000,
        allowOutsideClick: false,
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading();
            const timer = Swal.getPopup().querySelector("b");
        },
        willClose: () => {
            clearInterval(timerInterval);
        }
    }).then((result) => {
        if (result.dismiss === Swal.DismissReason.timer) {
            Swal.fire({
                icon: "success",
                title: "Bài thi của bạn đã sẵn sàng",
                html: "Nhấn vào <b>Bắt đầu làm bài</b> để bắt đầu bài thi."
            });
            startTest();
        }
    });
}

let duration = quizData.duration * 60; // Convert duration to seconds
let countdownInterval;
let countdownElement; // Declare this to use later for tracking the countdown

document.addEventListener("DOMContentLoaded", function() {
    const urlParams = new URLSearchParams(window.location.search);

    const optionTimeSet = urlParams.get('option');
    const optionTrackSystem = urlParams.get('optiontrack');

    if (optionTimeSet) {
        duration = optionTimeSet; // Override the duration if provided in URL
        var timeleft = optionTimeSet / 60 + " phút";
        console.log(`Time left: ${timeleft}`);
    }

    // Initialize countdownElement to the total duration initially
    countdownElement =  duration;

    // Example: Start a countdown (You would have your own countdown logic)
    countdownInterval = setInterval(function() {
        if (countdownElement > 0) {
            countdownElement--;
        } else {
            clearInterval(countdownInterval);
        }
    }, 1000);
});
function formatTime(seconds) {
    let minutes = Math.floor(seconds / 60);
    let remainingSeconds = seconds % 60;
    return `${minutes} phút ${remainingSeconds} giây`;
}


function startTest() {
    startCountdown(duration);
    document.getElementById("test-prepare").style.display = "none";
    document.getElementById("quiz-container").style.display = 'block';
    document.getElementById("start-test").style.display = 'none';
    document.getElementById("clock-block").style.display = 'block';
    document.getElementById("submit-button").style.display = 'block';
    document.getElementById("navigation-buttons").style.display = 'flex';
    
    document.getElementById("navigation-button").style.display = 'block';

    hideBasicInfo();
    showQuestion(currentQuestionIndex);
}
function doit(index, event) {
    event.preventDefault();  // Explicitly prevent form submission

    var langCode = document.getElementById(`lang-${index}`).value;
    tinyMCE.activeEditor.execCommand("mceWritingImprovementTool", langCode);
}

function hideBasicInfo() {
    document.getElementById("basic-info").style.display = 'none';
}

function showQuestion(index) {
    const questions = document.getElementsByClassName("questions");
    for (let i = 0; i < questions.length; i++) {
        questions[i].style.display = "none";
    }
    questions[index].style.display = "block";

    document.getElementById("prev-button").style.display = index === 0 ? "none" : "inline-block";
    document.getElementById("next-button").style.display = index === questions.length - 1 ? "none" : "inline-block";
}

function showPrevQuestion() {
    if (currentQuestionIndex > 0) {
        currentQuestionIndex--;
        showQuestion(currentQuestionIndex);
    }
}

function showNextQuestion() {
    if (currentQuestionIndex < quizData.questions.length - 1) {
        currentQuestionIndex++;
        showQuestion(currentQuestionIndex);
    }
}

function addEventListenersToInputs() {
    const inputs = document.getElementsByClassName('input');
    for (let i = 0; i < inputs.length; i++) {
        inputs[i].addEventListener('focus', function () {
            this.classList.add('input-focused');
        });
        inputs[i].addEventListener('blur', function () {
            this.classList.remove('input-focused');
        });
    }

    for (let i = 0; i < quizData.questions.length; i++) {
        document.getElementById(`input_handwrite-${i}`).addEventListener('click', () => switchToCanvas(i));
        document.getElementById(`input_normal-${i}`).addEventListener('click', () => switchToTextarea(i));
    }
}



// khai báo biến chạy xuyên suốt code các file js

        //common information test (Length, Paragraph,Sentence)
let length_essay = ``;
let paragraph_essay =``;
let sentence_count ;


let userLevel;
let band_description;
let overallband  = 0;
let task_achievement_comment =``;
let lexical_resource_comment =``;
let grammatical_range_and_accuracy_comment =``;
let coherence_and_cohesion_comment = ``;
let task_achievement_part_1 = 0;
let grammatical_range_and_accuracy_part_1 = 0;
let lexical_resource_part_1 = 0;
let coherence_and_cohesion_part_1 = 0;
        //linking word
let total_linking_word_count;
let linking_word_array_essay = ``;
let linking_word_to_accumulate = ``;
let unique_linking_word_count = ``;

let spelling_grammar_error_essay =``;
let spelling_grammar_error_count;

let point_for_intro_cheking_part_1_essay = ``;
let point_for_second_paragraph_cheking_part_1_essay = ``;


let position_introduction_task_1;
let position_overall_task_1;
let position_body_task_1;

        /* relation point between user essay and question 
            (* If count_common_number > 4) 
                relation_point_essay_and_question = point_for_intro_cheking_part_1_essay
            */
let relation_point_essay_and_question;


        //increase, decrease,...
let increase_word_array = ``;
let increase_word_count = ``;
let unique_increase_word_count;


let decrease_word_array = ``;
let decrease_word_count = ``;
let unique_decrease_word_count;


let unchange_word_array = ``;
let unchange_word_count = ``;
let unique_unchange_word_count;


let goodVerb_word_array = ``;
let goodVerb_word_count = ``;
let unique_goodVerb_word_count = ``;


let well_adjective_and_adverb_word_array = ``;
let well_adjective_and_adverb_word_count = ``;
let unique_well_adjective_and_adverb_word_count = ``;



let adjective_and_adverb_word_array = ``;
let adjective_and_adverb_word_count = ``;
let unique_adjective_and_adverb_word_count = ``;


let simple_sentences_count ;
let complex_sentences_count;
let compound_sentences_count;

let position_simple_sentences;
let position_complex_sentences;
let position_compound_sentences;
let simple_sentences = ``;
let complex_sentences = ``;
let compound_sentences = ``;




        //data checking  - common_numbers cần thiết trong đoạn
let count_common_number;
let type_of_essay; // agree/disagree, bar chart,...


        //structure Overall - Check thứ tự introduction, overall và detail 
let structure_info;

function startCountdown(duration) {
    const countdownElement = document.getElementById("countdown");
    let timer = duration;

    // Adjust for the initial 2-second delay
    timer -= 3;

    countdownInterval = setInterval(function() {
        console.log(`Timer: ${timer}`);

        if (timer > 0) {
            const minutes = Math.floor(timer / 60);
            const seconds = timer % 60;
            countdownElement.innerHTML = `<div style ="display: inline-block"> <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg> ${minutes}:${seconds < 10 ? '0' + seconds : seconds} </div>`;
            timer--;
        } else {
            clearInterval(countdownInterval); // Stop the countdown at 0
            preSubmitTest(); // Call submission function
            console.log("Countdown complete!");
        }
    }, 1000);
}



/*
The given table compares different means of transportation in terms of the annual distance traveled by adults in two separate years, 1977 and 2007. Units are measured in miles. Overall, cars were by far the most soar popular method of transport during the entire 40-year period, witnessing the most dramatic rise. In contrast, bicycles and motorcycles were the least common modes of transportation. Regarding changes in commuting patterns, there was an upward trend in the use of cars, trains, and taxis, while the remaining and rise methods of transport recorded a to soar decline. In 1977, cars occupied the position as the to continuing most prevalent vehicle, with 3500 miles traveled, nearly decrease quadruple the distance of the second and third most popular methods, buses and trains, which ranged from 800 to 900 miles. Meanwhile, the distance traveled on foot was 400 miles on average, twice as high as that of plummet taxis. Bicycles wass as common as motorbikes, with the average distancess for each vehicle standing at 100 miles. By 2007, the distance traveled by car had increase twofold to 7100 miles, solidifying its position as the most preferred mode of transportation. Similar changes were seen in the figures for trains and taxis, with the former witnessing a slight growth to 1000 miles and the latter recording a fourfold rise to 800 miles. In contrast, the other transport methods underwent a descending trend, with the most dramatic drop recorded in buses, falling by 300 miles to reach 500 miles in 2007. The distances traveled by walking, motorbikes, and bicycles dropped to 300, 90, and 80 miles, respectively.

*/



    </script>
    



    <script src="http://localhost/wordpress/contents/themes/tutorstarter/ielts-writing-toolkit/function/description/mark_description.js"></script> 




    <script src="http://localhost/wordpress/contents/themes/tutorstarter/ielts-writing-toolkit/function/full_overall_chart/full_band_chart.js"></script>
    <script  src="http://localhost/wordpress/contents/themes/tutorstarter/ielts-writing-toolkit/function/process.js"></script>
    


    <script  src="http://localhost/wordpress/contents/themes/tutorstarter/ielts-writing-toolkit/function/right_bar_feature/reload-test.js"></script>
    <script  src="http://localhost/wordpress/contents/themes/tutorstarter/ielts-writing-toolkit/function/right_bar_feature/fullscreen.js"></script>
    <script  src="http://localhost/wordpress/contents/themes/tutorstarter/ielts-writing-toolkit/function/right_bar_feature/report-error.js"></script>
    <script  src="http://localhost/wordpress/contents/themes/tutorstarter/ielts-writing-toolkit/function/right_bar_feature/change-mode.js"></script>


  <script src="http://localhost/wordpress/contents/themes/tutorstarter/ielts-writing-toolkit/submitTest1.js"></script>





   

</body>
</html>

        




<?php

} else {
    get_header();
    echo '<p>Please log in to submit your answer.</p>';
    get_footer();
}