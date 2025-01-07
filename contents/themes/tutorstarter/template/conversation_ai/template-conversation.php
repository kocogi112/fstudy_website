<?php
/*
 * Template Name: Doing Template
 * Template Post Type: digitalsat
 
 */

    if (!defined('ABSPATH')) {
        exit; // Exit if accessed directly.
    }

    remove_filter('the_content', 'wptexturize');
    remove_filter('the_title', 'wptexturize');
    remove_filter('comment_text', 'wptexturize');
    get_header(); // Gọi phần đầu trang (header.php)

if (is_user_logged_in()) {
    $post_id = get_the_ID();
    $user_id = get_current_user_id();
    $additional_info = get_post_meta($post_id, '_digitalsat_additional_info', true); 
    $custom_number = get_post_meta($post_id, '_digitalsat_custom_number', true);

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
$sql = "SELECT testname, time, test_type, question_choose, tag, number_question FROM digital_sat_test_list WHERE id_test = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_test);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Fetch test data if available
    $data = $result->fetch_assoc();

    // Initialize quizData structure
    echo "<script>";
    echo "const quizData = {";
    echo "    'title': " . json_encode($data['testname']) . ",";
    echo "    'description': '',";
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
} else {
    echo "<script>console.log('No data found for the given id_test');</script>";
}


    // Close statement and connection
    $stmt->close();
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


  </style>
</head>
<body>
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
  <script src="/wordpress/contents/themes/tutorstarter/conversation_ai_toolkit/script.js"></script>
</body>
</html>



<?php
    //get_footer();
} else {
    get_header();
    echo '<p>Please log in to submit your answer.</p>';
    //get_footer();
}