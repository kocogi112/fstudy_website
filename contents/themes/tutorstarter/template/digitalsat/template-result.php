<?php
/*
 * Template Name: Result Template Digital SAT
 * Template Post Type: digitalsat
 */

get_header();
$post_id = get_the_ID();

$post_id = get_the_ID();
$user_id = get_current_user_id();
$additional_info = get_post_meta($post_id, '_digitalsat_additional_info', true); 
$custom_number = get_post_meta($post_id, '_digitalsat_custom_number', true);
echo "<script>console.log('Custom Number doing template: " . esc_js($custom_number) . "');</script>";

// Database credentials
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

// Set custom_number as id_test
$id_test = $custom_number;

// Prepare the SQL statement
$sql = "SELECT testname, time, test_type, question_choose, tag, number_question, book FROM digital_sat_test_list WHERE id_test = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_test);
$stmt->execute();
$result = $stmt->get_result();


?>
<style>
    table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    padding: 8px;
    text-align: left;
    border: 1px solid #ddd;
}

th {
    background-color: #f2f2f2;
}

.close {
    border-color: transparent;
    background-color: transparent;
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}
.green-text {
    color: green;
}
.grey-text {
    color: grey;
}

.red-text {
    color: red;
}


body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
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


/* CSS */
.button-10 {
  flex-direction: column;
  align-items: center;
  padding: 6px 14px;
  font-family: -apple-system, BlinkMacSystemFont, 'Roboto', sans-serif;
  border-radius: 6px;
  border: none;

  color: #fff;
  background: linear-gradient(180deg, #4B91F7 0%, #367AF6 100%);
   background-origin: border-box;
  box-shadow: 0px 0.5px 1.5px rgba(54, 122, 246, 0.25), inset 0px 0.8px 0px -0.25px rgba(255, 255, 255, 0.2);
  user-select: none;
  -webkit-user-select: none;
  touch-action: manipulation;
}

.button-10:focus {
  box-shadow: inset 0px 0.8px 0px -0.25px rgba(255, 255, 255, 0.2), 0px 0.5px 1.5px rgba(54, 122, 246, 0.25), 0px 0px 0px 3.5px rgba(58, 108, 217, 0.5);
  outline: 0;
}


.group-control-part-btn{
    /*position: fixed;*/
    bottom: 70px;
    right: 10px;
    color: white;
    padding: 10px 20px;
    border: none;
    cursor: pointer;
}
.control-part-btn{
    background-color:black;
    color: #ffffff;
    height:60px;
    width: 60px;
}

.popup-content {
    background-color: #fefefe;
    margin: 5% auto; /* 5% từ trên, tự căn giữa */
    padding: 20px;
    border: 1px solid #888;
    max-height: 80%;
    overflow: auto;
    width: 90%; /* Đảm bảo popup đủ rộng */
    box-sizing: border-box; /* Đảm bảo padding không ảnh hưởng tới width */
}

.popup {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgba(0, 0, 0, 0.4); /* Black w/ opacity */
}

.container-popup {
    display: flex; /* Căn ngang */
    justify-content: space-between; /* Tạo khoảng cách đều giữa các phần */
    gap: 10px; /* Thêm khoảng cách giữa các cột nếu cần */
    width: 100%;
    box-sizing: border-box;
}

.left-popup, .right-popup {
    width: 50%; /* Mỗi cột chiếm 50% */
    padding: 10px;
    box-sizing: border-box; /* Đảm bảo padding không làm tràn width */
}

.left-popup {
    background-color: #f9f9f9; /* Màu nền nhẹ */
    border-right: 1px solid #ddd; /* Đường chia cột (tuỳ chọn) */
}

.right-popup {
    background-color: #ffffff; /* Màu nền trắng */
}

</style>
<head>
    <title>Digital SAT Report</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script type="text/javascript">var lbplugin_event_opt={"extension_enable":true,"dict_type":1,"dbclk_event":{"trigger":"none","enable":false,"display":2},"select_event":{"trigger":"none","enable":true,"display":2}};function loadScript(t,e){var n=document.createElement("script");n.type="text/javascript",n.readyState?n.onreadystatechange=function(){("loaded"===n.readyState||"complete"===n.readyState)&&(n.onreadystatechange=null,e())}:n.onload=function(){e()},n.src=t,document.getElementsByTagName("head")[0].appendChild(n)}setTimeout(function(){null==document.getElementById("lbdictex_find_popup")&&loadScript("https://stc-laban.zdn.vn/dictionary/js/plugin/lbdictplugin.min.js?"+Date.now()%1e4,function(){lbDictPlugin.init(lbplugin_event_opt)})},1e3);</script></body>


</head>

<?php
if ($result->num_rows > 0) {
    // Fetch test data if available
    $data = $result->fetch_assoc();

    
} else {
    echo "<script>console.log('No data found for the given id_test');</script>";
}
    global $wpdb;

    // Get current user's username
    $current_user = wp_get_current_user();
    $current_username = $current_user->user_login;
    $testsavenumber = get_query_var('testsavedigitalsatnumber');



    $results = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM save_user_result_digital_sat WHERE testsavenumber = %d",
            $testsavenumber
        )
    );
    
    $review = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM digital_sat_test_list WHERE id_test = %d",
            $id_test // Replace with the correct variable holding the id_test
        )
    );
    

