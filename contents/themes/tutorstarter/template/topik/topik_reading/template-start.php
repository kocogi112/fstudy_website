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
        















// Output the quizData as JavaScript
echo '<script type="text/javascript">
const questions = [
    ' . $testcode . '
];


console.log(questions);
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
        body {
            display: flex;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        #quiz-container {
            flex: 3;
            padding: 20px;
            position: relative;
            min-height: 100vh;
        }
        #sidebar {
            flex: 1;
            padding: 20px;
            border-left: 2px solid #ccc;
            height: 100vh;
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
        .navigation {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            position: absolute;
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
        

    </style>
</head>
<body>
    <div id="timer">60:00</div>

    <div id="quiz-container">

        <div class="test-questions-wrapper">
                            
                            
    
    

    
        
        
    

    

    

    

        
            


            <div class="context-wrapper " data-qid="207534" >
                
                
                
                
                
                
                
                
            </div>
            
                    
            
                    <div class="question-wrapper" data-qid="207534" id="question-wrapper-207534" style="">
                        
                        <div class="question-number
                            " data-qid="207534" data-markable="true">
                            <strong>1</strong>
                        </div>
                        
            
                        <div class="question-content text-highlightable">
                            
                            <div class="question-text ">
                                <div><div>무엇에 대한 이야기입니까? 알맞은 것을 고르 십시오.</div><hr><div>저는 김민수입니다. 이 사람은 제임스입니다.</div></div>
                            </div>
                            
                            
                            
            
                            <div class="question-answers">
                                
                                    
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207534" type="radio" name="question-207534" id="question-207534-A" value="A">
                <label class="form-check-label" for="question-207534-A">
                    A. 시간
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207534" type="radio" name="question-207534" id="question-207534-B" value="B">
                <label class="form-check-label" for="question-207534-B">
                    B. 장소
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207534" type="radio" name="question-207534" id="question-207534-C" value="C">
                <label class="form-check-label" for="question-207534-C">
                    C. 이름
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207534" type="radio" name="question-207534" id="question-207534-D" value="D">
                <label class="form-check-label" for="question-207534-D">
                    D. 주말
                </label>
            </div>
            
                                    
                                    
                                
                            </div>
                            
                            
            
                            
                        </div>
            
                        
                    </div>
                    
            
                
            
                
            
                    
                
            
                                        
                                        
                
                
            
                
                    
                    
                
            
                
            
                
            
                
            
                    
                        
            
            
            <div class="context-wrapper ">
                
                
                
                
                
                
                
                
            </div>
            
                    
            
                    <div class="question-wrapper" data-qid="207535" id="question-wrapper-207535" style="">
                        
                        <div class="question-number
                            " data-qid="207535" data-markable="true">
                            <strong>2</strong>
                        </div>
                        
            
                        <div class="question-content text-highlightable">
                            
                            <div class="question-text ">
                                <div><div>무엇에 대한 이야기입니까? 알맞은 것을 고르 십시오.</div><hr><div>불고기를 먹습니다. 맛있습니다.</div></div>
                            </div>
                            
                            
                            
            
                            <div class="question-answers">
                                
                                    
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207535" type="radio" name="question-207535" id="question-207535-A" value="A">
                <label class="form-check-label" for="question-207535-A">
                    A. 쇼핑
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207535" type="radio" name="question-207535" id="question-207535-B" value="B">
                <label class="form-check-label" for="question-207535-B">
                    B. 사람
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207535" type="radio" name="question-207535" id="question-207535-C" value="C">
                <label class="form-check-label" for="question-207535-C">
                    C. 노래
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207535" type="radio" name="question-207535" id="question-207535-D" value="D">
                <label class="form-check-label" for="question-207535-D">
                    D. 음식
                </label>
            </div>
            
                                    
                                    
                                
                            </div>
                            
                            
            
                            
                        </div>
            
                        
                    </div>
                    
            
                
            
                
            
                    
                
            
                                        
                                        
                
                
            
                
                    
                    
                
            
                
            
                
            
                
            
                    
                        
            
            
            <div class="context-wrapper ">
                
                
                
                
                
                
                
                
            </div>
            
                    
            
                    <div class="question-wrapper" data-qid="207536" id="question-wrapper-207536" style="">
                        
                        <div class="question-number
                            " data-qid="207536" data-markable="true">
                            <strong>3</strong>
                        </div>
                        
            
                        <div class="question-content text-highlightable">
                            
                            <div class="question-text ">
                                <div><div>무엇에 대한 이야기입니까? 알맞은 것을 고르 십시오.</div><hr><div>선생님을 만납니다. 공부를 합니다.</div></div>
                            </div>
                            
                            
                            
            
                            <div class="question-answers">
                                
                                    
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207536" type="radio" name="question-207536" id="question-207536-A" value="A">
                <label class="form-check-label" for="question-207536-A">
                    A. 학교
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207536" type="radio" name="question-207536" id="question-207536-B" value="B">
                <label class="form-check-label" for="question-207536-B">
                    B. 요일
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207536" type="radio" name="question-207536" id="question-207536-C" value="C">
                <label class="form-check-label" for="question-207536-C">
                    C. 취미
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207536" type="radio" name="question-207536" id="question-207536-D" value="D">
                <label class="form-check-label" for="question-207536-D">
                    D. 날짜
                </label>
            </div>
            
                                    
                                    
                                
                            </div>
                            
                            
            
                            
                        </div>
            
                        
                    </div>
                    
            
                
            
                
            
                    
                
            
                                        
                                        
                
                
            
                
                    
                    
                
            
                
            
                
            
                
            
                    
                        
            
            
            <div class="context-wrapper ">
                
                
                
                
                
                
                
                
            </div>
            
                    
            
                    <div class="question-wrapper" data-qid="207537" id="question-wrapper-207537">
                        
                        <div class="question-number
                            " data-qid="207537" data-markable="true">
                            <strong>4</strong>
                        </div>
                        
            
                        <div class="question-content text-highlightable">
                            
                            <div class="question-text ">
                                <div><div>(__)에 들어갈 가장 알맞은 것을 고르십시오.</div><hr><div>몇시(__) 옵니까?</div></div>
                            </div>
                            
                            
                            
            
                            <div class="question-answers">
                                
                                    
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207537" type="radio" name="question-207537" id="question-207537-A" value="A">
                <label class="form-check-label" for="question-207537-A">
                    A. 가
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207537" type="radio" name="question-207537" id="question-207537-B" value="B">
                <label class="form-check-label" for="question-207537-B">
                    B. 는
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207537" type="radio" name="question-207537" id="question-207537-C" value="C">
                <label class="form-check-label" for="question-207537-C">
                    C. 를
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207537" type="radio" name="question-207537" id="question-207537-D" value="D">
                <label class="form-check-label" for="question-207537-D">
                    D. 에
                </label>
            </div>
            
                                    
                                    
                                
                            </div>
                            
                            
            
                            
                        </div>
            
                        
                    </div>
                    
            
                
            
                
            
                    
                
            
                                        
                                        
                
                
            
                
                    
                    
                
            
                
            
                
            
                
            
                    
                        
            
            
            <div class="context-wrapper ">
                
                
                
                
                
                
                
                
            </div>
            
                    
            
                    <div class="question-wrapper" data-qid="207538" id="question-wrapper-207538">
                        
                        <div class="question-number
                            " data-qid="207538" data-markable="true">
                            <strong>5</strong>
                        </div>
                        
            
                        <div class="question-content text-highlightable">
                            
                            <div class="question-text ">
                                <div><div>(__)에 들어갈 가장 알맞은 것을 고르십시오.</div><hr><div>(__)에 갑니다. 우유를 삽니다.</div></div>
                            </div>
                            
                            
                            
            
                            <div class="question-answers">
                                
                                    
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207538" type="radio" name="question-207538" id="question-207538-A" value="A">
                <label class="form-check-label" for="question-207538-A">
                    A. 가게
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207538" type="radio" name="question-207538" id="question-207538-B" value="B">
                <label class="form-check-label" for="question-207538-B">
                    B. 교실
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207538" type="radio" name="question-207538" id="question-207538-C" value="C">
                <label class="form-check-label" for="question-207538-C">
                    C. 은행
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207538" type="radio" name="question-207538" id="question-207538-D" value="D">
                <label class="form-check-label" for="question-207538-D">
                    D. 서점
                </label>
            </div>
            
                                    
                                    
                                
                            </div>
                            
                            
            
                            
                        </div>
            
                        
                    </div>
                    
            
                
            
                
            
                    
                
            
                                        
                                        
                
                
            
                
                    
                    
                
            
                
            
                
            
                
            
                    
                        
            
            
            <div class="context-wrapper ">
                
                
                
                
                
                
                
                
            </div>
            
                    
            
                    <div class="question-wrapper" data-qid="207539" id="question-wrapper-207539">
                        
                        <div class="question-number
                            " data-qid="207539" data-markable="true">
                            <strong>6</strong>
                        </div>
                        
            
                        <div class="question-content text-highlightable">
                            
                            <div class="question-text ">
                                <div><div>(__)에 들어갈 가장 알맞은 것을 고르십시오.</div><hr><div>저는 한국어 선생님입니다. 한국어를 (__).</div></div>
                            </div>
                            
                            
                            
            
                            <div class="question-answers">
                                
                                    
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207539" type="radio" name="question-207539" id="question-207539-A" value="A">
                <label class="form-check-label" for="question-207539-A">
                    A. 줍니다
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207539" type="radio" name="question-207539" id="question-207539-B" value="B">
                <label class="form-check-label" for="question-207539-B">
                    B. 모릅니다
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207539" type="radio" name="question-207539" id="question-207539-C" value="C">
                <label class="form-check-label" for="question-207539-C">
                    C. 가르칩니다
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207539" type="radio" name="question-207539" id="question-207539-D" value="D">
                <label class="form-check-label" for="question-207539-D">
                    D. 일어납니다
                </label>
            </div>
            
                                    
                                    
                                
                            </div>
                            
                            
            
                            
                        </div>
            
                        
                    </div>
                    
            
                
            
                
            
                    
                
            
                                        
                                        
                
                
            
                
                    
                    
                
            
                
            
                
            
                
            
                    
                        
            
            
            <div class="context-wrapper ">
                
                
                
                
                
                
                
                
            </div>
            
                    
            
                    <div class="question-wrapper" data-qid="207540" id="question-wrapper-207540">
                        
                        <div class="question-number
                            " data-qid="207540" data-markable="true">
                            <strong>7</strong>
                        </div>
                        
            
                        <div class="question-content text-highlightable">
                            
                            <div class="question-text ">
                                <div><div>(__)에 들어갈 가장 알맞은 것을 고르십시오.</div><hr><div>요즘 일이 (__). 바쁩니다.</div></div>
                            </div>
                            
                            
                            
            
                            <div class="question-answers">
                                
                                    
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207540" type="radio" name="question-207540" id="question-207540-A" value="A">
                <label class="form-check-label" for="question-207540-A">
                    A. 비쌉니다
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207540" type="radio" name="question-207540" id="question-207540-B" value="B">
                <label class="form-check-label" for="question-207540-B">
                    B. 작습니다
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207540" type="radio" name="question-207540" id="question-207540-C" value="C">
                <label class="form-check-label" for="question-207540-C">
                    C. 많습니다
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207540" type="radio" name="question-207540" id="question-207540-D" value="D">
                <label class="form-check-label" for="question-207540-D">
                    D. 나쁩니다
                </label>
            </div>
            
                                    
                                    
                                
                            </div>
                            
                            
            
                            
                        </div>
            
                        
                    </div>
                    
            
                
            
                
            
                    
                
            
                                        
                                        
                
                
            
                
                    
                    
                
            
                
            
                
            
                
            
                    
                        
            
            
            <div class="context-wrapper ">
                
                
                
                
                
                
                
                
            </div>
            
                    
            
                    <div class="question-wrapper" data-qid="207541" id="question-wrapper-207541">
                        
                        <div class="question-number
                            " data-qid="207541" data-markable="true">
                            <strong>8</strong>
                        </div>
                        
            
                        <div class="question-content text-highlightable">
                            
                            <div class="question-text ">
                                <div><div>(__)에 들어갈 가장 알맞은 것을 고르십시오.</div><hr><div>산을 좋아합니다. 그래서 등산을 (__) 합니다.</div></div>
                            </div>
                            
                            
                            
            
                            <div class="question-answers">
                                
                                    
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207541" type="radio" name="question-207541" id="question-207541-A" value="A">
                <label class="form-check-label" for="question-207541-A">
                    A. 자주
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207541" type="radio" name="question-207541" id="question-207541-B" value="B">
                <label class="form-check-label" for="question-207541-B">
                    B. 제일
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207541" type="radio" name="question-207541" id="question-207541-C" value="C">
                <label class="form-check-label" for="question-207541-C">
                    C. 아주
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207541" type="radio" name="question-207541" id="question-207541-D" value="D">
                <label class="form-check-label" for="question-207541-D">
                    D. 아까
                </label>
            </div>
            
                                    
                                    
                                
                            </div>
                            
                            
            
                            
                        </div>
            
                        
                    </div>
                    
            
                
            
                
            
                    
                
            
                                        
                                        
                
                
            
                
                    
                    
                
            
                
            
                
            
                
            
                    
                        
            
            
            <div class="context-wrapper ">
                
                
                
                
                
                
                
                
            </div>
            
                    
            
                    <div class="question-wrapper" data-qid="207542" id="question-wrapper-207542">
                        
                        <div class="question-number
                            " data-qid="207542" data-markable="true">
                            <strong>9</strong>
                        </div>
                        
            
                        <div class="question-content text-highlightable">
                            
                            <div class="question-text ">
                                <div><div>(__)에 들어갈 가장 알맞은 것을 고르십시오.</div><hr><div>머리가 깁니다. 그래서 (__) 싶습니다.</div></div>
                            </div>
                            
                            
                            
            
                            <div class="question-answers">
                                
                                    
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207542" type="radio" name="question-207542" id="question-207542-A" value="A">
                <label class="form-check-label" for="question-207542-A">
                    A. 자르고
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207542" type="radio" name="question-207542" id="question-207542-B" value="B">
                <label class="form-check-label" for="question-207542-B">
                    B. 나오고
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207542" type="radio" name="question-207542" id="question-207542-C" value="C">
                <label class="form-check-label" for="question-207542-C">
                    C. 가지고
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207542" type="radio" name="question-207542" id="question-207542-D" value="D">
                <label class="form-check-label" for="question-207542-D">
                    D. 마시고
                </label>
            </div>
            
                                    
                                    
                                
                            </div>
                            
                            
            
                            
                        </div>
            
                        
                    </div>
                    
            
                
            
                
            
                    
                
            
                                        
                                        
                
                
            
                
                    
                    
                
            
                
            
                
            
                
            
                    
                        
            
            
            <div class="context-wrapper ">
                
                
                
                
                
                
                <div class="context-content text-highlightable">
                    <div><p><img src="https://s4-media1.study4.com/media/topik_tests/img/33_10.webp"></p></div>
                </div>
                
                
                
            </div>
            
                    
            
                    <div class="question-wrapper" data-qid="207543" id="question-wrapper-207543">
                        
                        <div class="question-number
                            " data-qid="207543" data-markable="true">
                            <strong>10</strong>
                        </div>
                        
            
                        <div class="question-content text-highlightable">
                            
                            <div class="question-text ">
                                <div><div>다음을 읽고 맞지 않는 것을 고르십시오.</div><hr></div>
                            </div>
                            
                            
                            
            
                            <div class="question-answers">
                                
                                    
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207543" type="radio" name="question-207543" id="question-207543-A" value="A">
                <label class="form-check-label" for="question-207543-A">
                    A. 이 컴퓨터는 십만 원입니다.
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207543" type="radio" name="question-207543" id="question-207543-B" value="B">
                <label class="form-check-label" for="question-207543-B">
                    B. 이 컴퓨터를 1년 동안 썼습니다.
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207543" type="radio" name="question-207543" id="question-207543-C" value="C">
                <label class="form-check-label" for="question-207543-C">
                    C. 이 사람은 컴퓨터를 받고 싶습니다.
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207543" type="radio" name="question-207543" id="question-207543-D" value="D">
                <label class="form-check-label" for="question-207543-D">
                    D. 컴퓨터를 사고 싶으면 이메일로 연락합니다.
                </label>
            </div>
            
                                    
                                    
                                
                            </div>
                            
                            
            
                            
                        </div>
            
                        
                    </div>
                    
            
                
            
                
            
                    
                
            
                                        
                                        
                
                
            
                
                    
                    
                
            
                
            
                
            
                
            
                    
                        
            
            
            <div class="context-wrapper ">
                
                
                
                
                
                
                <div class="context-content text-highlightable">
                    <div><p><img src="https://s4-media1.study4.com/media/topik_tests/img/33_11.webp"></p></div>
                </div>
                
                
                
            </div>
            
                    
            
                    <div class="question-wrapper" data-qid="207544" id="question-wrapper-207544">
                        
                        <div class="question-number
                            " data-qid="207544" data-markable="true">
                            <strong>11</strong>
                        </div>
                        
            
                        <div class="question-content text-highlightable">
                            
                            <div class="question-text ">
                                <div><div>다음을 읽고 맞지 않는 것을 고르십시오.</div><hr></div>
                            </div>
                            
                            
                            
            
                            <div class="question-answers">
                                
                                    
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207544" type="radio" name="question-207544" id="question-207544-A" value="A">
                <label class="form-check-label" for="question-207544-A">
                    A. 지현 씨의 동생이 왔습니다.
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207544" type="radio" name="question-207544" id="question-207544-B" value="B">
                <label class="form-check-label" for="question-207544-B">
                    B. 커피숍은 회사 안에 있습니다.
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207544" type="radio" name="question-207544" id="question-207544-C" value="C">
                <label class="form-check-label" for="question-207544-C">
                    C. 지현 씨는 한 시에 회사에 갑니다.
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207544" type="radio" name="question-207544" id="question-207544-D" value="D">
                <label class="form-check-label" for="question-207544-D">
                    D. 지현 씨는 민수 씨에게 메시지를 썼습니다.
                </label>
            </div>
            
                                    
                                    
                                
                            </div>
                            
                            
            
                            
                        </div>
            
                        
                    </div>
                    
            
                
            
                
            
                    
                
            
                                        
                                        
                
                
            
                
                    
                    
                
            
                
            
                
            
                
            
                    
                        
            
            
            <div class="context-wrapper ">
                
                
                
                
                
                
                <div class="context-content text-highlightable">
                    <div><p><img src="https://s4-media1.study4.com/media/topik_tests/img/33_12.webp"></p></div>
                </div>
                
                
                
            </div>
            
                    
            
                    <div class="question-wrapper" data-qid="207545" id="question-wrapper-207545">
                        
                        <div class="question-number
                            " data-qid="207545" data-markable="true">
                            <strong>12</strong>
                        </div>
                        
            
                        <div class="question-content text-highlightable">
                            
                            <div class="question-text ">
                                <div><div>다음을 읽고 맞지 않는 것을 고르십시오.</div><hr></div>
                            </div>
                            
                            
                            
            
                            <div class="question-answers">
                                
                                    
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207545" type="radio" name="question-207545" id="question-207545-A" value="A">
                <label class="form-check-label" for="question-207545-A">
                    A. 하늘공원에서 음악회를 합니다.
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207545" type="radio" name="question-207545" id="question-207545-B" value="B">
                <label class="form-check-label" for="question-207545-B">
                    B. 토요일마다 음악회가 있습니다.
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207545" type="radio" name="question-207545" id="question-207545-C" value="C">
                <label class="form-check-label" for="question-207545-C">
                    C. 이 음악회는 한 달 동안 합니다.
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207545" type="radio" name="question-207545" id="question-207545-D" value="D">
                <label class="form-check-label" for="question-207545-D">
                    D. 이 음악회는 일곱 시에 시작합니다.
                </label>
            </div>
            
                                    
                                    
                                
                            </div>
                            
                            
            
                            
                        </div>
            
                        
                    </div>
                    
            
                
            
                
            
                    
                
            
                                        
                                        
                
                
            
                
                    
                    
                
            
                
            
                
            
                
            
                    
                        
            
            
            <div class="context-wrapper ">
                
                
                
                
                
                
                
                
            </div>
            
                    
            
                    <div class="question-wrapper" data-qid="207546" id="question-wrapper-207546">
                        
                        <div class="question-number
                            " data-qid="207546" data-markable="true">
                            <strong>13</strong>
                        </div>
                        
            
                        <div class="question-content text-highlightable">
                            
                            <div class="question-text ">
                                <div><div>다음의 내용과 같은 것을 고르십시오.</div><hr><div>저는 매일 아침 산책을 하고 학교에 갑니다.학생 식당에서 아침을 먹고 수업을 듣습니다.그리고 커피숍에서 아르바이트를 합니다.</div></div>
                            </div>
                            
                            
                            
            
                            <div class="question-answers">
                                
                                    
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207546" type="radio" name="question-207546" id="question-207546-A" value="A">
                <label class="form-check-label" for="question-207546-A">
                    A. 저는 아침마다 산책을 합니다.
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207546" type="radio" name="question-207546" id="question-207546-B" value="B">
                <label class="form-check-label" for="question-207546-B">
                    B. 저는 아침을 먹고 학교에 갑니다.
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207546" type="radio" name="question-207546" id="question-207546-C" value="C">
                <label class="form-check-label" for="question-207546-C">
                    C. 저는 아르바이트를 하고 학교에 갑니다.
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207546" type="radio" name="question-207546" id="question-207546-D" value="D">
                <label class="form-check-label" for="question-207546-D">
                    D. 저는 학생 식당에서 아르바이트를 합니다.
                </label>
            </div>
            
                                    
                                    
                                
                            </div>
                            
                            
            
                            
                        </div>
            
                        
                    </div>
                    
            
                
            
                
            
                    
                
            
                                        
                                        
                
                
            
                
                    
                    
                
            
                
            
                
            
                
            
                    
                        
            
            
            <div class="context-wrapper ">
                
                
                
                
                
                
                
                
            </div>
            
                    
            
                    <div class="question-wrapper" data-qid="207547" id="question-wrapper-207547">
                        
                        <div class="question-number
                            " data-qid="207547" data-markable="true">
                            <strong>14</strong>
                        </div>
                        
            
                        <div class="question-content text-highlightable">
                            
                            <div class="question-text ">
                                <div><div>다음의 내용과 같은 것을 고르십시오.</div><hr><div>다음 주 월요일에 수학 시험이 있습니다.그 시험은 아주 어렵습니다. 그래서 날마다 도서관에 가서 공부합니다.</div></div>
                            </div>
                            
                            
                            
            
                            <div class="question-answers">
                                
                                    
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207547" type="radio" name="question-207547" id="question-207547-A" value="A">
                <label class="form-check-label" for="question-207547-A">
                    A. 저는 수학을 좋아합니다.
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207547" type="radio" name="question-207547" id="question-207547-B" value="B">
                <label class="form-check-label" for="question-207547-B">
                    B. 저는 요즘 열심히 공부합니다.
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207547" type="radio" name="question-207547" id="question-207547-C" value="C">
                <label class="form-check-label" for="question-207547-C">
                    C. 이번 주에 수학 시험이 있습니다.
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207547" type="radio" name="question-207547" id="question-207547-D" value="D">
                <label class="form-check-label" for="question-207547-D">
                    D. 저는 월요일마다 어려운 시험이 있습니다.
                </label>
            </div>
            
                                    
                                    
                                
                            </div>
                            
                            
            
                            
                        </div>
            
                        
                    </div>
                    
            
                
            
                
            
                    
                
            
                                        
                                        
                
                
            
                
                    
                    
                
            
                
            
                
            
                
            
                    
                        
            
            
            <div class="context-wrapper ">
                
                
                
                
                
                
                
                
            </div>
            
                    
            
                    <div class="question-wrapper" data-qid="207548" id="question-wrapper-207548" style="">
                        
                        <div class="question-number
                            " data-qid="207548" data-markable="true">
                            <strong>15</strong>
                        </div>
                        
            
                        <div class="question-content text-highlightable">
                            
                            <div class="question-text ">
                                <div><div>다음의 내용과 같은 것을 고르십시오.</div><hr><div>친구가 지난달에 고향으로 돌아갔습니다.친구는 저에게 냉장고를 주었습니다.그 냉장고는 커서 좋습니다.</div></div>
                            </div>
                            
                            
                            
            
                            <div class="question-answers">
                                
                                    
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207548" type="radio" name="question-207548" id="question-207548-A" value="A">
                <label class="form-check-label" for="question-207548-A">
                    A. 저는 냉장고를 샀습니다.
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207548" type="radio" name="question-207548" id="question-207548-B" value="B">
                <label class="form-check-label" for="question-207548-B">
                    B. 저는 이 냉장고가 마음에 듭니다.
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207548" type="radio" name="question-207548" id="question-207548-C" value="C">
                <label class="form-check-label" for="question-207548-C">
                    C. 저는 고향에 큰 냉장고가 있습니다.
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207548" type="radio" name="question-207548" id="question-207548-D" value="D">
                <label class="form-check-label" for="question-207548-D">
                    D. 저는 친구에게 냉장고를 주었습니다.
                </label>
            </div>
            
                                    
                                    
                                
                            </div>
                            
                            
            
                            
                        </div>
            
                        
                    </div>
                    
            
                
            
                
            
                    
                
            
                                        
                                        
                
                
            
                
                    
                    
                
            
                
            
                
            
                
            
                    
                        
            
            
            <div class="context-wrapper ">
                
                
                
                
                
                
                
                
            </div>
            
                    
            
                    <div class="question-wrapper" data-qid="207549" id="question-wrapper-207549">
                        
                        <div class="question-number
                            " data-qid="207549" data-markable="true">
                            <strong>16</strong>
                        </div>
                        
            
                        <div class="question-content text-highlightable">
                            
                            <div class="question-text ">
                                <div><div>다음을 읽고 중심 생각을 고르십시오.</div><hr><div>저는 극장에 가지 않고 집에서 혼자 영화를 봅니다.집에서 영화를 보면 누워서 볼 수 있습니다.그리고 보고 싶은 시간에 볼 수 있습니다.</div></div>
                            </div>
                            
                            
                            
            
                            <div class="question-answers">
                                
                                    
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207549" type="radio" name="question-207549" id="question-207549-A" value="A">
                <label class="form-check-label" for="question-207549-A">
                    A. 저는 극장에 자주 갑니다.
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207549" type="radio" name="question-207549" id="question-207549-B" value="B">
                <label class="form-check-label" for="question-207549-B">
                    B. 저는 친구와 영화를 봅니다.
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207549" type="radio" name="question-207549" id="question-207549-C" value="C">
                <label class="form-check-label" for="question-207549-C">
                    C. 저는 극장에서 영화를 봅니다.
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207549" type="radio" name="question-207549" id="question-207549-D" value="D">
                <label class="form-check-label" for="question-207549-D">
                    D. 저는 집에서 영화 보는 것을 좋아합니다.
                </label>
            </div>
            
                                    
                                    
                                
                            </div>
                            
                            
            
                            
                        </div>
            
                        
                    </div>
                    
            
                
            
                
            
                    
                
            
                                        
                                        
                
                
            
                
                    
                    
                
            
                
            
                
            
                
            
                    
                        
            
            
            <div class="context-wrapper ">
                
                
                
                
                
                
                
                
            </div>
            
                    
            
                    <div class="question-wrapper" data-qid="207550" id="question-wrapper-207550">
                        
                        <div class="question-number
                            " data-qid="207550" data-markable="true">
                            <strong>17</strong>
                        </div>
                        
            
                        <div class="question-content text-highlightable">
                            
                            <div class="question-text ">
                                <div><div>다음을 읽고 중심 생각을 고르십시오.</div><hr><div>시간이 없어서 일을 다 하지 못했습니다.그래서 지현 씨가 저를 도와 주었습니다.저는 지현 씨에게 커피를 사 주었습니다.</div></div>
                            </div>
                            
                            
                            
            
                            <div class="question-answers">
                                
                                    
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207550" type="radio" name="question-207550" id="question-207550-A" value="A">
                <label class="form-check-label" for="question-207550-A">
                    A. 저는 일을 많이 합니다.
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207550" type="radio" name="question-207550" id="question-207550-B" value="B">
                <label class="form-check-label" for="question-207550-B">
                    B. 저는 커피를 좋아합니다.
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207550" type="radio" name="question-207550" id="question-207550-C" value="C">
                <label class="form-check-label" for="question-207550-C">
                    C. 저는 지현 씨가 고마웠습니다.
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207550" type="radio" name="question-207550" id="question-207550-D" value="D">
                <label class="form-check-label" for="question-207550-D">
                    D. 저는 지현 씨를 도와주었습니다.
                </label>
            </div>
            
                                    
                                    
                                
                            </div>
                            
                            
            
                            
                        </div>
            
                        
                    </div>
                    
            
                
            
                
            
                    
                
            
                                        
                                        
                
                
            
                
                    
                    
                
            
                
            
                
            
                
            
                    
                        
            
            
            <div class="context-wrapper ">
                
                
                
                
                
                
                
                
            </div>
            
                    
            
                    <div class="question-wrapper" data-qid="207551" id="question-wrapper-207551">
                        
                        <div class="question-number
                            " data-qid="207551" data-markable="true">
                            <strong>18</strong>
                        </div>
                        
            
                        <div class="question-content text-highlightable">
                            
                            <div class="question-text ">
                                <div><div>다음을 읽고 중심 생각을 고르십시오.</div><hr><div>이번 주말에 제가 좋아하는 가수의 공연이 있습니다.저는 두 달 전에 표를 미리 샀습니다.공연을 빨리 보고 싶습니다.</div></div>
                            </div>
                            
                            
                            
            
                            <div class="question-answers">
                                
                                    
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207551" type="radio" name="question-207551" id="question-207551-A" value="A">
                <label class="form-check-label" for="question-207551-A">
                    A. 저는 표를 사고 싶습니다.
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207551" type="radio" name="question-207551" id="question-207551-B" value="B">
                <label class="form-check-label" for="question-207551-B">
                    B. 저는 가수가 되고 싶습니다.
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207551" type="radio" name="question-207551" id="question-207551-C" value="C">
                <label class="form-check-label" for="question-207551-C">
                    C. 저는 공연을 기다리고 있습니다.
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207551" type="radio" name="question-207551" id="question-207551-D" value="D">
                <label class="form-check-label" for="question-207551-D">
                    D. 저는 두 달 전에 공연을 봤습니다.
                </label>
            </div>
            
                                    
                                    
                                
                            </div>
                            
                            
            
                            
                        </div>
            
                        
                    </div>
                    
            
                
            
                
            
                    
                
            
                                        
                                        
                
                
            
                
                    
                    
            
            <div class="question-group-wrapper">
                
                
                
            
            
            <div class="context-wrapper ">
                
                
                
                
                
                
                <div class="context-content text-highlightable">
                    <div><p>[19~20]</p><p>다<span class="highlighted" data-timestamp="1743149438106" style="background-color: rgb(255, 255, 123);" data-highlighted="true" data-uid="f37fa756-cb33-4d50-bb2d-2025efea7eae">음을 </span>읽고 물음에 답하십시오.</p><p>제 친구는 그림 그리는 것을 좋아합니다. 그래서 시간이 있을 때마다 종이컵에 그림을 그립니다. 그리고 친한 사람들에게 종이컵을 선물합니다. <strong>㉠</strong>종이컵은 세상에 하나만 있습니다. 친구의 종이컵은 참 예쁩니다.</p></div>
                </div>
                
                
                
            </div>
            
                
                <div class="questions-wrapper two-cols">
                    
                    
                
            
                
            
                
            
                
            
                    
                        
            
            
            <div class="context-wrapper ">
                
                
                
                
                
                
                
                
            </div>
            
                    
            
                    <div class="question-wrapper" data-qid="207552" id="question-wrapper-207552">
                        
                        <div class="question-number
                            " data-qid="207552" data-markable="true">
                            <strong>19</strong>
                        </div>
                        
            
                        <div class="question-content text-highlightable">
                            
                            <div class="question-text ">
                                <div><strong>㉠</strong>에 들어갈 알맞은 말을 고르십시오.</div>
                            </div>
                            
                            
                            
            
                            <div class="question-answers">
                                
                                    
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207552" type="radio" name="question-207552" id="question-207552-A" value="A">
                <label class="form-check-label" for="question-207552-A">
                    A. 친구가 산
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207552" type="radio" name="question-207552" id="question-207552-B" value="B">
                <label class="form-check-label" for="question-207552-B">
                    B. 친구가 만든
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207552" type="radio" name="question-207552" id="question-207552-C" value="C">
                <label class="form-check-label" for="question-207552-C">
                    C. 사람들이 선물한
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207552" type="radio" name="question-207552" id="question-207552-D" value="D">
                <label class="form-check-label" for="question-207552-D">
                    D. 사람들이 버리는
                </label>
            </div>
            
                                    
                                    
                                
                            </div>
                            
                            
            
                            
                        </div>
            
                        
                    </div>
                    
            
                
            
                
            
                    
                    
                
            
                
            
                
            
                
            
                    
                        
            
            
            <div class="context-wrapper ">
                
                
                
                
                
                
                
                
            </div>
            
                    
            
                    <div class="question-wrapper" data-qid="207553" id="question-wrapper-207553">
                        
                        <div class="question-number
                            " data-qid="207553" data-markable="true">
                            <strong>20</strong>
                        </div>
                        
            
                        <div class="question-content text-highlightable">
                            
                            <div class="question-text ">
                                <div>이 글의 내용과 같은 것을 고르십시오.</div>
                            </div>
                            
                            
                            
            
                            <div class="question-answers">
                                
                                    
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207553" type="radio" name="question-207553" id="question-207553-A" value="A">
                <label class="form-check-label" for="question-207553-A">
                    A. 친구는 종이로 컴을 만듭니다.
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207553" type="radio" name="question-207553" id="question-207553-B" value="B">
                <label class="form-check-label" for="question-207553-B">
                    B. 친구는 예쁜 종이컵을 받았습니다.
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207553" type="radio" name="question-207553" id="question-207553-C" value="C">
                <label class="form-check-label" for="question-207553-C">
                    C. 친구는 친한 사람들과 그림을 그립니다.
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207553" type="radio" name="question-207553" id="question-207553-D" value="D">
                <label class="form-check-label" for="question-207553-D">
                    D. 친구는 종이컵에 예쁘게 그림을 그립니다.
                </label>
            </div>
            
                                    
                                    
                                
                            </div>
                            
                            
            
                            
                        </div>
            
                        
                    </div>
                    
            
                
            
                
            
                    
                </div>
                
            </div>
            
                    
                
            
                                        
                                        
                
                
            
                
                    
                    
            
            <div class="question-group-wrapper">
                
                
                
            
            
            <div class="context-wrapper ">
                
                
                
                
                
                
                <div class="context-content text-highlightable">
                    <div><p>[21~22]</p><p>다음을 읽고 물음에 답하십시오.</p><p>몇 십년 후에는 자동차가 하늘로 다닐 것입니다. 그러면 그 자동차를 만드는 사람이 필요합니다. 그리고 하늘에 자동차가 있으면 하늘에서 일하는 교통경찰도 있어야 합니다. 지금은 이런 사람들을 <strong>㉠</strong> 없습니다. 하지만 앞으로는 이런 사람들을 자주 볼 수 있을 것입니다.</p></div>
                </div>
                
                
                
            </div>
            
                
                <div class="questions-wrapper two-cols">
                    
                    
                
            
                
            
                
            
                
            
                    
                        
            
            
            <div class="context-wrapper ">
                
                
                
                
                
                
                
                
            </div>
            
                    
            
                    <div class="question-wrapper" data-qid="207554" id="question-wrapper-207554">
                        
                        <div class="question-number
                            " data-qid="207554" data-markable="true">
                            <strong>21</strong>
                        </div>
                        
            
                        <div class="question-content text-highlightable">
                            
                            <div class="question-text ">
                                <div><strong>㉠</strong>에 들어갈 알맞은 말을 고르십시오.</div>
                            </div>
                            
                            
                            
            
                            <div class="question-answers">
                                
                                    
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207554" type="radio" name="question-207554" id="question-207554-A" value="A">
                <label class="form-check-label" for="question-207554-A">
                    A. 만날 수
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207554" type="radio" name="question-207554" id="question-207554-B" value="B">
                <label class="form-check-label" for="question-207554-B">
                    B. 보낼 수
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207554" type="radio" name="question-207554" id="question-207554-C" value="C">
                <label class="form-check-label" for="question-207554-C">
                    C. 가르칠 수
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207554" type="radio" name="question-207554" id="question-207554-D" value="D">
                <label class="form-check-label" for="question-207554-D">
                    D. 기다릴 수
                </label>
            </div>
            
                                    
                                    
                                
                            </div>
                            
                            
            
                            
                        </div>
            
                        
                    </div>
                    
            
                
            
                
            
                    
                    
                
            
                
            
                
            
                
            
                    
                        
            
            
            <div class="context-wrapper ">
                
                
                
                
                
                
                
                
            </div>
            
                    
            
                    <div class="question-wrapper" data-qid="207555" id="question-wrapper-207555">
                        
                        <div class="question-number
                            " data-qid="207555" data-markable="true">
                            <strong>22</strong>
                        </div>
                        
            
                        <div class="question-content text-highlightable">
                            
                            <div class="question-text ">
                                <div>무엇에 대한 이야기인지 고르십시오.</div>
                            </div>
                            
                            
                            
            
                            <div class="question-answers">
                                
                                    
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207555" type="radio" name="question-207555" id="question-207555-A" value="A">
                <label class="form-check-label" for="question-207555-A">
                    A. 미래의 집
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207555" type="radio" name="question-207555" id="question-207555-B" value="B">
                <label class="form-check-label" for="question-207555-B">
                    B. 미래의 직업
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207555" type="radio" name="question-207555" id="question-207555-C" value="C">
                <label class="form-check-label" for="question-207555-C">
                    C. 내가 만든 자동차
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207555" type="radio" name="question-207555" id="question-207555-D" value="D">
                <label class="form-check-label" for="question-207555-D">
                    D. 내가 좋아하는 자동차
                </label>
            </div>
            
                                    
                                    
                                
                            </div>
                            
                            
            
                            
                        </div>
            
                        
                    </div>
                    
            
                
            
                
            
                    
                </div>
                
            </div>
            
                    
                
            
                                        
                                        
                
                
            
                
                    
                    
            
            <div class="question-group-wrapper">
                
                
                
            
            
            <div class="context-wrapper ">
                
                
                
                
                
                
                <div class="context-content text-highlightable">
                    <div><p>[23~24]</p><p>다음을 읽고 물음에 답하십시오.</p><p>저는 아침에 일어나서 혼자 운동을 합니다. 운동을 하면 즐겁습니다. 그런데 아침에 <strong>㉠</strong> 일어나는 것이 힘들어서 가끔 운동을 못 합니다. 그래서 다음 주부터는 저녁에 친구와 같이 운동을 하기로 했습니다. 이제 매일 운동을 할 것 같습니다.</p></div>
                </div>
                
                
                
            </div>
            
                
                <div class="questions-wrapper two-cols">
                    
                    
                
            
                
            
                
            
                
            
                    
                        
            
            
            <div class="context-wrapper ">
                
                
                
                
                
                
                
                
            </div>
            
                    
            
                    <div class="question-wrapper" data-qid="207556" id="question-wrapper-207556">
                        
                        <div class="question-number
                            " data-qid="207556" data-markable="true">
                            <strong>23</strong>
                        </div>
                        
            
                        <div class="question-content text-highlightable">
                            
                            <div class="question-text ">
                                <div><strong>㉠</strong>에 들어갈 알맞은 말을 고르십시오.</div>
                            </div>
                            
                            
                            
            
                            <div class="question-answers">
                                
                                    
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207556" type="radio" name="question-207556" id="question-207556-A" value="A">
                <label class="form-check-label" for="question-207556-A">
                    A. 많이
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207556" type="radio" name="question-207556" id="question-207556-B" value="B">
                <label class="form-check-label" for="question-207556-B">
                    B. 잠깐
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207556" type="radio" name="question-207556" id="question-207556-C" value="C">
                <label class="form-check-label" for="question-207556-C">
                    C. 늦게
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207556" type="radio" name="question-207556" id="question-207556-D" value="D">
                <label class="form-check-label" for="question-207556-D">
                    D. 일찍
                </label>
            </div>
            
                                    
                                    
                                
                            </div>
                            
                            
            
                            
                        </div>
            
                        
                    </div>
                    
            
                
            
                
            
                    
                    
                
            
                
            
                
            
                
            
                    
                        
            
            
            <div class="context-wrapper ">
                
                
                
                
                
                
                
                
            </div>
            
                    
            
                    <div class="question-wrapper" data-qid="207557" id="question-wrapper-207557">
                        
                        <div class="question-number
                            " data-qid="207557" data-markable="true">
                            <strong>24</strong>
                        </div>
                        
            
                        <div class="question-content text-highlightable">
                            
                            <div class="question-text ">
                                <div>이 글의 내용과 같은 것을 고르십시오.</div>
                            </div>
                            
                            
                            
            
                            <div class="question-answers">
                                
                                    
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207557" type="radio" name="question-207557" id="question-207557-A" value="A">
                <label class="form-check-label" for="question-207557-A">
                    A. 이 사람은 저녁에 운동을 했습니다.
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207557" type="radio" name="question-207557" id="question-207557-B" value="B">
                <label class="form-check-label" for="question-207557-B">
                    B. 이 사람은 아침마다 친구를 만납니다.
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207557" type="radio" name="question-207557" id="question-207557-C" value="C">
                <label class="form-check-label" for="question-207557-C">
                    C. 이 사람은 친구와 운동을 할 것입니다.
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207557" type="radio" name="question-207557" id="question-207557-D" value="D">
                <label class="form-check-label" for="question-207557-D">
                    D. 이 사람은 친구와 약속을 하려고 합니다.
                </label>
            </div>
            
                                    
                                    
                                
                            </div>
                            
                            
            
                            
                        </div>
            
                        
                    </div>
                    
            
                
            
                
            
                    
                </div>
                
            </div>
            
                    
                
            
                                        
                                        
                
                
            
                
                    
                    
            
            <div class="question-group-wrapper">
                
                
                
            
            
            <div class="context-wrapper ">
                
                
                
                
                
                
                <div class="context-content text-highlightable">
                    <div><p>[25~26]</p><p>다음을 읽고 물음에 답하십시오.</p><p>저는 안경이 여러 개 있습니다. 그래서 그때그때 다른 안경을 씁니다. 사람을 처음 만날 때는 부드러운 느낌의 안경을 씁니다. 운동을 할 때는 가벼운 안경을 씁니다. <strong>㉠</strong> 멋있게 보이고 싶을 때는 유행하는 안경을 씁니다. 이렇게 안경을 바꿔서 쓰면 기분이 좋아집니다.</p></div>
                </div>
                
                
                
            </div>
            
                
                <div class="questions-wrapper two-cols">
                    
                    
                
            
                
            
                
            
                
            
                    
                        
            
            
            <div class="context-wrapper ">
                
                
                
                
                
                
                
                
            </div>
            
                    
            
                    <div class="question-wrapper" data-qid="207558" id="question-wrapper-207558">
                        
                        <div class="question-number
                            " data-qid="207558" data-markable="true">
                            <strong>25</strong>
                        </div>
                        
            
                        <div class="question-content text-highlightable">
                            
                            <div class="question-text ">
                                <div><strong>㉠</strong>에 들어갈 알맞은 말을 고르십시오.</div>
                            </div>
                            
                            
                            
            
                            <div class="question-answers">
                                
                                    
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207558" type="radio" name="question-207558" id="question-207558-A" value="A">
                <label class="form-check-label" for="question-207558-A">
                    A. 그러면
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207558" type="radio" name="question-207558" id="question-207558-B" value="B">
                <label class="form-check-label" for="question-207558-B">
                    B. 그래서
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207558" type="radio" name="question-207558" id="question-207558-C" value="C">
                <label class="form-check-label" for="question-207558-C">
                    C. 그리고
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207558" type="radio" name="question-207558" id="question-207558-D" value="D">
                <label class="form-check-label" for="question-207558-D">
                    D. 그러니까
                </label>
            </div>
            
                                    
                                    
                                
                            </div>
                            
                            
            
                            
                        </div>
            
                        
                    </div>
                    
            
                
            
                
            
                    
                    
                
            
                
            
                
            
                
            
                    
                        
            
            
            <div class="context-wrapper ">
                
                
                
                
                
                
                
                
            </div>
            
                    
            
                    <div class="question-wrapper" data-qid="207559" id="question-wrapper-207559">
                        
                        <div class="question-number
                            " data-qid="207559" data-markable="true">
                            <strong>26</strong>
                        </div>
                        
            
                        <div class="question-content text-highlightable">
                            
                            <div class="question-text ">
                                <div>이 글의 내용과 같은 것을 고르십시오.</div>
                            </div>
                            
                            
                            
            
                            <div class="question-answers">
                                
                                    
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207559" type="radio" name="question-207559" id="question-207559-A" value="A">
                <label class="form-check-label" for="question-207559-A">
                    A. 저는 안경이 한 개 있습니다.
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207559" type="radio" name="question-207559" id="question-207559-B" value="B">
                <label class="form-check-label" for="question-207559-B">
                    B. 저는 유행하는 안경이 있습니다.
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207559" type="radio" name="question-207559" id="question-207559-C" value="C">
                <label class="form-check-label" for="question-207559-C">
                    C. 저는 운동을 할 때 안경을 안 씁니다.
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207559" type="radio" name="question-207559" id="question-207559-D" value="D">
                <label class="form-check-label" for="question-207559-D">
                    D. 저는 사람을 만날 때 안경을 벗습니다.
                </label>
            </div>
            
                                    
                                    
                                
                            </div>
                            
                            
            
                            
                        </div>
            
                        
                    </div>
                    
            
                
            
                
            
                    
                </div>
                
            </div>
            
                    
                
            
                                        
                                        
                
                
            
                
                    
                    
                
            
                
            
                
            
                
            
                    
                        
            
            
            <div class="context-wrapper ">
                
                
                
                
                
                
                
                
            </div>
            
                    
            
                    <div class="question-wrapper" data-qid="207560" id="question-wrapper-207560">
                        
                        <div class="question-number
                            " data-qid="207560" data-markable="true">
                            <strong>27</strong>
                        </div>
                        
            
                        <div class="question-content text-highlightable">
                            
                            <div class="question-text ">
                                <div><div>다음을 순서대로 맞게 나열한 것을 고르십시오.</div><hr><div><strong>(가)</strong> 모든 동물은 잠을 잡니다. </div><div><strong>(나)</strong> 하지만 개나 고양이는 열 시간쯤 잡니다. </div><div><strong>(다)</strong> 말은 하루에 세 시간만 자도 괜찮습니다. </div><div><strong>(라)</strong> 그런데 잠을 자는 시간은 동물마다 다릅니다.</div></div>
                            </div>
                            
                            
                            
            
                            <div class="question-answers">
                                
                                    
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207560" type="radio" name="question-207560" id="question-207560-A" value="A">
                <label class="form-check-label" for="question-207560-A">
                    A. (가) - (나) - (다) - (라)
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207560" type="radio" name="question-207560" id="question-207560-B" value="B">
                <label class="form-check-label" for="question-207560-B">
                    B. (가) - (다) - (나) - (라)
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207560" type="radio" name="question-207560" id="question-207560-C" value="C">
                <label class="form-check-label" for="question-207560-C">
                    C. (가) - (라) - (나) - (다)
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207560" type="radio" name="question-207560" id="question-207560-D" value="D">
                <label class="form-check-label" for="question-207560-D">
                    D. (가) - (라) - (다) - (나)
                </label>
            </div>
            
                                    
                                    
                                
                            </div>
                            
                            
            
                            
                        </div>
            
                        
                    </div>
                    
            
                
            
                
            
                    
                
            
                                        
                                        
                
                
            
                
                    
                    
                
            
                
            
                
            
                
            
                    
                        
            
            
            <div class="context-wrapper ">
                
                
                
                
                
                
                
                
            </div>
            
                    
            
                    <div class="question-wrapper" data-qid="207561" id="question-wrapper-207561">
                        
                        <div class="question-number
                            " data-qid="207561" data-markable="true">
                            <strong>28</strong>
                        </div>
                        
            
                        <div class="question-content text-highlightable">
                            
                            <div class="question-text ">
                                <div><div>다음을 순서대로 맞게 나열한 것을 고르십시오.</div><hr><div><strong>(가)</strong> 우리 고향에는 딸기가 많이 납니다. </div><div><strong>(나)</strong> 그래서 딸기가 많은 4월에 축제를 합니다. </div><div><strong>(다)</strong> 그리고 맛있는 딸기를 시장보다 싸게 살 수 있습니다. </div><div><strong>(라)</strong> 이 축제에서는 딸기로 여러 가지 음식을 만들어 볼 수 있습니다.</div></div>
                            </div>
                            
                            
                            
            
                            <div class="question-answers">
                                
                                    
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207561" type="radio" name="question-207561" id="question-207561-A" value="A">
                <label class="form-check-label" for="question-207561-A">
                    A. (가) - (나) - (다) - (라)
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207561" type="radio" name="question-207561" id="question-207561-B" value="B">
                <label class="form-check-label" for="question-207561-B">
                    B. (가) - (나) - (라) - (다)
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207561" type="radio" name="question-207561" id="question-207561-C" value="C">
                <label class="form-check-label" for="question-207561-C">
                    C. (가) - (다) - (나) - (라)
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207561" type="radio" name="question-207561" id="question-207561-D" value="D">
                <label class="form-check-label" for="question-207561-D">
                    D. (가) - (라) - (나) - (다)
                </label>
            </div>
            
                                    
                                    
                                
                            </div>
                            
                            
            
                            
                        </div>
            
                        
                    </div>
                    
            
                
            
                
            
                    
                
            
                                        
                                        
                
                
            
                
                    
                    
            
            <div class="question-group-wrapper">
                
                
                
            
            
            <div class="context-wrapper ">
                
                
                
                
                
                
                <div class="context-content text-highlightable">
                    <div><p>[29~30]</p><p>다음을 읽고 물음에 답하십시오.</p><p>라면은 맛있지만 소금이 많이 들어 있어서 건강에 나쁩니다. <strong>㉠</strong> 라면의 소금은 보통 국물을 만드는 스프에 있습니다. <strong>㉡</strong> 그래도 국물을 먹고 싶으면 스프를 조금만 넣습니다. <strong>㉢</strong> 그리고 라면을 끓일 때 스프를 늦게 넣는 것도 소금을 덜 먹는 또 하나의 방법입니다. <strong>㉣</strong></p></div>
                </div>
                
                
                
            </div>
            
                
                <div class="questions-wrapper two-cols">
                    
                    
                
            
                
            
                
            
                
            
                    
                        
            
            
            <div class="context-wrapper ">
                
                
                
                
                
                
                
                
            </div>
            
                    
            
                    <div class="question-wrapper" data-qid="207562" id="question-wrapper-207562">
                        
                        <div class="question-number
                            " data-qid="207562" data-markable="true">
                            <strong>29</strong>
                        </div>
                        
            
                        <div class="question-content text-highlightable">
                            
                            <div class="question-text ">
                                <div>다음 문장이 들어갈 곳을 고르십시오.</div>
                            </div>
                            
                            
                            
            
                            <div class="question-answers">
                                
                                    
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207562" type="radio" name="question-207562" id="question-207562-A" value="A">
                <label class="form-check-label" for="question-207562-A">
                    A. ㉠
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207562" type="radio" name="question-207562" id="question-207562-B" value="B">
                <label class="form-check-label" for="question-207562-B">
                    B. ㉡
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207562" type="radio" name="question-207562" id="question-207562-C" value="C">
                <label class="form-check-label" for="question-207562-C">
                    C. ㉢
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207562" type="radio" name="question-207562" id="question-207562-D" value="D">
                <label class="form-check-label" for="question-207562-D">
                    D. ㉣
                </label>
            </div>
            
                                    
                                    
                                
                            </div>
                            
                            
            
                            
                        </div>
            
                        
                    </div>
                    
            
                
            
                
            
                    
                    
                
            
                
            
                
            
                
            
                    
                        
            
            
            <div class="context-wrapper ">
                
                
                
                
                
                
                
                
            </div>
            
                    
            
                    <div class="question-wrapper" data-qid="207563" id="question-wrapper-207563">
                        
                        <div class="question-number
                            " data-qid="207563" data-markable="true">
                            <strong>30</strong>
                        </div>
                        
            
                        <div class="question-content text-highlightable">
                            
                            <div class="question-text ">
                                <div>이 글의 내용과 같은 것을 고르십시오.</div>
                            </div>
                            
                            
                            
            
                            <div class="question-answers">
                                
                                    
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207563" type="radio" name="question-207563" id="question-207563-A" value="A">
                <label class="form-check-label" for="question-207563-A">
                    A. 라면은 건강에 좋은 음식입니다.
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207563" type="radio" name="question-207563" id="question-207563-B" value="B">
                <label class="form-check-label" for="question-207563-B">
                    B. 스프를 많이 넣으면 건강에 좋습니다.
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207563" type="radio" name="question-207563" id="question-207563-C" value="C">
                <label class="form-check-label" for="question-207563-C">
                    C. 스프를 먼저 넣으면 소금을 많이 먹게 됩니다.
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207563" type="radio" name="question-207563" id="question-207563-D" value="D">
                <label class="form-check-label" for="question-207563-D">
                    D. 라면의 소금을 적게 먹는 방법은 한 가지입니다.
                </label>
            </div>
            
                                    
                                    
                                
                            </div>
                            
                            
            
                            
                        </div>
            
                        
                    </div>
                    
            
                
            
                
            
                    
                </div>
                
            </div>
            
                    
                
            
                                        
                                        
                
                
            
                
                    
                    
            
            <div class="question-group-wrapper">
                
                
                
            
            
            <div class="context-wrapper ">
                
                
                
                
                
                
                <div class="context-content text-highlightable">
                    <div><p>[31~32]</p><p>다음을 읽고 물음에 답하십시오.</p><p>지금은 동전과 지폐를 모두 사용합니다. 하지만 선에는 동전만 사용했습 니다. 종이로 만든 지폐는 쉽게 찢어지고 더러워져서 <strong>㉠</strong> 못합니다. 그리고 가짜 돈을 만들기도 쉽습니다. 그래서 동전보다 지폐를 늦게 사용한 것입니다.</p></div>
                </div>
                
                
                
            </div>
            
                
                <div class="questions-wrapper two-cols">
                    
                    
                
            
                
            
                
            
                
            
                    
                        
            
            
            <div class="context-wrapper ">
                
                
                
                
                
                
                
                
            </div>
            
                    
            
                    <div class="question-wrapper" data-qid="207564" id="question-wrapper-207564">
                        
                        <div class="question-number
                            " data-qid="207564" data-markable="true">
                            <strong>31</strong>
                        </div>
                        
            
                        <div class="question-content text-highlightable">
                            
                            <div class="question-text ">
                                <div><strong>㉠</strong>에 들어갈 알맞은 말을 고르십시오.</div>
                            </div>
                            
                            
                            
            
                            <div class="question-answers">
                                
                                    
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207564" type="radio" name="question-207564" id="question-207564-A" value="A">
                <label class="form-check-label" for="question-207564-A">
                    A. 오래 쓰지
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207564" type="radio" name="question-207564" id="question-207564-B" value="B">
                <label class="form-check-label" for="question-207564-B">
                    B. 가끔 내지
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207564" type="radio" name="question-207564" id="question-207564-C" value="C">
                <label class="form-check-label" for="question-207564-C">
                    C. 자주 만들지
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207564" type="radio" name="question-207564" id="question-207564-D" value="D">
                <label class="form-check-label" for="question-207564-D">
                    D. 계속 나오지
                </label>
            </div>
            
                                    
                                    
                                
                            </div>
                            
                            
            
                            
                        </div>
            
                        
                    </div>
                    
            
                
            
                
            
                    
                    
                
            
                
            
                
            
                
            
                    
                        
            
            
            <div class="context-wrapper ">
                
                
                
                
                
                
                
                
            </div>
            
                    
            
                    <div class="question-wrapper" data-qid="207565" id="question-wrapper-207565">
                        
                        <div class="question-number
                            " data-qid="207565" data-markable="true">
                            <strong>32</strong>
                        </div>
                        
            
                        <div class="question-content text-highlightable">
                            
                            <div class="question-text ">
                                <div>이 글의 내용과 같은 것을 고르십시오.</div>
                            </div>
                            
                            
                            
            
                            <div class="question-answers">
                                
                                    
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207565" type="radio" name="question-207565" id="question-207565-A" value="A">
                <label class="form-check-label" for="question-207565-A">
                    A. 지폐는 잘 더러워집니다.
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207565" type="radio" name="question-207565" id="question-207565-B" value="B">
                <label class="form-check-label" for="question-207565-B">
                    B. 옛날에도 지폐를 사용했습니다.
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207565" type="radio" name="question-207565" id="question-207565-C" value="C">
                <label class="form-check-label" for="question-207565-C">
                    C. 지폐가 동전보다 먼저 나왔습니다.
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207565" type="radio" name="question-207565" id="question-207565-D" value="D">
                <label class="form-check-label" for="question-207565-D">
                    D. 동전은 가짜 돈을 만들기 쉽습니다.
                </label>
            </div>
            
                                    
                                    
                                
                            </div>
                            
                            
            
                            
                        </div>
            
                        
                    </div>
                    
            
                
            
                
            
                    
                </div>
                
            </div>
            
                    
                
            
                                        
                                        
                
                
            
                
                    
                    
            
            <div class="question-group-wrapper">
                
                
                
            
            
            <div class="context-wrapper ">
                
                
                
                
                
                
                <div class="context-content text-highlightable">
                    <div><p>[33~34]</p><p>다음을 읽고 물음에 답하십시오.</p></div>
                </div>
                
                
                
            </div>
            
                
                <div class="questions-wrapper two-cols">
                    
                    
                
            
                
            
                
            
                
            
                    
                        
            
            
            <div class="context-wrapper ">
                
                
                
                
                
                
                <div class="context-content text-highlightable">
                    <div><p><img src="https://s4-media1.study4.com/media/topik_tests/img/33_33-34.webp"></p></div>
                </div>
                
                
                
            </div>
            
                    
            
                    <div class="question-wrapper" data-qid="207566" id="question-wrapper-207566">
                        
                        <div class="question-number
                            " data-qid="207566" data-markable="true">
                            <strong>33</strong>
                        </div>
                        
            
                        <div class="question-content text-highlightable">
                            
                            <div class="question-text ">
                                <div>유미 씨는 왜 이 글을 썼습니까?</div>
                            </div>
                            
                            
                            
            
                            <div class="question-answers">
                                
                                    
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207566" type="radio" name="question-207566" id="question-207566-A" value="A">
                <label class="form-check-label" for="question-207566-A">
                    A. 대회 날짜를 바꾸려고
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207566" type="radio" name="question-207566" id="question-207566-B" value="B">
                <label class="form-check-label" for="question-207566-B">
                    B. 대회 참가 신청을 받으려고
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207566" type="radio" name="question-207566" id="question-207566-C" value="C">
                <label class="form-check-label" for="question-207566-C">
                    C. 대회 참가 신청을 취소하려고
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207566" type="radio" name="question-207566" id="question-207566-D" value="D">
                <label class="form-check-label" for="question-207566-D">
                    D. 대회 시간과 장소를 알려 주려고
                </label>
            </div>
            
                                    
                                    
                                
                            </div>
                            
                            
            
                            
                        </div>
            
                        
                    </div>
                    
            
                
            
                
            
                    
                    
                
            
                
            
                
            
                
            
                    
                        
            
            
            <div class="context-wrapper ">
                
                
                
                
                
                
                <div class="context-content text-highlightable">
                    <div><p><img src="https://s4-media1.study4.com/media/topik_tests/img/33_33-34.webp"></p></div>
                </div>
                
                
                
            </div>
            
                    
            
                    <div class="question-wrapper" data-qid="207567" id="question-wrapper-207567">
                        
                        <div class="question-number
                            " data-qid="207567" data-markable="true">
                            <strong>34</strong>
                        </div>
                        
            
                        <div class="question-content text-highlightable">
                            
                            <div class="question-text ">
                                <div>이 글의 내용과 같은 것을 고르십시오.</div>
                            </div>
                            
                            
                            
            
                            <div class="question-answers">
                                
                                    
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207567" type="radio" name="question-207567" id="question-207567-A" value="A">
                <label class="form-check-label" for="question-207567-A">
                    A. 체육관은 운동장 옆에 있습니다.
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207567" type="radio" name="question-207567" id="question-207567-B" value="B">
                <label class="form-check-label" for="question-207567-B">
                    B. 비가 오면 농구 대회를 하지 않습니다.
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207567" type="radio" name="question-207567" id="question-207567-C" value="C">
                <label class="form-check-label" for="question-207567-C">
                    C. 농구 대회 참가자는 10시까지 와야 합니다.
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207567" type="radio" name="question-207567" id="question-207567-D" value="D">
                <label class="form-check-label" for="question-207567-D">
                    D. 날씨가 좋으면 운동장에서 농구 대회를 할 겁니다.
                </label>
            </div>
            
                                    
                                    
                                
                            </div>
                            
                            
            
                            
                        </div>
            
                        
                    </div>
                    
            
                
            
                
            
                    
                </div>
                
            </div>
            
                    
                
            
                                        
                                        
                
                
            
                
                    
                    
            
            <div class="question-group-wrapper">
                
                
                
            
            
            <div class="context-wrapper ">
                
                
                
                
                
                
                <div class="context-content text-highlightable">
                    <div><p>[35~36]</p><p>다음을 읽고 물음에 답하십시오.</p><p>식혜는 한국의 전통 음료수입니다. 보통 모임이나 잔치에서 <strong>㉠</strong> 식혜를 마십니다. 이것은 식혜가 소화를 도와주기 때문입니다. 식혜는 달고 맛있어서 많은 사람들이 좋아합니다. 시원하게 마시면 더 좋습니다. 저는 식혜를 만드는 방법이 간단해서 자주 만들어 먹습니다. 하지만 만드는 데 시간이 오래 걸립니다.</p></div>
                </div>
                
                
                
            </div>
            
                
                <div class="questions-wrapper two-cols">
                    
                    
                
            
                
            
                
            
                
            
                    
                        
            
            
            <div class="context-wrapper ">
                
                
                
                
                
                
                
                
            </div>
            
                    
            
                    <div class="question-wrapper" data-qid="207568" id="question-wrapper-207568">
                        
                        <div class="question-number
                            " data-qid="207568" data-markable="true">
                            <strong>35</strong>
                        </div>
                        
            
                        <div class="question-content text-highlightable">
                            
                            <div class="question-text ">
                                <div><strong>㉠</strong>에 들어갈 알맞은 말을 고르십시오.</div>
                            </div>
                            
                            
                            
            
                            <div class="question-answers">
                                
                                    
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207568" type="radio" name="question-207568" id="question-207568-A" value="A">
                <label class="form-check-label" for="question-207568-A">
                    A. 운동을 한 후에
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207568" type="radio" name="question-207568" id="question-207568-B" value="B">
                <label class="form-check-label" for="question-207568-B">
                    B. 음식을 먹은 후에
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207568" type="radio" name="question-207568" id="question-207568-C" value="C">
                <label class="form-check-label" for="question-207568-C">
                    C. 모임에 가기 전에
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207568" type="radio" name="question-207568" id="question-207568-D" value="D">
                <label class="form-check-label" for="question-207568-D">
                    D. 음료수를 마시기 전에
                </label>
            </div>
            
                                    
                                    
                                
                            </div>
                            
                            
            
                            
                        </div>
            
                        
                    </div>
                    
            
                
            
                
            
                    
                    
                
            
                
            
                
            
                
            
                    
                        
            
            
            <div class="context-wrapper ">
                
                
                
                
                
                
                
                
            </div>
            
                    
            
                    <div class="question-wrapper" data-qid="207569" id="question-wrapper-207569">
                        
                        <div class="question-number
                            " data-qid="207569" data-markable="true">
                            <strong>36</strong>
                        </div>
                        
            
                        <div class="question-content text-highlightable">
                            
                            <div class="question-text ">
                                <div>이 글의 내용과 같은 것을 고르십시오.</div>
                            </div>
                            
                            
                            
            
                            <div class="question-answers">
                                
                                    
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207569" type="radio" name="question-207569" id="question-207569-A" value="A">
                <label class="form-check-label" for="question-207569-A">
                    A. 식혜는 빨리 만들 수 있습니다.
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207569" type="radio" name="question-207569" id="question-207569-B" value="B">
                <label class="form-check-label" for="question-207569-B">
                    B. 식혜는 달아서 사람들이 싫어합니다.
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207569" type="radio" name="question-207569" id="question-207569-C" value="C">
                <label class="form-check-label" for="question-207569-C">
                    C. 식혜는 차갑게 마시면 더 맛있습니다.
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207569" type="radio" name="question-207569" id="question-207569-D" value="D">
                <label class="form-check-label" for="question-207569-D">
                    D. 모임이나 잔치에 가면 식혜를 만듭니다.
                </label>
            </div>
            
                                    
                                    
                                
                            </div>
                            
                            
            
                            
                        </div>
            
                        
                    </div>
                    
            
                
            
                
            
                    
                </div>
                
            </div>
            
                    
                
            
                                        
                                        
                
                
            
                
                    
                    
            
            <div class="question-group-wrapper">
                
                
                
            
            
            <div class="context-wrapper ">
                
                
                
                
                
                
                <div class="context-content text-highlightable">
                    <div><p>[37~38]</p><p>다음을 읽고 물음에 답하십시오.</p><p>문제를 풀기 어려울 때는 책상 앞에만 앉아 있지 마십시오. 계속 앉아 있으면 좋은 생각이 <strong>㉠</strong> 않습니다. 그럴 때는 일어나서 걷는 것이 좋습니다. 걸으려고 꼭 밖으로 <strong>㉡</strong>. 집 안도 좋고 사무실 안도 괜찮습니다.</p></div>
                </div>
                
                
                
            </div>
            
                
                <div class="questions-wrapper two-cols">
                    
                    
                
            
                
            
                
            
                
            
                    
                        
            
            
            <div class="context-wrapper ">
                
                
                
                
                
                
                
                
            </div>
            
                    
            
                    <div class="question-wrapper" data-qid="207570" id="question-wrapper-207570">
                        
                        <div class="question-number
                            " data-qid="207570" data-markable="true">
                            <strong>37</strong>
                        </div>
                        
            
                        <div class="question-content text-highlightable">
                            
                            <div class="question-text ">
                                <div><strong>㉠</strong>에 들어갈 알맞은 말을 고르십시오.</div>
                            </div>
                            
                            
                            
            
                            <div class="question-answers">
                                
                                    
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207570" type="radio" name="question-207570" id="question-207570-A" value="A">
                <label class="form-check-label" for="question-207570-A">
                    A. 나지
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207570" type="radio" name="question-207570" id="question-207570-B" value="B">
                <label class="form-check-label" for="question-207570-B">
                    B. 많지
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207570" type="radio" name="question-207570" id="question-207570-C" value="C">
                <label class="form-check-label" for="question-207570-C">
                    C. 없어지지
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207570" type="radio" name="question-207570" id="question-207570-D" value="D">
                <label class="form-check-label" for="question-207570-D">
                    D. 달라지지
                </label>
            </div>
            
                                    
                                    
                                
                            </div>
                            
                            
            
                            
                        </div>
            
                        
                    </div>
                    
            
                
            
                
            
                    
                    
                
            
                
            
                
            
                
            
                    
                        
            
            
            <div class="context-wrapper ">
                
                
                
                
                
                
                
                
            </div>
            
                    
            
                    <div class="question-wrapper" data-qid="207571" id="question-wrapper-207571">
                        
                        <div class="question-number
                            " data-qid="207571" data-markable="true">
                            <strong>38</strong>
                        </div>
                        
            
                        <div class="question-content text-highlightable">
                            
                            <div class="question-text ">
                                <div>㉡에 들어갈 알맞은 말을 고르십시오.</div>
                            </div>
                            
                            
                            
            
                            <div class="question-answers">
                                
                                    
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207571" type="radio" name="question-207571" id="question-207571-A" value="A">
                <label class="form-check-label" for="question-207571-A">
                    A. 나가려고 합니다.
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207571" type="radio" name="question-207571" id="question-207571-B" value="B">
                <label class="form-check-label" for="question-207571-B">
                    B. 나갈 수 있습니다
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207571" type="radio" name="question-207571" id="question-207571-C" value="C">
                <label class="form-check-label" for="question-207571-C">
                    C. 나가지 않아도 됩니다
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207571" type="radio" name="question-207571" id="question-207571-D" value="D">
                <label class="form-check-label" for="question-207571-D">
                    D. 나가지 않기로 합니다
                </label>
            </div>
            
                                    
                                    
                                
                            </div>
                            
                            
            
                            
                        </div>
            
                        
                    </div>
                    
            
                
            
                
            
                    
                </div>
                
            </div>
            
                    
                
            
                                        
                                        
                
                
            
                
                    
                    
            
            <div class="question-group-wrapper">
                
                
                
            
            
            <div class="context-wrapper ">
                
                
                
                
                
                
                <div class="context-content text-highlightable">
                    <div><p>[39~40]</p><p>다음을 읽고 물음에 답하십시오.</p><p>우리 가족은 <strong>㉠</strong> 적이 없습니다. 그래서 저는 그동안 할머니께서 노래를 좋아하는 것을 몰랐습니다. 그런데 어젯밤에 할머니께서 공연 초대장을 주셨습니다. 그 공연에서 할머니가 노래를 하실 것입니다. 우리 가족은 공연에 가려고 합니다. 거기에서 할머니의 노래를 처음 듣게 될 것입니다.</p></div>
                </div>
                
                
                
            </div>
            
                
                <div class="questions-wrapper two-cols">
                    
                    
                
            
                
            
                
            
                
            
                    
                        
            
            
            <div class="context-wrapper ">
                
                
                
                
                
                
                
                
            </div>
            
                    
            
                    <div class="question-wrapper" data-qid="207572" id="question-wrapper-207572">
                        
                        <div class="question-number
                            " data-qid="207572" data-markable="true">
                            <strong>39</strong>
                        </div>
                        
            
                        <div class="question-content text-highlightable">
                            
                            <div class="question-text ">
                                <div><strong>㉠</strong>에 들어갈 알맞은 말을 고르십시오.</div>
                            </div>
                            
                            
                            
            
                            <div class="question-answers">
                                
                                    
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207572" type="radio" name="question-207572" id="question-207572-A" value="A">
                <label class="form-check-label" for="question-207572-A">
                    A. 할머니와 공연을 한
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207572" type="radio" name="question-207572" id="question-207572-B" value="B">
                <label class="form-check-label" for="question-207572-B">
                    B. 할머니와 공연을 본
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207572" type="radio" name="question-207572" id="question-207572-C" value="C">
                <label class="form-check-label" for="question-207572-C">
                    C. 할머니와 노래를 배운
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207572" type="radio" name="question-207572" id="question-207572-D" value="D">
                <label class="form-check-label" for="question-207572-D">
                    D. 할머니의 노래를 들은
                </label>
            </div>
            
                                    
                                    
                                
                            </div>
                            
                            
            
                            
                        </div>
            
                        
                    </div>
                    
            
                
            
                
            
                    
                    
                
            
                
            
                
            
                
            
                    
                        
            
            
            <div class="context-wrapper ">
                
                
                
                
                
                
                
                
            </div>
            
                    
            
                    <div class="question-wrapper" data-qid="207573" id="question-wrapper-207573">
                        
                        <div class="question-number
                            " data-qid="207573" data-markable="true">
                            <strong>40</strong>
                        </div>
                        
            
                        <div class="question-content text-highlightable">
                            
                            <div class="question-text ">
                                <div>이 글의 내용으로 알 수 있는 것은 무엇입니까?</div>
                            </div>
                            
                            
                            
            
                            <div class="question-answers">
                                
                                    
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207573" type="radio" name="question-207573" id="question-207573-A" value="A">
                <label class="form-check-label" for="question-207573-A">
                    A. 할머니는 노래 부르기를 좋아하십니다.
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207573" type="radio" name="question-207573" id="question-207573-B" value="B">
                <label class="form-check-label" for="question-207573-B">
                    B. 우리 가족은 함께 노래 연습을 했습니다.
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207573" type="radio" name="question-207573" id="question-207573-C" value="C">
                <label class="form-check-label" for="question-207573-C">
                    C. 할머니는 가끔 우리를 공연에 초대하십니다.
                </label>
            </div>
            
                                    
                                    
            <div class="form-check">
                <input data-type="question-answer" class="form-check-input" data-qid="207573" type="radio" name="question-207573" id="question-207573-D" value="D">
                <label class="form-check-label" for="question-207573-D">
                    D. 우리 가족은 할머니의 공연을 보러 갔습니다.
                </label>
            </div>
            
                                    
                                    
                                
                            </div>
                            
                            
            
                            
                        </div>
            
                        
                    </div>
                    
            
                
            
                
            
                    
                </div>
                
            </div>
            
                    
                
            
                                        
                                    </div>
        
        <div class="navigation">
            <button class="nav-btn" id="prevBtn" disabled>Previous</button>
            <button class="nav-btn" id="nextBtn">Next</button>
        </div>
    </div>

    <div id="sidebar">
        <h3>Answers</h3>
        <div id="boxanswers"></div>
        <button id="logBtn">Submit Answers</button>
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
            let timeLeft = 60 * 60; // 60 phút tính bằng giây
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