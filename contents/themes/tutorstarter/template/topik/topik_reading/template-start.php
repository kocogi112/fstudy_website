<?php
/*
 * Template Name: Start Doing Test Topik Reading
 * Template Post Type: Topik Reading
 
 */


if (is_user_logged_in()) {
    $post_id = get_the_ID();
    $user_id = get_current_user_id();

    $custom_number = get_query_var('id_test');
    $current_user = wp_get_current_user();
    $current_username = $current_user->user_login;
    $username = $current_username;

  // Database credentials
  $servername = DB_HOST;
  $username = DB_USER;
  $password = DB_PASSWORD;
  $dbname = DB_NAME;

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


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

if (!$custom_number) {
    $custom_number = '00'; // Set id_test to "00" if invalid
}


 // Create result_id
 $result_id = $hour . $minute . $second . $custom_number . $user_id . $random_number;
 $site_url = get_site_url();

 echo "<script> 
        var resultId = '" . $result_id . "';
       
        var siteUrl = '" .
        $site_url .
        "';
        var id_test = '" .
        $id_test .
        "';


        console.log('Result ID: ' + resultId);
    </script>";




$sql_test = "SELECT * FROM topik_reading_test_list WHERE id_test = ?";


// Query to fetch token details for the current username
$sql2 = "SELECT token, token_use_history 
         FROM user_token 
         WHERE username = ?";



// Use prepared statements to execute the query
$stmt_test = $conn->prepare($sql_test);
$id_test = $custom_number;
$stmt_test->bind_param("s", $id_test);
$stmt_test->execute();

// Get the result
$result_test = $stmt_test->get_result();
if ($result_test->num_rows > 0) {
    $data = $result_test->fetch_assoc();

    $testname = $data['testname']; // Fetch the testname field
    $testcode = $data['testcode']; // Fetch the testname field
    $correct_answer = $data['correct_answer']; // Fetch the testname field
    $test_type = $data['test_type']; // Fetch the testname field
    $time = $data['time']; // Fetch the testname field
    $token_need = $data['token_need'];
    $time_allow = $data['time_allow'];
    $permissive_management = $data['permissive_management'];


    add_filter('document_title_parts', function ($title) use ($testname) {
      $title['title'] = $testname; // Use the $testname variable from the outer scope
      return $title;
  });
  

get_header(); // Gọi phần đầu trang (header.php)



$stmt2 = $conn->prepare($sql2);
if (!$stmt2) {
    die("Error preparing statement 2: " . $conn->error);
}

$stmt2->bind_param("s", $current_username);
$stmt2->execute();
$result2 = $stmt2->get_result();

if ($result2->num_rows > 0) {
    $token_data = $result2->fetch_assoc();
    $token = $token_data['token'];
    $token_use_history = $token_data['token_use_history'];

    echo "<script>console.log('Token: $token, Token Use History: $token_use_history, Mày tên: $current_username');</script>";
   

} else {
    echo "Lỗi đề thi";
    
}


  
        $permissiveManagement = json_decode($permissive_management, true);
        
        // Chuyển mảng PHP thành JSON string để có thể in trong console.log
        echo "<script> 
                console.log('$permissive_management');
            </script>";
        
        
        $foundUser = null;
        if (!empty($permissiveManagement)) {
            foreach ($permissiveManagement as $entry) {
                if ($entry['username'] === $current_username) {
                    $foundUser = $entry;
                    break;
                }
            }
        }
    
        $premium_test = "False"; // Default value
        if ($foundUser != null && $foundUser['time_left'] > 0 || $token_need == 0) {
            if ($token_need > 0) {
                $premium_test = "True";
            }
        
        
            echo '<script>
            let premium_test = "' . $premium_test . '";
            let token_need = "' . $token_need . '";
            let change_content = "' . $testname . '";
            let time_left = "' . (isset($foundUser['time_left']) ? $foundUser['time_left'] : 10) . '";
        </script>';
        





// Đóng kết nối
$conn->close();

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Navigation</title>
    <style>

        #quiz-container1 {
            flex: 3;
            padding: 20px;
            min-height: 100vh;
        }

        #sidebar1 {
            flex: 1;
            padding: 20px;
            border-left: 2px solid #ccc;
            height: auto;
            overflow-y: auto;
        }
        .question-wrapper, .context-wrapper {
            display: none;
        }
        .active {
            display: block;
        }
        .box-answer {
            width: calc(20% - 10px);
            height: 40px;
            border: 1px solid black;
            margin-bottom: 10px;
            display: inline-block;
            box-sizing: border-box;
            padding: 15px;
        }
        .selected {
            background-color: lightgreen !important;
        }
        .navigation1 {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            width: calc(100% - 40px);
        }
        .nav-btn {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }
        .nav-btn:disabled {
            background-color: #cccccc;
            cursor: not-allowed;
        }
        #logBtn {
            margin-top: 10px;
            padding: 10px;
            width: 100%;
            background-color: blue;
            color: white;
            border: none;
            cursor: pointer;
        }
        #timer {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #333;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            font-size: 18px;
            font-weight: bold;
            z-index: 1000;
        }
        .time-warning {
            background-color: #ff9800 !important;
        }
        .time-critical {
            background-color: #f44336 !important;
            animation: blink 1s infinite;
        }
        .question-content{
            height: 300px;
        }
        @keyframes blink {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }
        .container-content {
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            min-height: 100vh;
        }

        .header-content {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            padding: 15px;
            background-color: #f8f9fa; /* Màu nền nhẹ */
            border-bottom: 2px solid #ddd; /* Đường kẻ ngăn cách */
        }

    </style>
