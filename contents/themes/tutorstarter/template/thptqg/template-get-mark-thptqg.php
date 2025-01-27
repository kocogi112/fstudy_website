<?php
/*
 * Template Name: Result Template
 * Template Post Type: ieltsreadingtest
 */

get_header();
$post_id = get_the_ID();

// Get the custom number field value
//$custom_number = get_post_meta($post_id, '_ieltsreadingtest_custom_number', true);
$custom_number =intval(get_query_var('id_test'));
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

$sql = "SELECT part, duration, number_question_of_this_part, paragraph, group_question, category FROM ielts_reading_part_1_question WHERE id_test = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $custom_number); // 'i' is used for integer
$stmt->execute();
$result = $stmt->get_result();
$part = []; // Initialize the array for storing questions

while ($row = $result->fetch_assoc()) {
    // Create an associative array for each row
    $entry = [
        'part_number' => $row['part'],
        'paragraph' => $row['paragraph'],
        'number_question_of_this_part' => $row['number_question_of_this_part'],
        'duration' => $row['duration'],
    ];

    // Check if group_question is a valid JSON string, convert to PHP array or object
    if (!empty($row['group_question'])) {
        $entry['group_question'] = json_decode($row['group_question'], true); // Convert JSON string to array
    } else {
        $entry['group_question'] = null; // Set to null if no data
    }

    // Add the entry to the $part array
    $part[] = $entry;
}

// Output the quizData as JavaScript
echo '<script type="text/javascript">
const quizData = {
    part: ' . json_encode($part, JSON_UNESCAPED_SLASHES) . '
};

console.log(quizData);
</script>';

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

// Check results
if (!empty($results)) {
    // Display results
    foreach ($results as $result) {
        echo '<h2>Kết quả Reading Test</h2>';
        echo '<p>Username: ' . esc_html($result->username) . '</p>';
        echo '<p>Ngày làm đề: ' . esc_html($result->dateform) . '</p>';
        echo '<p>Tên đề thi: ' . esc_html($result->testname) . '</p>';
        echo '<p>Thời gian làm test: ' . esc_html($result->timedotest) . '</p>';
        echo '<p>Testsavenumber: ' . esc_html($result->testsavenumber) . '</p>';

        // Create a table for User Answers and Correct Answers
        echo '<table style="width:100%; border-collapse: collapse;">';
        echo '<tr>
                <th style="border: 1px solid black; padding: 8px;">Câu hỏi</th>
                <th style="border: 1px solid black; padding: 8px;">Đáp án của người dùng</th>
                <th style="border: 1px solid black; padding: 8px;">Đáp án đúng</th>
              </tr>';

        // Example string from user answers
        $userAnswers = $result->useranswer; // Assuming this is a string
        $userAnswersArray = explode('Question ', $userAnswers); // Split by question number

        foreach ($userAnswersArray as $index => $userAnswer) {
            if (trim($userAnswer) === '') continue; // Skip empty strings

            // Example regex to extract user answer and correct answer
            preg_match('/User Answer: (.*?), Correct Answer: (.*?)(?=Question \d+|$)/', $userAnswer, $matches);

            if (count($matches) === 3) {
                echo '<tr>
                        <td style="border: 1px solid black; padding: 8px;">Câu hỏi ' . ($index ) . '</td>
                        <td style="border: 1px solid black; padding: 8px;">' . esc_html($matches[1]) . '</td>
                        <td style="border: 1px solid black; padding: 8px;">' . esc_html($matches[2]) . '</td>
                      </tr>';
            }
        }
        echo '</table>';
    }
} else {
    // If no results with testsavenumber
    echo '<p>Không có test nào với testsavenumber này.</p>';
}

echo '<script>
// Your existing JavaScript for logging user answers
for (let i = 0; i < quizData.part.length; i++) {
    logUserAnswers(i);
}

// Function to calculate the starting question number for a part
function getStartingQuestionNumber(partIndex) {
    let startQuestion = 1; // Start with question 1
    for (let i = 0; i < partIndex; i++) {
        startQuestion += parseInt(quizData.part[i].number_question_of_this_part);
    }
    return startQuestion;
}

// Function to calculate question range for a part
function getQuestionRange(partIndex) {
    const startQuestion = getStartingQuestionNumber(partIndex);
    const endQuestion = startQuestion + parseInt(quizData.part[partIndex].number_question_of_this_part) - 1;
    return `${startQuestion} - ${endQuestion}`;
}

function logUserAnswers(partIndex) {
    const part = quizData.part[partIndex];

    // Initialize variables for correct and incorrect answers
    let correctCount = 0;
    let incorrectCount = 0;
    let totalQuestions = 0; // Total question count

    // Initialize the current question number for this part
    let currentQuestionNumber = getStartingQuestionNumber(partIndex);

    part.group_question.forEach((group, groupIndex) => {
        group.questions.forEach((question, questionIndex) => {
            let questionNumber;
            let userAnswer;
            let correctAnswer; // Variable for correct answer

            // Handle multi-select questions
            if (group.type_group_question === "multi-select") {
                const answerChoiceCount = parseInt(question.number_answer_choice) || 1;
                questionNumber = `${currentQuestionNumber}-${currentQuestionNumber + answerChoiceCount - 1}`;
                const selectedAnswers = [];
                const correctAnswers = [];

                question.answers.forEach((answer, answerIndex) => {
                    // Check if this answer is selected by the user
                    if (isAnswerSelected(partIndex, groupIndex, questionIndex, answerIndex)) {
                        selectedAnswers.push(answer[0]);
                    }
                    // Check if this answer is marked as correct
                    if (answer[1] === true) {
                        correctAnswers.push(answer[0]);
                    }
                });

                correctAnswer = correctAnswers.length > 0 ? correctAnswers.join(', ') : "Not available";

                console.log(`Question ${questionNumber}, Correct Answer: ${correctAnswer}`);

                currentQuestionNumber += answerChoiceCount; // Increment the question number
                totalQuestions += answerChoiceCount; // Count total number of questions
            }

            // Handle completion questions
            else if (group.type_group_question === "completion") {
                question.box_answers.forEach((boxAnswer, boxIndex) => {
                    const completionNumber = currentQuestionNumber + boxIndex; // This matches how loadPart handles completion inputs

                    correctAnswer = boxAnswer.answer ? boxAnswer.answer : "Not available"; // Assume correct answer is in `boxAnswer.answer`

                    console.log(`Question ${completionNumber}: Correct Answer: ${correctAnswer}`);
                });

                currentQuestionNumber += question.box_answers.length; // Increment by the number of boxes
                totalQuestions += question.box_answers.length; // Count total number of completion questions
            }

            // Handle multiple-choice questions
            else if (group.type_group_question === "multiple-choice") {
                questionNumber = `${currentQuestionNumber}`;

                const correctAnswerIndex = question.answers.findIndex(answer => answer[1] === true); // Find the correct answer
                correctAnswer = correctAnswerIndex !== -1 ? question.answers[correctAnswerIndex][0] : "Not available";

                console.log(`Question ${questionNumber}:  Correct Answer: ${correctAnswer}`);

                currentQuestionNumber++; // Increment the question number for the next question
                totalQuestions++; // Count as a single question
            }
        });
    });
}
</script>';

get_footer();
