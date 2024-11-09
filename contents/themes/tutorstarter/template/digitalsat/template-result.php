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
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}
.green-text {
    color: green;
}

.red-text {
    color: red;
}

.popup-content {
    background-color: #fefefe;
    margin: 5% auto; /* 15% from the top and centered */
    padding: 20px;
    border: 1px solid #888;
    max-height: 70%;
    overflow: auto;
    width: 70%; /* Could be more or less, depending on screen size */
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

body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
</style>

<title>Digital SAT Report</title>

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
    
$questions = explode(",", $data['question_choose']);
// Normalize question IDs to handle spaces
$questions = array_map(function($id) {
    return str_replace(' ', '', trim($id)); // Remove spaces and trim
}, $questions);

    // Display results
    foreach ($results as $result) {
        echo '<h2>Digital SAT Test Report</h2> <button>Share this test</button>';
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
                $result_status = ($user_answer == $correct_answer_text) ? 'Correct' : 'Incorrect';
                $color_class = ($result_status == 'Correct') ? 'green-text' : 'red-text';

                // Display each answer in the table
                echo '<tr>';
                echo '<td>Question ' . $question_number . '</td>';
                echo '<td>'.  $question_data['id_question'].  '</td>'; 
                echo '<td>' . esc_html($user_answer) . '</td>';
                echo '<td>' . $correct_answer_text . '</td>';
                echo '<td class="' . $color_class . '">' . $result_status . '</td>'; // Apply color class
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
        <span class="close" onclick="closeDetailExplanation()">&times;</span>
        <b>Details:</b>
        <div id="popup_question_id"></div> <!-- Display the question ID here -->
        <div id="popup_question_content"></div> <!-- Display the question content here -->
        <div id="popup_question_answer"></div> <!-- Display the question answer here -->
        <div id="popup_question_correct_answer"></div> <!-- Display the question answer here -->
        <div id="popup_question_user_answer"></div> <!-- Display the question answer here -->
        <div id="popup_question_explanation"></div> <!-- Display the question answer here -->

    </div>
</div>



</body>


<script>

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
    document.getElementById('popup_question_user_answer').innerHTML = `User answer: ${userAnswer}`; // Use correctAnswer parameter
    document.getElementById('popup_question_explanation').innerHTML = `Explanation:  ${explanationQuestion} `; // This will render HTML tags properly


}





</script>

<?php





get_footer();