// Ensure that you have fetched the correct questions from your database

// Check results
if (!empty($results)) {
    $time_data = [];
    if (!empty($results) && !empty($results[0]->save_specific_time)) {
        $decoded_time_data = json_decode($results[0]->save_specific_time, true); // Chuyển JSON thành mảng
        if (is_array($decoded_time_data)) {
            $time_data = $decoded_time_data; // Gán khi JSON hợp lệ
        }
    }

    
$questions = explode(",", $data['question_choose']);
// Normalize question IDs to handle spaces
$questions = array_map(function($id) {
    return str_replace(' ', '', trim($id)); // Remove spaces and trim
}, $questions);


$new_correct_ans = 0;
$new_incorrrect_ans = 0;
$new_skip_ans = 0;


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
                                    <span class="result-stats-text">'. esc_html($result->resulttest) .'</span>
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
                                        <div class="result-score-text text-score">'. esc_html($result->resulttest) .'</div>
                                        <div class="result-score-sub"><span>Overall</span></div>

                                    </div>
                                </div>
                                
                            </div>
                            <br>
                            
                        </div>
                    </div>
                </div>
        ';

        echo'<button class="button-10" id ="share-button">Chia sẻ bài làm</button>';
        echo'<button class="button-10" onclick = "openRemarkTest()" >Chấm lại </button>';


        echo '<div>
        <canvas id="myLineChart" width="200" height="100"></canvas>

        </div>';








        echo '<p>Username: ' . esc_html($result->username) . '</p>';
        echo '<p>Ngày làm đề: ' . esc_html($result->dateform) . '</p>';
        echo '<p>Tên đề thi: ' . esc_html($result->testname) . '</p>';
        echo '<p>Thời gian làm bài: ' . esc_html($result->timedotest) . '</p>';
        echo '<p>ID data: ' . esc_html($result->testsavenumber) . '</p>';
        echo '<p>Result Test: ' . esc_html($result->resulttest) . '</p>';
        if ($review) {
            echo '<p>Type Test: ' . esc_html($review->test_type) . '</p>';
            echo '<p>Resource: ' . esc_html($review->book) . '</p>';
        } else {
            echo '<p>Type Test: N/A</p>';
            echo '<p>Resource: N/A</p>';
        }
        
        // Start the table for answers
        echo '<table border="1">';
        echo '<tr>
        
        <th>Question</th>
        <th>ID Question</th>
        <th>User Answer</th>
        <th>Correct Answer</th>
        <th>Result</th>
            <th>Time (seconds)</th>

        <th>Action</th>
        </tr>';

        // Split the user answers based on the string format
        // Example format: "Kết quả lưu:Question 1. AQuestion 2. BQuestion 3. C..."
        $user_answer_string = $result->useranswer;
        
        // Remove the prefix and split based on "Question"
        $answers_array = preg_split('/Question /', $user_answer_string);
        array_shift($answers_array); // Remove first empty element if present

        // Counter for question numbering
        $question_number = 1;
    

        // Loop through all question IDs in the questions array
        foreach ($questions as $question_id) {
            // Query for each question to get detailed data
            $sql_question = "SELECT explanation, id_question, type_question, question_content, answer_1, answer_2, answer_3, answer_4, correct_answer FROM digital_sat_question_bank_verbal WHERE id_question = ?";
            $stmt_question = $conn->prepare($sql_question);
            $stmt_question->bind_param("s", $question_id);
            $stmt_question->execute();
            $result_question = $stmt_question->get_result();

            if ($result_question->num_rows > 0) {
                $question_data = $result_question->fetch_assoc();
                error_log(print_r($question_data, true)); // Check the output in your PHP error log

                // Determine the correct answer text
                $correct_answer_text = '';
                switch ($question_data['correct_answer']) {
                    case 'answer_1':
                        $correct_answer_text = 'A';
                        break;
                    case 'answer_2':
                        $correct_answer_text = 'B';
                        break;
                    case 'answer_3':
                        $correct_answer_text = 'C';
                        break;
                    case 'answer_4':
                        $correct_answer_text = 'D';
                        break;
                }

                // User's answer for the current question
                // Extract user's answer from the answers_array based on question_number
                $user_answer = isset($answers_array[$question_number - 1]) ? trim($answers_array[$question_number - 1]) : '';

                // Remove anything after the first space if the answer includes it
                if (strpos($user_answer, '.') !== false) {
                    $user_answer = substr($user_answer, strpos($user_answer, '.') + 1);
                }
                $user_answer = trim($user_answer); // Trim any extra spaces

          
                // Determine if the answer is correct or incorrect
                if ($user_answer  == ""){
                    $result_status = "Not answered";
                    $color_class = 'grey-text';
                    $new_skip_ans++;
                }
                else if ($user_answer == $correct_answer_text){
                    $result_status = 'Correct' ;
                    $color_class =  'green-text' ;
                    $new_correct_ans++;
                }
                else
                {
                    $result_status = 'Incorrect';
                    $color_class =  'red-text';
                    $new_incorrrect_ans++;
                }
                $time_spent = 'N/A';
                foreach ($time_data as $time_entry) {
                    if ($time_entry['question'] == $question_number) {
                        $time_spent = $time_entry['time'];
                        break;
                    }
                }
                // Display each answer in the table
                echo '<tr>';
                echo '<td>Question ' . $question_number . '</td>';
                echo '<td>'.  $question_data['id_question'].  '</td>'; 
                echo '<td>' . esc_html($user_answer) . '</td>';
                echo '<td>' . $correct_answer_text . '</td>';
                echo '<td class="' . $color_class . '">' . $result_status . '</td>'; // Apply color class
                echo '<td>' . esc_html($time_spent) . '</td>'; // Cột thời gian

                $explanation = isset($question_data['explanation']) ? htmlspecialchars($question_data['explanation'], ENT_QUOTES, 'UTF-8') : 'Explanation not available';

                // Use $explanation safely in the JavaScript function
                echo '<td><a onclick="openDetailExplanation(\'' . esc_js($question_number) . '\',  \'' . esc_js($question_data['id_question']) . '\', \'' . esc_js($question_data['question_content']) . '\', \'' . esc_js($question_data['answer_1']) . '\', \'' . esc_js($question_data['answer_2']) . '\', \'' . esc_js($question_data['answer_3']) . '\', \'' . esc_js($question_data['answer_4']) . '\', \'' . esc_js($correct_answer_text) . '\', \'' . esc_js($user_answer) . '\', `' . htmlspecialchars($question_data['explanation'], ENT_QUOTES, 'UTF-8') . '`)" id="quick-view-' . $question_data['id_question'] . '">Review</a></td>';
                                
                echo '</tr>';

                $question_number++;
            }
        }

        // End the table
        echo '</table>';
    }
} else {
    // If no results with testsavenumber
    echo '<p>Không có kết quả tìm thấy cho đề thi này.</p>';
}
?>
<body>