</head>
<body>
    <div id="timer"></div>

    <div class="container-content">
        <div id="quiz-container1">
            <div class = "header-content"><?php echo $testname ?></div>

            <?php echo html_entity_decode($testcode); ?> <!-- Hiển thị HTML từ database mà không escape -->
            
            <div class="navigation1">
                <button class="nav-btn" id="prevBtn" disabled>Previous</button>
                <button class="nav-btn" id="nextBtn">Next</button>
            </div>
        </div>

        <div id="sidebar1">
            <h3>Answers</h3>
            <div id="boxanswers"></div>
            <button id="logBtn">Submit Answers</button>
        </div>
    </div>

    <script>
        
        document.addEventListener("DOMContentLoaded", function () {
            const questions = document.querySelectorAll(".question-wrapper");
            const contexts = document.querySelectorAll(".context-wrapper");
            const groupQuestion = document.querySelectorAll(".question-group-wrapper");

            const sidebar = document.getElementById("boxanswers");
            const prevBtn = document.getElementById("prevBtn");
            const nextBtn = document.getElementById("nextBtn");
            const logBtn = document.getElementById("logBtn");
            const timerElement = document.getElementById("timer");
            let currentQuestion = 0;
            
            // Cài đặt timer 60 phút
            let timeLeft = <?php echo intval($time); ?> * 60; // Chắc chắn rằng $time là số nguyên
            let timerInterval;
            
            function startTimer() {
                timerInterval = setInterval(function() {
                    timeLeft--;
                    
                    // Cập nhật hiển thị timer
                    const minutes = Math.floor(timeLeft / 60);
                    const seconds = timeLeft % 60;
                    timerElement.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                    
                    // Thay đổi màu sắc khi thời gian sắp hết
                    if (timeLeft <= 300) { // 5 phút cuối
                        timerElement.classList.add("time-critical");
                        timerElement.classList.remove("time-warning");
                    } else if (timeLeft <= 900) { // 15 phút cuối
                        timerElement.classList.add("time-warning");
                        timerElement.classList.remove("time-critical");
                    }
                    
                    // Tự động nộp bài khi hết giờ
                    if (timeLeft <= 0) {
                        clearInterval(timerInterval);
                        submitQuiz();
                    }
                }, 1000);
            }
            
            // Khởi tạo sidebar với các câu hỏi
            questions.forEach((question, index) => {
                let qid = question.getAttribute("data-qid");
                let box = document.createElement("div");
                box.classList.add("box-answer");
                box.setAttribute("data-qid", qid);
                box.setAttribute("data-index", index);
                box.textContent = `${index + 1}`;
                sidebar.appendChild(box);
                
                // Click vào box answer để chuyển đến câu hỏi tương ứng
                box.addEventListener("click", function() {
                    currentQuestion = parseInt(this.getAttribute("data-index"));
                    showQuestion(currentQuestion);
                });
            });
            
            // Xử lý sự kiện khi chọn đáp án
            document.querySelectorAll(".form-check-input").forEach(input => {
                input.addEventListener("change", function () {
                    let qid = this.getAttribute("data-qid");
                    let box = document.querySelector(`.box-answer[data-qid='${qid}']`);
                    box.classList.add("selected");
                });
            });
            
            // Nút Previous
            prevBtn.addEventListener("click", function() {
                if (currentQuestion > 0) {
                    currentQuestion--;
                    showQuestion(currentQuestion);
                }
            });
            
            // Nút Next
            nextBtn.addEventListener("click", function() {
                if (currentQuestion < questions.length - 1) {
                    currentQuestion++;
                    showQuestion(currentQuestion);
                }
            });
            
            // Nút Submit
            logBtn.addEventListener("click", submitQuiz);
            
            // Hiển thị câu hỏi đầu tiên
            showQuestion(currentQuestion);
            
            // Bắt đầu đếm ngược
            startTimer();
            
            // Hàm hiển thị câu hỏi và context tương ứng
            function showQuestion(index) {
                // Ẩn tất cả câu hỏi và context
                questions.forEach(q => q.classList.remove("active"));
                contexts.forEach(c => c.classList.remove("active"));
                groupQuestion.forEach(g => g.classList.remove("active"));
                
                // Hiển thị câu hỏi và context hiện tại
                if (questions[index]) {
                    questions[index].classList.add("active");
                }
                if (contexts[index+1]) {
                    contexts[index+1].classList.add("active");
                }
                if (groupQuestion[index+1]) {
                    groupQuestion[index+1].classList.add("active");
                }
                // Cập nhật trạng thái nút
                prevBtn.disabled = index === 0;
                nextBtn.disabled = index === questions.length - 1;
                
                // Cuộn lên đầu trang
                window.scrollTo(0, 0);
            }
            
            // Hàm nộp bài
            function submitQuiz() {
                clearInterval(timerInterval);
                timerElement.textContent = "00:00";
                
                const results = [];
                const questionBoxes = document.querySelectorAll('.box-answer');
                
                questionBoxes.forEach((box, index) => {
                    const qid = box.getAttribute('data-qid');
                    const questionNumber = index + 1;
                    const selectedAnswer = document.querySelector(`.form-check-input[data-qid="${qid}"]:checked`);
                    
                    if (selectedAnswer) {
                        results.push({
                            question: questionNumber,
                            answer: selectedAnswer.value,
                            status: 'answered'
                        });
                    } else {
                        results.push({
                            question: questionNumber,
                            answer: null,
                            status: 'not answered'
                        });
                    }
                });
                
                // Hiển thị kết quả trong console
                console.log("=== QUIZ RESULTS ===");
                console.log(`Time remaining: ${timerElement.textContent}`);
                console.table(results);
                
                // Hiển thị thông báo
                const answeredCount = results.filter(r => r.status === 'answered').length;
                const totalQuestions = results.length;
                
                // Vô hiệu hóa tất cả input và nút
                document.querySelectorAll(".form-check-input").forEach(input => {
                    input.disabled = true;
                });
                prevBtn.disabled = true;
                nextBtn.disabled = true;
                logBtn.disabled = true;
                
                alert(`TIME'S UP!\nBạn đã trả lời ${answeredCount}/${totalQuestions} câu hỏi.\nKiểm tra console để xem chi tiết.`);
                
                return results;
            }
        });
    </script>

    
