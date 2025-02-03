<?php
/*
 * Template Name: Doing Template
 * Template Post Type: conversation ai
 
 */

    if (!defined('ABSPATH')) {
        exit; // Exit if accessed directly.
    }

    remove_filter('the_content', 'wptexturize');
    remove_filter('the_title', 'wptexturize');
    remove_filter('comment_text', 'wptexturize');

if (is_user_logged_in()) {
    $post_id = get_the_ID();
    $user_id = get_current_user_id();
 //   $custom_number = get_post_meta($post_id, '_digitalsat_custom_number', true);
    $custom_number =intval(get_query_var('id_test'));
    $current_user = wp_get_current_user();
    $current_username = $current_user->user_login;
    $username = $current_username;
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
$sql_test = "SELECT * FROM conversation_with_ai_list WHERE id_test = ?";
$stmt_test = $conn->prepare($sql_test);
$stmt_test->bind_param("i", $id_test);
$stmt_test->execute();
$result_test = $stmt_test->get_result();


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


// Query to fetch token details for the current username
$sql2 = "SELECT token, token_use_history 
         FROM user_token 
         WHERE username = ?";


if ($result_test->num_rows > 0) {
    // Fetch test data if available
    $data = $result_test->fetch_assoc();
    $token_need = $data['token_need'];
    $time_allow = $data['time_allow'];
    $permissive_management = $data['permissive_management'];
    $testname =  $data['testname'];
    

    add_filter('document_title_parts', function ($title) use ($testname) {
      $title['title'] = $testname; // Use the $testname variable from the outer scope
      return $title;
  });
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
          
          get_header(); // Gọi phần đầu trang (header.php)












    // Initialize quizData structure
    echo "<script>";
    echo "const quizData = {";
    echo "    'title': " . json_encode($data['testname']) . ",";
    echo "    'duration': " . intval($data['time']) * 60 . ",";
    echo "    'test_type': " . json_encode($data['test_type']) . ",";
    echo "    'number_questions': " . intval($data['number_question']) . ",";
    echo "    'category_test': " . json_encode($data['tag']) . ",";
    echo "    'id_test': " . json_encode($data['tag'] . "_003") . ",";
    echo "    'restart_question_number_for_each_question_category': 'Yes',";
    echo "    'data_added_1': '',";
    echo "    'data_added_2': '',";
    echo "    'data_added_3': '',";
    echo "    'data_added_4': '',";
    echo "    'data_added_5': '',";
    echo "    'questions': [";

    // Normalize and split question_choose
    $question_choose_cleaned = preg_replace('/\s*,\s*/', ',', trim($data['question_choose']));
    $questions = explode(",", $question_choose_cleaned);
    $first = true;

    foreach ($questions as $question_id) {
        if (strpos($question_id, "verbal") === 0) {
            // Query only from digital_sat_question_bank_verbal table
            $sql_question = "SELECT id_question, type_question, question_content, answer_1, answer_2, answer_3, answer_4, correct_answer, explanation, image_link FROM digital_sat_question_bank_verbal WHERE id_question = ?";
            $stmt_question = $conn->prepare($sql_question);
            $stmt_question->bind_param("s", $question_id);
            $stmt_question->execute();
            $result_question = $stmt_question->get_result();

            if ($result_question->num_rows > 0) {
                $question_data = $result_question->fetch_assoc();

                if (!$first) echo ",";
                $first = false;

                echo "{";
                echo "'type': " . json_encode($question_data['type_question']) . ",";
                echo "'question': " . json_encode($question_data['question_content']) . ",";
                echo "'image': " . json_encode($question_data['image_link']) . ",";
                echo "'question_category': '',";
                echo "'id_question': " . json_encode($question_data['id_question']) . ",";

                echo "'answer': [";
                echo "['" . $question_data['answer_1'] . "', '" . ($question_data['correct_answer'] == 'answer_1' ? "true" : "false") . "'],";
                echo "['" . $question_data['answer_2'] . "', '" . ($question_data['correct_answer'] == 'answer_2' ? "true" : "false") . "'],";
                echo "['" . $question_data['answer_3'] . "', '" . ($question_data['correct_answer'] == 'answer_3' ? "true" : "false") . "'],";
                echo "['" . $question_data['answer_4'] . "', '" . ($question_data['correct_answer'] == 'answer_4' ? "true" : "false") . "']";
                echo "],";
                echo "'explanation': " . json_encode($question_data['explanation']) . ",";
                echo "'section': '',";
                echo "'related_lectures': ''";
                echo "}";
            }
        }
    }

    // Close the questions array and the main object
    echo "]};";
    echo "</script>";



    // Close statement and connection
    $stmt_test->close();
    $conn->close();
        
        

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Conversation With AI</title>
  <style>
.chat-box {
  width: 100%;
  height: 500px;
  overflow-y: auto;
  border: 1px solid #ccc;
  border-radius: 8px;
  padding: 10px;
  background-color: #f9f9f9;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.chat-message {
  margin: 10px 0;
  display: flex;
  align-items: flex-start;
}

.chat-message.assistant {
  justify-content: flex-start;
}

.chat-message.user {
  justify-content: flex-end;
}

.message-content {
  max-width: 70%;
  padding: 10px;
  border-radius: 10px;
  font-size: 14px;
  line-height: 1.5;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.chat-message.assistant .message-content {
  background-color: #e3f2fd;
  color: #0d47a1;
}

.chat-message.user .message-content {
  background-color: #bbdefb;
  color: #0d47a1;
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

.start_test {
  appearance: none;
  background-color: #2ea44f;
  border: 1px solid rgba(27, 31, 35, .15);
  border-radius: 6px;
  box-shadow: rgba(27, 31, 35, .1) 0 1px 0;
  box-sizing: border-box;
  color: #fff;
  cursor: pointer;
  display: inline-block;
  font-family: -apple-system,system-ui,"Segoe UI",Helvetica,Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji";
  font-size: 20px;
  font-weight: 600;
  line-height: 20px;
  padding: 6px 16px;
  position: relative;
  text-align: center;
  text-decoration: none;
  user-select: none;
  -webkit-user-select: none;
  touch-action: manipulation;
  vertical-align: middle;
  white-space: nowrap;
}

.start_test:focus:not(:focus-visible):not(.focus-visible) {
  box-shadow: none;
  outline: none;
}

.start_test:hover {
  background-color: #2c974b;
}

.start_test:focus {
  box-shadow: rgba(46, 164, 79, .4) 0 0 0 3px;
  outline: none;
}

.start_test:disabled {
  background-color: #94d3a2;
  border-color: rgba(27, 31, 35, .1);
  color: rgba(255, 255, 255, .8);
  cursor: default;
}

.start_test:active {
  background-color: #298e46;
  box-shadow: rgba(20, 70, 32, .2) 0 1px 0 inset;
}

  </style>
</head>
<body onload = "main()">

<div id = "test-prepare">
        <div class="loader"></div>
        <h3>Your test will begin shortly</h3>
        <div id = "checkpoint" class = "checkpoint">
                <?php
                    if($premium_test == "True"){
                        echo "<script >console.log('Thông báo. Bạn còn {$foundUser['time_left']} lượt làm bài. success ');</script>";
                        echo " <p style = 'color:green'> Bạn còn {$foundUser['time_left']} lượt làm bài này <svg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 24 24' fill='none' stroke='#7ed321' stroke-width='2' stroke-linecap='round' stroke-linejoin='arcs'><path d='M22 11.08V12a10 10 0 1 1-5.93-9.14'></path><polyline points='22 4 12 14.01 9 11.01'></polyline></svg> </p> ";
                        echo "<script>console.log('This is premium test');</script>";
                    }
                    else{
                        echo "<script>console.log('This is free test');</script>"; 
                    }
                        ?>
        </div>    
        <div id = "quick-instruction">
            <i>Quick Instruction:<br>
            - If you find any errors from test (image,display,text,...), please let us know by clicking icon <i class="fa-solid fa-bug"></i><br> 
            - Incon <i class="fa-solid fa-circle-info"></i> will give you a guide tour, in which you can understand the structure of test, include test's type, formation and how to answer questions<br>
            - All these two icons are at the right-above side of test.
        </i>

        </div>
        <div style="display: none;" id="date" style="visibility:hidden;"></div>
        <div style="display: none;" id="title-test"><?php echo esc_html($testname);?></div>
        <div  style="display: none;"  id="id_test"  style="visibility:hidden;"><?php echo esc_html($custom_number);?></div>
        <button  style="display: none;" class ="start_test" id="start_test"  onclick = "prestartTest()">Start test</button>
        <i id = "welcome" style = "display:none">Click Start Test button to start the test now. Good luck</i>


    </div>

 <div  id = "test_screen" style="display: none;">
    <div class="container mt-4">
      <h1>Conversation With AI</h1>
      <div class="mb-3">
        <label for="scenarioSelect" class="form-label">Chọn tình huống:</label>
        <select id="scenarioSelect" class="form-select">
          <option value="receptionist">Đặt phòng cho chuyến du lịch</option>
          <option value="teacher">Hỏi giáo viên về bài luận cuối khóa</option>
          <option value="ticketAgent">Đặt vé máy bay online</option>
        </select>
      </div>
      <div class="mb-3">
        <label for="modelSelect" class="form-label">Chọn mô hình:</label>
        <select id="modelSelect" class="form-select">
          <option value="llama3-8b-8192">llama3-8b-8192</option>
          <option value="model-2">model-2</option>
          <option value="model-3">model-3</option>
        </select>
      </div>
      <div id="chatBox" class="chat-box mb-3"></div>
      <div class="input-group">
      <button id="micButton" class="btn btn-secondary">🎤</button>
      <input id="userInput" type="text" class="form-control" placeholder="Speak your message...">
      <button id="sendButton" class="btn btn-primary">Send</button>
      </div>

    </div>
 </div>
  <script src="/wordpress/contents/themes/tutorstarter/conversation_ai_toolkit/script__.js"></script>
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
                    table: 'conversation_with_ai_list',
                    change_token: '$token_need',
                    payment_gate: 'token',
                    title: 'Renew test $testname with $id_test (Conversation With AI) with $token_need (Buy $time_allow time do this test)',
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