<?php
/*
 * Template Name: Result Template
 * Template Post Type: ieltsreadingtest
 */

get_header();
$post_id = get_the_ID();

// Get the custom number field value
$custom_number = get_post_meta($post_id, '_ieltsreadingtest_custom_number', true);

echo "<script>console.log('Custom Number doing template: " . esc_js($custom_number) . "');</script>";

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

// Truy vấn `question_choose` từ bảng `ielts_reading_test_list` theo `id_test`
$sql_test = "SELECT question_choose FROM ielts_reading_test_list WHERE id_test = ?";
$stmt_test = $conn->prepare($sql_test);

if ($stmt_test === false) {
    die('Lỗi MySQL prepare: ' . $conn->error);
}

$stmt_test->bind_param("i", $custom_number);
$stmt_test->execute();
$result_test = $stmt_test->get_result();

if ($result_test->num_rows > 0) {
    // Lấy các ID từ question_choose (ví dụ: "1001,2001,3001")
    $row_test = $result_test->fetch_assoc();
    $question_choose = $row_test['question_choose'];
    $id_parts = explode(",", $question_choose); // Chuyển thành mảng ID

    $part = []; // Mảng chứa dữ liệu của các phần

    // Lặp qua từng id_part và truy vấn bảng tương ứng
    foreach ($id_parts as $index => $id_part) {
        // Xác định bảng và câu lệnh SQL tương ứng dựa trên index của part
        switch ($index) {
            case 0:
                $sql_part = "SELECT part, duration, number_question_of_this_part, paragraph, group_question, category 
                             FROM ielts_reading_part_1_question WHERE id_part = ?";
                break;
            case 1:
                $sql_part = "SELECT part, duration, number_question_of_this_part, paragraph, group_question, category 
                             FROM ielts_reading_part_2_question WHERE id_part = ?";
                break;
            case 2:
                $sql_part = "SELECT part, duration, number_question_of_this_part, paragraph, group_question, category 
                             FROM ielts_reading_part_3_question WHERE id_part = ?";
                break;
            default:
                continue 2; // Nếu có nhiều hơn 3 phần, bỏ qua
        }

        // Chuẩn bị và thực thi câu lệnh SQL cho từng phần
        $stmt_part = $conn->prepare($sql_part);
        if ($stmt_part === false) {
            die('Lỗi MySQL prepare: ' . $conn->error);
        }

        $stmt_part->bind_param("i", $id_part);
        $stmt_part->execute();
        $result_part = $stmt_part->get_result();

        // Nếu có kết quả, thêm vào mảng part
        while ($row = $result_part->fetch_assoc()) {
            $entry = [
                'part_number' => $row['part'],
                'paragraph' => $row['paragraph'],
                'number_question_of_this_part' => $row['number_question_of_this_part'],
                'duration' => $row['duration'],
                'category' => $row['category'],
                'group_question' => $row['group_question']
            ];

            if (!empty($row['group_question'])) {
                $decoded = json_decode($row['group_question'], true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $entry['group_question'] = $decoded;
                } else {
                    // Log the error and set group_question to null
                    error_log('JSON decode error: ' . json_last_error_msg());
                    $entry['group_question'] = null;
                }
            } else {
                $entry['group_question'] = null;
            }
            

            // Thêm phần vào mảng part
            $part[] = $entry;
        }
    }

    // Xuất mảng quizData dưới dạng JavaScript
    echo '<script type="text/javascript">
    const quizData = {
        part: ' . json_encode($part, JSON_UNESCAPED_SLASHES) . '
    };

    console.log(quizData);
    </script>';
} else {
    echo '<script type="text/javascript">console.error("Không tìm thấy test với custom number: ' . $custom_number . '");</script>';
}

// Đóng kết nối
$conn->close();




global $wpdb; // Use global wpdb object to query the DB

// Get testsavenumber from URL
$testsavenumber = get_query_var('testsavereadingnumber');