</body>

    
</html>

<?php


}
else{
    get_header();
    if (!$foundUser) {
        echo "
        <div class='checkout-modal-overlay'>
            <div class='checkout-modal'>
                <h3>Bạn chưa mua đề thi này</h3>";     
        } 

    else if ($foundUser['time_left'] <= 0) {
        echo "
        <div class='checkout-modal-overlay'>
            <div class='checkout-modal'>
                <h3> Bạn đã từng mua test này nhưng số lượt làm test này đã hết rồi, vui lòng mua thêm token<i class='fa-solid fa-face-sad-tear'></i></h3>";
    }

    echo"
            <p> Bạn đang có: $token token</p>
            <p> Để làm test này bạn cần $token_need token. Bạn sẽ được làm test này $time_allow lần </p>
            <p class = 'info-buy'>Bạn có muốn mua $time_allow lượt làm test này với $token_need không ?</button>
                <div class='button-group'>
                    <button class='process-token' onclick='preProcessToken()'>Mua ngay</button>
                    <button style = 'display:none' class='close-modal'>Hủy</button>
                </div>  
            </div>
        </div>
        
        <script>
    
    function preProcessToken() {
        if ($token < $token_need) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                html: 'Bạn không đủ token để mua test này',
                footer: `<a href='${site_url}/dashboard/buy_token/'>Nạp token vào tài khoản ngay</a>`
            });
        } else {
            console.log(`Allow to next step`);
            jQuery.ajax({
                url: `${site_url}/wp-admin/admin-ajax.php`,
                type: 'POST',
                data: {
                    action: 'update_buy_test',
                    type_transaction: 'paid',
                    table: 'topik_reading_test_list',
                    change_token: '$token_need',
                    payment_gate: 'token',
                    title: 'Renew test $testname with $id_test (TOPIK Reading) with $token_need (Buy $time_allow time do this test)',
                    id_test: id_test
                },
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Mua test thành công!',
                        text: 'Trang sẽ được làm mới sau 2 giây.',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true,
                        willClose: () => location.reload()
                    });
                },
                error: function (error) {
                    console.error('Error updating time_left:', error);
                }
            });
        }
    }
        </script>
        <style>
.checkout-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
}

.checkout-modal {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    width: 400px;
    text-align: center;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.checkout-modal h3 {
    font-size: 18px;
    color: #333;
}

.checkout-modal p {
    margin: 10px 0;
    color: #555;
}

.checkout-modal .button-group {
    margin-top: 20px;
}

.process-token {
    background-color: #007bff;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    margin-right: 10px;
    font-size: 14px;
}

.process-token:hover {
    background-color: #0056b3;
}

.close-modal {
    background-color: #ccc;
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 14px;
}

.close-modal:hover {
    background-color: #aaa;
}
</style>

<script>
    document.querySelector('.close-modal')?.addEventListener('click', function() {
        document.querySelector('.checkout-modal-overlay').style.display = 'none';
    });
</script>
        ";
        } 
    }
    
 else {
        get_header();
            echo "<p>Không tìm thấy đề thi.</p>";
            exit();
    }

} else {
    get_header();
    echo "<p>Please log in to submit your answer.</p>";

}