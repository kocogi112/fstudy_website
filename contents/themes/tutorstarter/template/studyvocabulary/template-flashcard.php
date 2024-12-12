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
$id_test = $custom_number; // Ensure $custom_number is set before this script

// Prepare the SQL statement
$sql = "SELECT testname, test_type, question_choose, id_test FROM list_test_vocabulary_book WHERE id_test = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_test);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Fetch test data
    $data = $result->fetch_assoc();

    // Output JavaScript array
    echo "<script>";
    echo "const vocabList = [];";

    // Normalize and split question_choose
    $question_choose_cleaned = preg_replace('/\s*,\s*/', ',', trim($data['question_choose']));
    $questions = explode(",", $question_choose_cleaned);

    foreach ($questions as $question_id) {
        if (strpos($question_id, "vocabulary") === 0) {
            // Query list_vocabulary table
            $sql_question = "SELECT id, new_word, language_new_word, vietnamese_meaning, english_explanation, image_link 
                             FROM list_vocabulary 
                             WHERE id = ?";
            $stmt_question = $conn->prepare($sql_question);
            $stmt_question->bind_param("s", $question_id);
            $stmt_question->execute();
            $result_question = $stmt_question->get_result();

            if ($result_question->num_rows > 0) {
                $question_data = $result_question->fetch_assoc();
                echo "vocabList.push(" . json_encode([
                    'vocab' => $question_data['new_word'],
                    'explanation' => $question_data['english_explanation'],
                    'vietnamese_meaning' => $question_data['vietnamese_meaning']
                ]) . ");";
            }
            $stmt_question->close();
        }
    }

    // Output testname
    $testname = $data['testname'] ?? "Test name not found";
    echo "console.log('Test name: " . addslashes($testname) . "');";
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
    <!--<link rel="stylesheet" href="http://localhost/wordpress/contents/themes/tutorstarter/study_vocabulary_toolkit/flash-card-app/styles.css">-->
</head>
<style>
    body {
    font-family: Arial, sans-serif;
    /*background-color: #1b1b32; */
    color: white;
    background-color: #f8f9fa!important;

    justify-content: center;
    align-items: center;
    width: 100%;
}

.flashcard-container {
    display: block;
    margin-left: auto;
    margin-right: auto;
    text-align: center;
    width: 90%;
    /*max-width: 600px; */
}

.flashcard {
    margin: auto;
    width: 50%;
    padding: 10px;
    overflow: auto;
    text-align: center;
    justify-content: center;
    font-size: 30px;
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
</style>
<body>
    <h4 style = "color: black">Flashcard set: <?php echo htmlspecialchars($testname); ?></h4>
    <div class="flashcard-container">
        <div id="flashcard" class="flashcard">
            <div class="card-inner">
                <div class="card-front">
                    <button id="audioButton" class="audio-button">🔊</button>
                    <p id="vocabText">Vocab</p>
                </div>
                <div class="card-back">
                    <p id="definitionText">Definition</p>
                    <p id="explanationText">Explanation</p>
                    
                </div>
            </div>
        </div>
        <div class="controls">
            <button id="prev" class="control-button">← Prev</button>
            <span id="progress">1 / 1</span>
            <button id="next" class="control-button">Next →</button>
            <button id="fullscreen" class="control-button">⛶ Fullscreen</button>
            <button id="settings" class="control-button">⚙ Settings</button>
        </div>

        <table id="vocabTable" class="vocab-table">
            <thead>
                <tr>
                    <th>Số thứ tự</th>
                    <th>Vocab</th>
                    <th>Vietnamese Meaning</th>
                    <th>Explanation</th>
                    <th>Phát âm</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
    <script src="http://localhost/wordpress/contents/themes/tutorstarter/study_vocabulary_toolkit/flash-card-app/script2.js"></script>
</body>
</html>




<?php
get_footer();
/*} else {
    get_header();
    echo '<p>Please log in start reading test.</p>';
    get_footer();
}*/