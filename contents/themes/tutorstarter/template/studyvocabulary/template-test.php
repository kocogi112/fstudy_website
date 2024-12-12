<?php
/*
 * Template Name: Flash Card Vocabulary
 * Template Post Type: studyvocabulary
 
 */


 if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

remove_filter('the_content', 'wptexturize');
remove_filter('the_title', 'wptexturize');
remove_filter('comment_text', 'wptexturize');
get_header(); // Gọi phần đầu trang (header.php)

//if (is_user_logged_in()) {
$post_id = get_the_ID();
$user_id = get_current_user_id();
$additional_info = get_post_meta($post_id, '_studyvocabulary_additional_info', true); 
$custom_number = get_post_meta($post_id, '_studyvocabulary_custom_number', true);

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
$sql = "SELECT testname, test_type, question_choose, id_test FROM list_test_vocabulary_book WHERE id_test = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_test);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
// Fetch test data if available
$data = $result->fetch_assoc();

// Initialize quizData structure

echo "<script>";
echo "const vocabList = [";
// Normalize and split question_choose
$question_choose_cleaned = preg_replace('/\s*,\s*/', ',', trim($data['question_choose']));
$questions = explode(",", $question_choose_cleaned);
$first = true;

foreach ($questions as $question_id) {
    if (strpos($question_id, "vocabulary") === 0) {
        // Query only from list_vocabulary table
        $sql_question = "SELECT id, new_word, language_new_word, vietnamese_meaning,english_explanation ,image_link,example FROM list_vocabulary WHERE id = ?";
        $stmt_question = $conn->prepare($sql_question);
        $stmt_question->bind_param("s", $question_id);
        $stmt_question->execute();
        $result_question = $stmt_question->get_result();

        if ($result_question->num_rows > 0) {
            $question_data = $result_question->fetch_assoc();

            if (!$first) echo ",";
            $first = false;
      
            echo "{";
            echo "id_vocab: " . json_encode($question_data['id']) . ",";
            echo "vocab: " . json_encode($question_data['new_word']) . ",";
            echo "language_vocab: " . json_encode($question_data['language_new_word']) . ",";
            echo "vietnamese_meaning: " . json_encode($question_data['vietnamese_meaning']) . ",";
            echo "explanation: " . json_encode($question_data['english_explanation']) . ",";
            echo "example: " . json_encode($question_data['example']) . ",";

            echo "}";
        }
    }
}
$testname = $data['testname'] ?? "Test name not found";
// Close the questions array and the main object
echo "];";
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
    <title>Flashcard App</title>
</head>
<style>
    body {
    font-family: Arial, sans-serif;
    /*background-color: #1b1b32;
    color: white; */

    justify-content: center;
    align-items: center;
    width: 100%;
}
.progress {
    margin-top: 20px;
    width: 100%;
    background-color: #f4f4f4;
    border-radius: 5px;
    overflow: hidden;
    height: 25px;
    margin-bottom: 15px;
  }
  
  .progress-bar {
    height: 100%;
    background-color: #007bff;
    transition: width 0.4s ease;
  }
  

  .flashcard-container {
    display: block;
    margin-left: auto;
    margin-right: auto;
    height: 600px;
    text-align: center;
    width: 90%;
}

.flashcard {
    margin: auto;
    width: 50%;
    padding: 10px;
    overflow: auto;
    text-align: center;
    justify-content: center;
    font-size: 20px;
    width: 60%;
    height: 400px;
    perspective: 1000px;
   /* margin-bottom: 50px; */
}

.card-inner {
    width: 100%;
    height: 100%;
    position: relative;
    transform-style: preserve-3d;
    transition: transform 0.6s;
}

.flashcard.flipped .card-inner {
    transform: rotateY(180deg);
}

.card-front, .card-back {
    position: absolute;
    width: 100%;
    height: 100%;
    backface-visibility: hidden;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    border-radius: 10px;
    padding: 20px;
}

.card-front {
    background-color: #2b2b40;
}

.card-back {
    background-color: #444;
    transform: rotateY(180deg);
}

.audio-button {
    position: absolute;
    top: 10px;
    right: 10px;
    background-color: transparent;
    border: none;
    color: white;
    font-size: 20px;
    cursor: pointer;
}

.audio-button:hover {
    color: #aaa;
}

.controls {
    margin: auto;
    width: 60%;
 


    display: flex;
    justify-content: space-between;
    align-items: center;
}

.control-button {
    background-color: #444;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
}

.control-button:hover {
    background-color: #555;
}


.vocab-table {
    width: 100%;
    margin-top: 20px;
    border-collapse: collapse;
    background-color: #2b2b40;
    color: white;
    text-align: left;
}