// Query database to find results by testsavenumber
$results = $wpdb->get_results(
    $wpdb->prepare(
        "SELECT * FROM save_user_result_ielts_reading WHERE testsavenumber = %d",
        $testsavenumber
    )
);
if (!empty($results)) {
    // Display results
    foreach ($results as $result) {
        echo '<b style = "text-transform: uppercase;">Result and Explanation: ' . esc_html($result->testname) . '</b>';

        echo '
            <div class="result-score-details">
                    <div class="row">
                        <div class="col-12 col-md-3">
                            <div class="result-stats-box">
                                <div class="result-stats-item">
                                    <i class="fa-solid fa-check" style="color: #0de71c;"></i>
                                    <span class="result-stats-label">Kết quả làm bài</span>
                                    <span class="result-stats-text">'. esc_html($result->correct_number) .'/'. esc_html($result->total_question_number) .'</span>
                                </div>
                                <br>
                                <div class="result-stats-item">
                                   <i class="fa-solid fa-chart-simple" style="color: #63E6BE;"></i>
                                    <span class="result-stats-label">Độ chính xác (#đúng/#tổng)</span>
                                    <span class="result-stats-text">66.7%</span>
                                </div>
                                <br>
                                <div class="result-stats-item">
                                    <i class="fa-solid fa-clock" style="color: #63E6BE;"></i>
                                    <span class="result-stats-label">Thời gian hoàn thành</span>
                                    <span class="result-stats-text">'. esc_html($result->timedotest) .'</span>
                                </div>
                                <br>
                                <div class="result-stats-item">
                                   <i class="fa-solid fa-calendar-days" style="color: #372a60;"></i>
                                    <span class="result-stats-label">Ngày làm bài: '. esc_html($result->dateform) .'</span>
                                </div>
                            </div>
                            <br>
                        </div>
                        <div class="col-12 col-md-9">
                            <div class="row">
                                <div class="col">
                                    <div class="result-score-box">
                                        <div class="result-score-icon text-correct"><span class="fas fa-check-circle"></span></div>
                                        <div class="result-score-icontext text-correct">Trả lời đúng</div>
                                        <div class="result-score-text">'. esc_html($result->correct_number) .'</div>
                                        <div class="result-score-sub"><span>câu hỏi</span></div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="result-score-box">
                                        <div class="result-score-icon text-wrong"><span class="fas fa-times-circle"></span></div>
                                        <div class="result-score-icontext text-wrong" >Trả lời sai</div>
                                        <div class="result-score-text">'. esc_html($result->incorrect_number) .'</div>
                                        <div class="result-score-sub"><span>câu hỏi</span></div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="result-score-box">
                                        <div class="result-score-icon text-unanswered"><span class="fas fa-minus-circle"></span></div>
                                        <div class="result-score-icontext text-unanswered">Bỏ qua</div>
                                        <div class="result-score-text">'. esc_html($result->skip_number) .'</div>
                                        <div class="result-score-sub"><span>câu hỏi</span></div>
                                    </div>
                                </div>
                                
                                <div class="col">
                                    <div class="result-score-box">
                                        <div class="result-score-icon text-score"><i class="fa-solid fa-flag fa-lg" style="color: #74C0FC;"></i></div>
                                        <div class="result-score-icontext text-score">Điểm</div>
                                        <div class="result-score-text text-score">'. esc_html($result->overallband) .'</div>
                                        <div class="result-score-sub"><span>Overall</span></div>

                                    </div>
                                </div>
                                
                            </div>
                            <br>
                            
                        </div>
                    </div>
                </div>
        ';



    
        // Process user answers
        $userAnswers = $result->useranswer; // Assuming this is a string
        $questions = explode('Question:', $userAnswers); // Split by "Question:"
    
        // Group questions by parts
        $groupedQuestions = [];
        foreach ($questions as $questionData) {
            if (trim($questionData) === '') continue;
    
            // Extract data for each question
            preg_match('/(\d+), Part: (\d+), User Answer: (.*?)(?=Question:|$)/', $questionData, $matches);
    
            if (count($matches) === 4) {
                $questionNumber = $matches[1]; // Question number
                $partNumber = $matches[2];     // Part number
                $userAnswer = trim($matches[3]); // User's answer
    
                $groupedQuestions[$partNumber][] = [
                    'questionNumber' => $questionNumber,
                    'userAnswer' => $userAnswer,
                ];
            }
        }
    
        // Display questions grouped by parts
        foreach ($groupedQuestions as $partNumber => $questions) {

            echo '<b>Reading part ' . esc_html($partNumber) . '</b>';
            echo '<div class = "result-answers-list">';
                
                
        
                foreach ($questions as $question) {
                    $questionNumber = $question['questionNumber'];
                    $userAnswer = $question['userAnswer'];
        
                    echo '<div class="result-answers-item" >';
                    echo '<ul style="list-style: none; >';
                        echo '<li style="margin-bottom: 10px;">';
                        echo '<span class="question-number">';
                            echo '<strong>' . esc_html($questionNumber) . '</strong> ';
                        echo '</span>';

                        echo ' <span style="color: green;" id="correct-answer-' . esc_html($questionNumber) . '"></span>: ';
                        echo '<i >'. esc_html($userAnswer) . '</i> ' ;

                        echo '</li>';
                    echo '</div>';
                }
        
                echo '</ul>';
            echo'</div>';

        }
    }
    

    ?>



<!DOCTYPE html>
<html lang="en">
<head>
   
    <style>
body {
    font-family: Arial, sans-serif;
    font-size: 18px;
    padding:10px;
}
.row {
    display: flex;
    flex-wrap: wrap;
    margin-right: -.75rem;
    margin-left: -.75rem;
}

.col-12 {
    flex: 0 0 100%;
    max-width: 100%;
}

.result-stats-box {
    padding: 1.5rem 1rem;
    background-color: #f8f9fa;
    border: 1px solid #efefef;
    box-shadow: 0 2px 8px 0 rgba(0, 0, 0, .05);
    display: flex
;
    flex-direction: column;
}

.result-stats-item {
    display: flex
;
    justify-content: space-between;
}
.result-stats-label {
    margin-left: .5rem;
    margin-right: .5rem;
    flex-grow: 1;
}
.result-stats-icon {
    width: 25px;
}
.result-stats-text {
    width: 70px;
    font-weight: 500;
}
@media (min-width: 768px) {
    .col-md-9 {
        flex: 0 0 75%;
        max-width: 75%;
    }
}

.col {
    flex-basis: 0;
    flex-grow: 1;
    max-width: 100%;
}

.result-score-box {
    display: flex;
    flex-direction: column;
    background-color: #fff;
    padding: 1.5rem 1rem;
    border: 1px solid #efefef;
    box-shadow: 0 2px 8px 0 rgba(0, 0, 0, .05);
    border-radius: .65rem;
    margin-bottom: 1rem;
    align-items: center;
    justify-content: flex-start;
}
.text-score {
    color: #35509a;
}
.result-score-icon {
    font-size: 1.4rem;
    font-weight: 500;
}

.text-correct {
    color: #3cb46e;
}
.text-wrong {
    color: #e43a45;
}
@media (min-width: 768px) {
    .col-md-3 {
        flex: 0 0 25%;
        max-width: 25%;
    }
}
.result-answers-list{
    -webkit-columns:2;
    columns: 2;
}
.question-number {
    margin-right: .5rem;
}
.question-number strong {
    border-radius: 50%;
    background-color: #e8f2ff;
    color: #35509a;
    width: 35px;
    height: 35px;
    line-height: 35px;
    font-size: 15px;
    text-align: center;
    display: inline-block;
}
.result-answers-item {
    margin-bottom: 1rem;
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




#content-details {
    width: 100%;
    display:block;
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
.content-right {
    width: 50%;
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


.detail-display{
    border: 3px solid;
  padding: 10px;
  box-shadow: 5px 10px;
    width: 95%;
    margin-left: auto;
    margin-right: auto;
    display: block;
}
.correct-ans{
    color: green
}
    </style>
</head>
<script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>

<body onload ="main()">
    

<b>Detail and Explanation</b>
<div id = "detail-display" class = "detail-display">
    <div id="content-details" >
    
        <div class="quiz-container">
            

            <div class="content-left">
                <div id="questions-container">
                </div>
            </div>
            <div class="content-right">
                
                <div id="paragraph-container">
                </div>

             <div class="pagination-container">
                    <button id="prev-btn" style="display: none;" >Previous</button>
                    
                    <button id="next-btn" style="display: none;" >Next</button>
                    <h5  id="time-result"></h5>


    
  
                </div> 

                

                <div id="results-container"></div>
            </div>       


        </div>
        

        <div id="question-nav-container" class="fixed-bottom">
         
            <div id="part-navigation">
            </div>

        </div>
    </div>

</div>
        


    </body>
    <script src="/wordpress/contents/themes/tutorstarter/ielts-reading-tookit/script_result_1.js"></script>

</html>

<?php


} else {
    // If no results with testsavenumber
    echo '<p>Không có test nào với testsavenumber này.</p>';
}
if ( comments_open() || get_comments_number() ) :
    comments_template();
endif; 
get_footer();