<div id="explanation_popup" class="popup">
    <div class="popup-content">
        <button class="close" onclick="closeDetailExplanation()">&times;</button>

        <div class="container-popup">
            <!-- Cột bên trái -->
            <div class="left-popup"> 
                <div id="popup_question_id">Question ID: 123</div>
                <div id="popup_question_content">This is the question content.</div>
            </div>    

            <!-- Cột bên phải -->
            <div class="right-popup">
                <div id="popup_question_answer">Answer: A</div>
                <div id="popup_question_correct_answer">Correct Answer: B</div>
                <div id="popup_question_user_answer">Your Answer: C</div>
                <div id="popup_question_explanation">Explanation: This is the explanation.</div>
            </div>
        </div>
    </div>
</div>


<div id="remark_popup" class="popup">
<div class="popup-content">
        <span class="close" onclick="closeRemarkTest()">&times;</span>
        <i>Một số đề thi sau khi được sửa lại đáp án nhưng vẫn chưa được cập nhập ở bài làm của bạn<br> Bạn có thể sử dụng nút ở dưới để chấm bài cũ và nhận điểm mới !</i>
        <br>
        <button onclick = "remarkTest()"id = "remarkTest" class = "remarkTestBtn">Chấm lại bài</button>
        <div id = "remarkArea">
            <div id = "remarkPoint" style = "display:none">
                <p id ="test-type">Loại đề</p>
                <div id ="old-res">
                    <b>Lưu đáp án cũ</b>
                    <p id = "old-correct-ans">Số câu đúng cũ: </p> 
                    <p id = "old-incorrect-ans">Số câu sai cũ: </p>
                    <p id = "old-skip-ans">Số câu bỏ qua cũ: </p>
                    <p id = "old-overall">Tổng điểm Overall cũ: </p>
                </div>

                <div id ="new-res">
                    <b>Lưu đáp án mới</b>
                    <p id = "new-correct-ans">Số câu đúng mới: </p>
                    <p id = "new-incorrect-ans">Số câu sai mới: </p>
                    <p id = "new-skip-ans">Số câu bỏ qua mói: </p>
                    <p id = "new-overall">Tổng điểm Overall mới: </p>
                </div>
                <div id ="track_change_ans">
                    <p id = "track_change_note"></p>
                    <button id ="saveNewBtn" style = "display:none">Lưu kết quả mới</button>
                </div>
            </div>
            <div id ="warningRemark"></div>
        </div>
    </div>