.vocab-table th, .vocab-table td {
    border: 1px solid #444;
    padding: 10px;
}

.vocab-table th {
    background-color: #444;
}

.vocab-table tr:nth-child(even) {
    background-color: #333;
}
/* Style cho nút "Kiểm tra" */
#check {
    display: inline-flex; /* Nội dung nằm ngang */
    align-items: center; /* Căn giữa dọc */
    gap: 8px; /* Khoảng cách giữa icon và chữ */
    padding: 10px 16px;
    font-size: 16px;
    cursor: pointer;
    border: 1px solid #ccc;
    background-color: #f9f9f9;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

#check:hover {
    background-color: #e0e0e0; /* Hiệu ứng khi rê chuột */
}

#check i {
    font-size: 18px; /* Kích thước biểu tượng */
}

/* Style cho nút "Gợi ý" */
#hintButton {
    display: inline-flex; /* Nội dung nằm ngang */
    align-items: center; /* Căn giữa dọc */
    gap: 8px; /* Khoảng cách giữa icon và chữ */
    padding: 10px 16px;
    font-size: 16px;
    cursor: pointer;
    border: 1px solid #ccc;
    background-color: #f9f9f9;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

#hintButton:hover {
    background-color: #e0e0e0; /* Hiệu ứng khi rê chuột */
}

#hintButton i {
    font-size: 18px; /* Kích thước biểu tượng */
}

#nextButton {
    display: inline-flex; /* Nội dung nằm ngang */
    align-items: center; /* Căn giữa dọc */
    gap: 8px; /* Khoảng cách giữa icon và chữ */
    padding: 10px 16px;
    font-size: 16px;
    cursor: pointer;
    border: 1px solid #ccc;
    background-color: #f9f9f9;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

#nextButton:hover {
    background-color: #e0e0e0; /* Hiệu ứng khi rê chuột */
}

#nextButton i {
    font-size: 18px; /* Kích thước biểu tượng */
}
.tabs {
    display: flex;
    border-bottom: 1px solid #ccc;
    margin-bottom: 16px;
}

.tabs button {
    flex: 1;
    padding: 10px 16px;
    cursor: pointer;
    border: none;
    background-color: #f1f1f1;
    font-size: 16px;
    text-align: center;
}

.tabs button.active {
    background-color: #ffffff;
    border-bottom: 2px solid #007bff;
    font-weight: bold;
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

table {
    width: 100%;
    border-collapse: collapse;
}

table th, table td {
    border: 1px solid #ccc;
    padding: 8px;
    text-align: left;
}

table th {
    background-color: #f9f9f9;
}


</style>
<body>
    <h4 style = "color: black">Vocabulary Quiz: <?php echo htmlspecialchars($testname); ?></h4>

    <div class="flashcard-container">
        <div class="progress">
            <div class="progress-bar" id="progressBar" style="width: 0%;"></div>
          </div>
          <p id="questionStatus"></p> <!-- Hiển thị số câu hiện tại -->

        <div id="flashcard" class="flashcard">
            <p id="explanationText"></p>
            <p id="definitionText"></p>
            <p id="exampleText"></p>
            <input type="text" id="vocabInput" placeholder="Nhập câu trả lời của bạn ...">
            <!-- Nút kiểm tra -->
           
            <button id="check">
                <i class="fa-solid fa-check"></i> Kiểm tra
            </button>

            <!-- Nút gợi ý -->
            <button id="hintButton">
                <i class="fa-regular fa-lightbulb"></i> Gợi ý
            </button>

  
            <button id="nextButton" >
                Bỏ qua →
            </button>
      



            <p id="hintMessage"></p> <!-- Nơi hiển thị gợi ý -->

            <p id="resultMessage"></p>
        </div>
        
    </div>
    <div id="resultContainer" style="display: none;">
        <h2>Kết quả</h2>
        <p>Số câu đúng: <span id="correctCount"></span></p>
        <p>Số câu sai: <span id="incorrectCount"></span></p>
        <p>Số câu bỏ qua: <span id="skippedCount"></span></p>
        <p>Phần trăm đúng: <span id="accuracyPercentage"></span>%</p>
        <p>Thời gian hoàn thành: <span id="completionTime"></span> giây</p>
        <p>Trạng thái: <span id="status"></span></p>
    </div>
    <script src="http://localhost/wordpress/contents/themes/tutorstarter/study_vocabulary_toolkit/test-new-word/script9.js"></script>

</body>
</html>



<?php
get_footer();
/*} else {
    get_header();
    echo '<p>Please log in start reading test.</p>';
    get_footer();
}*/