</div>



</body>


<script>
    // Lấy phần tử canvas
    const ctx = document.getElementById('myLineChart').getContext('2d');

    // Dữ liệu và cấu hình biểu đồ
    const myLineChart = new Chart(ctx, {
        type: 'line', // Loại biểu đồ
        data: {
            labels: ['January', 'February', 'March', 'April', 'May', 'June'], // Nhãn trục X
            datasets: [
                {
                    label: 'Dataset 1', // Nhãn của dữ liệu
                    data: [10, 20, 15, 25, 30, 40], // Dữ liệu trên trục Y
                    borderColor: 'rgba(75, 192, 192, 1)', // Màu đường
                    backgroundColor: 'rgba(75, 192, 192, 0.2)', // Màu nền bên dưới đường
                    tension: 0.4, // Độ cong của đường (0 là thẳng, 1 là cong tối đa)
                },
                {
                    label: 'Dataset 2',
                    data: [5, 15, 10, 20, 25, 35],
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    tension: 0.4,
                },
            ],
        },
        options: {
            responsive: true, // Đáp ứng kích thước
            plugins: {
                legend: {
                    display: true, // Hiển thị chú thích
                },
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Months', // Tiêu đề trục X
                    },
                },
                y: {
                    title: {
                        display: true,
                        text: 'Values', // Tiêu đề trục Y
                    },
                    beginAtZero: true, // Bắt đầu từ 0
                },
            },
        },
    });






//document.getElementById('quick-view').addEventListener('click', openChangeDisplayPopup);



// Close the draft popup when the close button is clicked
function closeDetailExplanation() {
    document.getElementById('explanation_popup').style.display = 'none';
}


function openDetailExplanation(questionNumber, questionId, questionContent, answer1, answer2, answer3, answer4, correctAnswer,userAnswer, explanationQuestion) {
    console.log('Question ID:', questionId); // Log the received questionId
    document.getElementById('explanation_popup').style.display = 'block';
    document.getElementById('popup_question_id').innerHTML = '<b> Question '+ questionNumber + ' - ID: ' + questionId+'</b>'; // Set the ID in the popup
    document.getElementById('popup_question_content').innerHTML = questionContent; // Set the question content in the popup
    document.getElementById('popup_question_answer').innerHTML = `A. ${answer1}<br>B. ${answer2}<br>C. ${answer3}<br>D. ${answer4}`; // Set the question answers in the popup
    document.getElementById('popup_question_correct_answer').innerHTML = `<b style = "color:green"> Correct Answer: ${correctAnswer}</b>`; // Use correctAnswer parameter
    document.getElementById('popup_question_user_answer').innerHTML = `Your answer: ${userAnswer}`; // Use correctAnswer parameter
    document.getElementById('popup_question_explanation').innerHTML = `Explanation:  ${explanationQuestion} `; // This will render HTML tags properly


}

function closeRemarkTest() {
    document.getElementById('remark_popup').style.display = 'none';
}

function openRemarkTest(){
    document.getElementById('remark_popup').style.display = 'block';

}
let Alreadyremark = false;
function remarkTest(){
    if (!Alreadyremark){
        const oldCorrectNumber = <?php echo json_encode(esc_html($result->correct_number)); ?>;
        const oldIncorrectNumber = <?php echo json_encode(esc_html($result->incorrect_number)); ?>;
        const oldSkipNumber = <?php echo json_encode(esc_html($result->skip_number)); ?>;
        const oldResultTest = <?php echo json_encode(esc_html($result->correct_number)); ?>;

        const newCorrectAnsNumber = <?php echo json_encode($new_correct_ans); ?>;
        const newIncorrectAnsNumber = <?php echo json_encode($new_incorrrect_ans); ?>;
        const newSkipAnsNumber = <?php echo json_encode($new_skip_ans); ?>;

        // Update the content with the correct number
        document.getElementById("old-correct-ans").innerText += oldCorrectNumber;
        document.getElementById("old-incorrect-ans").innerText += oldIncorrectNumber;
        document.getElementById("old-skip-ans").innerText += oldSkipNumber;


        document.getElementById("new-correct-ans").innerText += newCorrectAnsNumber;
        document.getElementById("new-incorrect-ans").innerText += newIncorrectAnsNumber;
        document.getElementById("new-skip-ans").innerText += newSkipAnsNumber;
        if (oldCorrectNumber != newCorrectAnsNumber || oldIncorrectNumber != newIncorrectAnsNumber || oldSkipNumber != newSkipAnsNumber)
        {
            document.getElementById("track_change_note").innerHTML = `Đáp án có sự thay đổi, hãy ấn vào nút Lưu kết quả để cập nhập`;
            document.getElementById("saveNewBtn").style.display = 'block';
        }
        else{
            document.getElementById("track_change_note").innerHTML = `Đáp án vẫn giữ nguyên, không có sự thay đổi nào !!!`;

        }

        // Display the remarkPoint section
        document.getElementById("remarkPoint").style.display = 'block';
        Alreadyremark = true;
    }
    else{
        document.getElementById("warningRemark").innerHTML = "<i>Bạn đã chấm lại kết quả thêm yêu cầu mới nhất và đã lưu vào hệ thống. Nếu phát hiện kết quả có lỗi hãy report !</i>"
    }
}

document.getElementById("saveNewBtn").addEventListener("click", function() {
    const newCorrectAnsNumber = <?php echo json_encode($new_correct_ans); ?>;
    const newIncorrectAnsNumber = <?php echo json_encode($new_incorrrect_ans); ?>;
    const newSkipAnsNumber = <?php echo json_encode($new_skip_ans); ?>;

    // Gửi dữ liệu qua AJAX để cập nhật cơ sở dữ liệu
    fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            action: 'update_digital_sat_results',
            testsavenumber: <?php echo json_encode($testsavenumber); ?>,
            correct_number: newCorrectAnsNumber,
            incorrect_number: newIncorrectAnsNumber,
            skip_number: newSkipAnsNumber
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Làm mới trang nếu cập nhật thành công
            alert('Cập nhập kết quả thành công!');
            location.reload();
        } else {
            alert('Có lỗi xảy ra: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra trong khi cập nhật!');
    });
});



</script>

<?php





get_footer();