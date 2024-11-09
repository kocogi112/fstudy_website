<?php
/*
 * Template Name: Result Template Writing
 * Template Post Type: ieltsspeakingtests
 */

get_header();
$post_id = get_the_ID();

// Get the custom number field value
$custom_number = get_post_meta($post_id, '_ieltsspeakingtests_custom_number', true);

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
$sql = "SELECT testname, id_test, test_type, question_choose, tag, book FROM ielts_speaking_test_list WHERE id_test = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_test);
$stmt->execute();
$result = $stmt->get_result();



echo "<script>console.log('Custom Number doing template: " . esc_js($custom_number) . "');</script>";


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
    $testsavenumber = get_query_var('testsaveieltsspeaking');


    $results = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM save_user_result_ielts_speaking WHERE testsavenumber = %d",
            $testsavenumber
        )
    );
    
    $review = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM ielts_speaking_test_list WHERE id_test = %d",
            $id_test // Replace with the correct variable holding the id_test
        )
    );




    if (!empty($results)) {
    
        $questions = explode(",", $data['question_choose']);
        // Normalize question IDs to handle spaces
        $questions = array_map(function($id) {
            return str_replace(' ', '', trim($id)); // Remove spaces and trim
        }, $questions);
        
            // Display results
            foreach ($results as $result) {
             
                    $task1_data = [];
                    $task2_data = [];
                    $task3_data = [];

                    // Loop through all question IDs in the questions array
                    // Display results
foreach ($results as $result) {
    $task1_data = [];
    $task2_data = [];
    $task3_data = [];

    // Loop through all question IDs in the questions array
    foreach ($questions as $question_id) {
        // Query for Speaking Part 1
        $sql_question = "SELECT speaking_part, id_test, stt, question_content, sample FROM ielts_speaking_part_1_question WHERE id_test = ?";
        $stmt_question = $conn->prepare($sql_question);
        $stmt_question->bind_param("s", $question_id);
        $stmt_question->execute();
        $result_question = $stmt_question->get_result();

        // Fetch all rows for Part 1 and add to task1_data
        while ($row = $result_question->fetch_assoc()) {
            $task1_data[] = $row;
        }

        // Query for Speaking Part 2
        $sql_question_task2 = "SELECT speaking_part, id_test, question_content, topic, sample FROM ielts_speaking_part_2_question WHERE id_test = ?";
        $stmt_question_task2 = $conn->prepare($sql_question_task2);
        $stmt_question_task2->bind_param("s", $question_id);
        $stmt_question_task2->execute();
        $result_question_task_2 = $stmt_question_task2->get_result();

        // Fetch all rows for Part 2 and add to task2_data
        while ($row = $result_question_task_2->fetch_assoc()) {
            $task2_data[] = $row;
        }

        // Query for Speaking Part 3
        $sql_question_task3 = "SELECT speaking_part, stt, id_test, question_content, topic, sample FROM ielts_speaking_part_3_question WHERE id_test = ?";
        $stmt_question_task3 = $conn->prepare($sql_question_task3);
        $stmt_question_task3->bind_param("s", $question_id);
        $stmt_question_task3->execute();
        $result_question_task_3 = $stmt_question_task3->get_result();

        // Fetch all rows for Part 3 and add to task3_data
        while ($row = $result_question_task_3->fetch_assoc()) {
            $task3_data[] = $row;
        }
    }

    // Output or process the data as needed
    error_log(print_r($task1_data, true));
    error_log(print_r($task2_data, true));
    error_log(print_r($task3_data, true));
}


                
                

                    
              
            }
        } else {
            // If no results with testsavenumber
            echo '<p>Không có kết quả tìm thấy cho đề thi này.</p>';
        }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Interface</title>
    <style>
        /* Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            color: #333;
            background-color: #fdf5ef;
        }

        .container1 {
            display: flex;
            flex-direction: column;
            margin: auto;
            padding: 20px;
        }


        .top-nav {
            display: flex;
            gap: 10px;
        }

        .top-nav button {
            padding: 10px 20px;
            background-color: #ffefd8;
            border: 1px solid #ff8f5a;
            border-radius: 5px;
            cursor: pointer;
        }

        .top-nav .active {
            background-color: #ff8f5a;
            color: white;
        }

        .timer {
            display: flex;
            justify-content: space-between;
            width: 100%;
            padding: 10px 0;
            font-size: 14px;
            color: #666;
        }
        .intro-result {
            display: flex;
            justify-content: space-between;
            width: 100%;
            padding: 10px 0;
            font-size: 14px;
            color: #666;
        }

        .submission-time {
            font-style: italic;
        }
        .time-Spent{
            font-style: italic;
        }
        .username{
            font-style: italic;
        }
        .testType .testName{
            font-style: italic;
        }


        .task p {
            margin-bottom: 10px;
        }

        .task-description {
            background-color: #f2f2f2;
            padding: 10px;
            border-radius: 5px;
        }

        .table-container img {
            width: 60%;
            justify-self: center;
            
            margin: 10px 0;
            border-radius: 8px;
        }

        .text-analysis p {
            margin-bottom: 15px;
            line-height: 1.5;
        }

        /* Main Layout Container */
        .main-container-1 {
            display: flex;
            gap: 20px;
           /* margin: auto;*/
            height: 700px;
        }
        .task-buttons {
            display: flex;
            gap: 10px; /* Khoảng cách giữa các button */
            justify-content: flex-start; /* Căn các button sang góc trái */
        }

        .sidebar-buttons {
            display: flex;
            gap: 10px;
        }

        .btn-sidebar {
            font-size: 20px;
            border-radius: 5px;
            background-color: transparent;
            border-color: white;
        }

        /* Adjust the active class directly */
        .btn-sidebar.active {
            background-color: white;
            border-color: white;
        }

        
        

        /* Content Section (Left Column) */
        .content {
            width: 70%; /* Adjust width as needed */
            background-color: white;
            border: 1px solid #e6e6e6;
            border-radius: 10px;
            padding: 20px;
            overflow: auto;
        }

        /* Right Column (Feedback and Sidebar) */
        .right-column {
            width: 30%; /* Adjust width as needed */
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* Feedback Section */
        .feedback {
            background-color: #effbf2;
            padding: 20px;
            border-radius: 10px;
            overflow: auto;
            height: 100%;
        }

        .score {
            text-align: center;
        }

        .score-box h1 {
            color: #3bb75e;
            font-size: 2.5rem;
        }

        .feedback-buttons {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .feedback-buttons button {
            padding: 10px;
            border: none;
            background-color: #ff8f5a;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }

        /* Sidebar Section */
        .sidebar {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            overflow: auto;
        }

        .comment {
            margin-bottom: 15px;
        }

        .tag {
            background-color: #ffef8f;
            padding: 3px 6px;
            border-radius: 3px;
            font-size: 0.8em;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .top-nav {
            margin-bottom: 20px;
        }


        .score-box {
    display: flex;
    align-items: center;
}

.band-score {
    background-color: #28a745;
    color: white;
    padding: 10px;
    border-radius: 5px;
    text-align: center;
    margin-right: 20px;
}

.band-score h1 {
    margin: 0;
    font-size: 24px;
}

.criteria {
    display: flex;
    gap: 10px;
}

.criteria p {
    background-color: #f8f9fa;
    border-radius: 5px;
    padding: 5px 10px;
    margin: 0;
    font-weight: bold;
    text-align: center;
}



/* CSS */
.button-10 {
  display: flex;
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

    </style>
</head>
<script src="https://kit.fontawesome.com/acfb8e1879.js" crossorigin="anonymous"></script>
<body>
    <div class="container1">
    <div class="intro-result">
            <span class="username">Username: <span id="userName"><?php echo esc_html($result->username); ?></span></span><br>
            <span class="testName">Tên đề thi: <span id="testName"><?php echo esc_html($result->testname); ?></span></span><br>
            <span class="testType">Loại đề: <span id="categorytest"><?php echo esc_html($result->test_type); ?></span></span><br>

        </div>
        <!-- Top Navigation -->
        <div class="top-nav">
            <button class="tab-button active" onclick="openTab('original')">Bài gốc</button>
            <button class="tab-button" onclick="openTab('sample')">Sample Essay</button>
            <button class="tab-button" onclick="openTab('youpass')">Sửa bài</button>
            <button class="tab-button" onclick="openTab('suggestions')">Gợi ý nâng cấp</button>
        </div>

        <!-- Timer -->
        <div class="timer">
            <span class="submission-time">Nộp bài: <span id="dateSubmit"><?php echo esc_html($result->dateform); ?></span></span>
        </div>
        
        <div class="task-buttons">
            <button id="overall" class ="active button-10"  onclick="setActiveTask('overall')">Overall</button>
            <button id="task1"  class= "button-10" onclick="setActiveTask('task1')">Speaking Part 1</button>
            <button id="task2" class= "button-10" onclick="setActiveTask('task2')">Speaking Part 2</button>
            <button id="task3" class= "button-10" onclick="setActiveTask('task3')">Speaking Part 3</button>

        </div>

        <!-- Main Content -->
        <div class="main-container-1">
            <div class="content">
                <div class="task">
                    <p><strong>Word count:</strong> <span id="wordCount"></span></p>
                    <p id ="id_test_div">ID Test: </p>
                    <p class="task-description" id="taskDescription"></p>
                </div>
                <div class="table-container" id="taskImageContainer">
                <!-- Image will be dynamically inserted here -->
                </div>

                <div class="tab-content" id="original">
                    <p id="originalContent"></p>
                </div>



                <div class="tab-content active" id="youpass">
                    <div class="text-analysis" id="youpassContent"></div>
                </div>
                
                <div class="tab-content" id="sample">
                    <p id="sampleContent"></p>
                </div>
                <div class="tab-content" id="suggestions">
                    <p id="suggestionsContent"></p>
                </div>
            </div>

            <div class="right-column">
                <!-- Feedback Section -->
                <div class="feedback">
                    <div class="score">
                        <div class="score-box">
                            <div class="band-score">
                                
                                <p id="score"></p>
                            </div>
                            <div class="criteria">
                                <p id="task_achievement_score"></p>
                                <p id="coherence_and_cohesion_score"></p>
                                <p id="lexical_resource_score"></p>
                                <p id="grammatical_range_and_accuracy_score"></p>
                            </div>
                            <p id="score-expand"></p>
                        </div>
                        <p id = "user_level"></p>
                        <p id = "description_level"></p>
                    </div>

                    <div class="feedback-buttons">
                       <!-- <button onclick="addComment()">Thêm bình luận</button>
                        <button onclick="editFeedback()">Chỉnh sửa phản hồi</button>
                    </div> -->
                </div>

                <!-- Sidebar Section -->
                    <div class="sidebar-buttons">
                        <button class ="btn-sidebar active" id ="general-sidebar"><i class="fa-solid fa-circle" style="color: #74C0FC;"></i>General Comment</button>
                        <button class ="btn-sidebar" id ="details-sidebar"> <i class="fa-solid fa-circle" style="color: #B197FC;"></i> Detail Comment</button>
                    </div>
                <div class="sidebar" id="sidebarContent"></div>

            </div>
        </div>
    </div>
    <!--<script src="http://localhost/wordpress/contents/themes/tutorstarter/ielts-writing-toolkit/process_result.js"></script> 
    <script src="http://localhost/wordpress/contents/themes/tutorstarter/ielts-writing-toolkit/submit_result.js"></script> -->

    <script> 
    


 // Decode HTML entities first
const decodeHTML = (html) => {
  const txt = document.createElement('textarea');
  txt.innerHTML = html;
  return txt.value;
};

// Decode the task1BreakdownForm string
const ResultTest = decodeHTML('<?php echo esc_js(wp_kses_post($result->resulttest)); ?>');
const bandDetails = decodeHTML('<?php echo esc_js(wp_kses_post($result->band_detail)); ?>');

const userAnswerAndComment = <?php echo json_encode(json_decode($result->user_answer_and_comment, true), JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_HEX_TAG); ?>;

console.log("User Answer and Comment:", userAnswerAndComment);



function getAnswersForTask(taskData) {
    const { id_tests, stts } = taskData;

    return id_tests.map((id_test, index) => {
        const stt = stts ? stts[index] : null;
        const matches = userAnswerAndComment.filter(item => 
            item.id_question === id_test && (stt === null || item.stt === stt)
        );
        return matches.map(match => match.answer).join(", ");
    }).join("<br>");
}

// Task data descriptions
const task1Data = {
    id_tests: <?php echo json_encode(array_column($task1_data, 'id_test')); ?>,
    stts: <?php echo json_encode(array_column($task1_data, 'stt')); ?>
};
const task2Data = {
    id_tests: <?php echo json_encode(array_column($task2_data, 'id_test')); ?>
};
const task3Data = {
    id_tests: <?php echo json_encode(array_column($task3_data, 'id_test')); ?>,
    stts: <?php echo json_encode(array_column($task3_data, 'stt')); ?>
};
/*console.log("Datee semt ", userAnswerAndComment); // To check the structure
console.log(userAnswerAndComment.unchange); // Check if this is undefined
*/


// Create a DOMParser to parse the decoded HTML string
const parser = new DOMParser();
const doc = parser.parseFromString(ResultTest, 'text/html');
const doc2 = parser.parseFromString(bandDetails, 'text/html');


// Find the element by its ID
const taskAchievementScoreTask1 = doc.getElementById('task_achievement_score_task_1');
const coherenceAndCohesionScoreTask1 = doc.getElementById('coherence_and_cohesion_score_task_1');
const lexicalResourceScoreTask1 = doc.getElementById('lexical_resource_score_task_1');
const grammaticalRangeAndAccuracyScoreTask1 = doc.getElementById('grammatical_range_and_accuracy_score_task_1');
const bandScoreTask1Value = doc.getElementById('overall_band_score_task_1');
const userLevelTask1Value = doc.getElementById('user_level_task_1');
const DescriptionEssayTask1Value = doc.getElementById('description_user_essay_task_1');



const taskAchievementScoreTask2 = doc2.getElementById('task_achievement_score_task_2');
const coherenceAndCohesionScoreTask2 = doc2.getElementById('coherence_and_cohesion_score_task_2');
const lexicalResourceScoreTask2 = doc2.getElementById('lexical_resource_score_task_2');
const grammaticalRangeAndAccuracyScoreTask2 = doc2.getElementById('grammatical_range_and_accuracy_score_task_2');
const bandScoreTask2Value = doc2.getElementById('overall_band_score_task_2');
const userLevelTask2Value = doc2.getElementById('user_level_task_2');
const DescriptionEssayTask2Value = doc2.getElementById('description_user_essay_task_2');


/*
const WordCountTask1Value = doc3.getElementById('word_count_task_1');
const CurrentEssayTask1Value = doc3.getElementById('current_essay_task_1');
const TypeOfEssayTask1Value = doc3.getElementById('type_of_essay_task_1');
const ParagraphCountTask1Value = doc3.getElementById('paragraph_count_task_1');
const NumberOfSpellingAndGrammarTask1Value = doc3.getElementById('number_of_spelling_grammar_error_task_1');
const TotalLinkingWordCountTask1Value = doc3.getElementById('total_linking_word_count_task_1');
const RelationPointEssayAndQuestionTask1Value = doc3.getElementById('relation_point_essay_and_question_task_1');




const WordCountTask2Value = doc4.getElementById('word_count_task_2');
const CurrentEssayTask2Value = doc4.getElementById('current_essay_task_2');
const TypeOfEssayTask2Value = doc4.getElementById('type_of_essay_task_2');
const ParagraphCountTask2Value = doc4.getElementById('paragraph_count_task_2');
const NumberOfSpellingAndGrammarTask2Value = doc4.getElementById('number_of_spelling_grammar_error_task_2');
const TotalLinkingWordCountTask2Value = doc4.getElementById('total_linking_word_count_task_2');
const RelationPointEssayAndQuestionTask2Value = doc4.getElementById('relation_point_essay_and_question_task_2');*/
/*
const WordCountTask1Final = WordCountTask1Value ? WordCountTask1Value.textContent : 'Not found';
const CurrentEssayTask1Final = CurrentEssayTask1Value ? CurrentEssayTask1Value.textContent : 'Not found';
const TypeOfEssayTask1Final = TypeOfEssayTask1Value ? TypeOfEssayTask1Value.textContent : 'Not found';
const ParagraphCountTask1Final = ParagraphCountTask1Value ? ParagraphCountTask1Value.textContent : 'Not found';
const NumberOfSpellingAndGrammarTask1Final = NumberOfSpellingAndGrammarTask1Value ? NumberOfSpellingAndGrammarTask1Value.textContent : 'Not found';
const TotalLinkingWordCountTask1Final = TotalLinkingWordCountTask1Value ? TotalLinkingWordCountTask1Value.textContent : 'Not found';
const RelationPointEssayAndQuestionTask1Final = RelationPointEssayAndQuestionTask1Value ? RelationPointEssayAndQuestionTask1Value.textContent : 'Not found';

const generalSidebarContentTask1 = `
Số từ: ${WordCountTask1Final}<br>
Task: ${CurrentEssayTask1Final}<br> 
Loại essay: ${TypeOfEssayTask1Final}<br>
Số câu: ${ParagraphCountTask1Final}<br>
Số lượng sai ngữ pháp/ từ vựng: ${NumberOfSpellingAndGrammarTask1Final}<br>
Số linking Words: ${TotalLinkingWordCountTask1Final}<br>
Độ mạch lạc: ${RelationPointEssayAndQuestionTask1Final}<br>

`;
*/

/*
const WordCountTask2Final = WordCountTask2Value ? WordCountTask2Value.textContent : 'Not found';
const CurrentEssayTask2Final = CurrentEssayTask2Value ? CurrentEssayTask2Value.textContent : 'Not found';
const TypeOfEssayTask2Final = TypeOfEssayTask2Value ? TypeOfEssayTask2Value.textContent : 'Not found';
const ParagraphCountTask2Final = ParagraphCountTask2Value ? ParagraphCountTask2Value.textContent : 'Not found';
const NumberOfSpellingAndGrammarTask2Final = NumberOfSpellingAndGrammarTask2Value ? NumberOfSpellingAndGrammarTask2Value.textContent : 'Not found';
const TotalLinkingWordCountTask2Final = TotalLinkingWordCountTask2Value ? TotalLinkingWordCountTask2Value.textContent : 'Not found';
const RelationPointEssayAndQuestionTask2Final = RelationPointEssayAndQuestionTask2Value ? RelationPointEssayAndQuestionTask2Value.textContent : 'Not found';
*/
const generalSidebarContentPart1 = `
Số từ: <br>
Task: <br> 
Loại essay: <br>
Số câu: <br>
Số lượng sai ngữ pháp/ từ vựng: <br>
Số linking Words: <br>
Độ mạch lạc: <br>

`;


const generalSidebarContentPart2 = `
Số từ: <br>
Task: <br> 
Loại essay: <br>
Số câu: <br>
Số lượng sai ngữ pháp/ từ vựng: <br>
Số linking Words: <br>
Độ mạch lạc: <br>

`;
const generalSidebarContentPart3 = `
Số từ: <br>
Task: <br> 
Loại essay: <br>
Số câu: <br>
Số lượng sai ngữ pháp/ từ vựng: <br>
Số linking Words: <br>
Độ mạch lạc: <br>

`;

const taskAchievementValueTask1 = taskAchievementScoreTask1 ? taskAchievementScoreTask1.textContent : 'Not found';
const coherenceAndCohesionValueTask1 = coherenceAndCohesionScoreTask1 ? coherenceAndCohesionScoreTask1.textContent : 'Not found';
const lexicalResourceValueTask1 = lexicalResourceScoreTask1 ? lexicalResourceScoreTask1.textContent : 'Not found';
const grammaticalRangeAndAccuracyValueTask1 = grammaticalRangeAndAccuracyScoreTask1 ? grammaticalRangeAndAccuracyScoreTask1.textContent : 'Not found';
const bandScoreTask1 = bandScoreTask1Value ? bandScoreTask1Value.textContent :'Not found';
const userLevelTask1 = userLevelTask1Value ? userLevelTask1Value.textContent :'Not found';
const DescriptionEssayTask1 = DescriptionEssayTask1Value ? DescriptionEssayTask1Value.textContent :'Not found';





const taskAchievementValueTask2 = taskAchievementScoreTask2 ? taskAchievementScoreTask2.textContent : 'Not found';
const coherenceAndCohesionValueTask2 = coherenceAndCohesionScoreTask2 ? coherenceAndCohesionScoreTask2.textContent : 'Not found';
const lexicalResourceValueTask2 = lexicalResourceScoreTask2 ? lexicalResourceScoreTask2.textContent : 'Not found';
const grammaticalRangeAndAccuracyValueTask2 = grammaticalRangeAndAccuracyScoreTask2 ? grammaticalRangeAndAccuracyScoreTask2.textContent : 'Not found';
const bandScoreTask2 = bandScoreTask2Value ? bandScoreTask2Value.textContent :'Not found';
const userLevelTask2 = userLevelTask2Value ? userLevelTask2Value.textContent :'Not found';
const DescriptionEssayTask2 = DescriptionEssayTask2Value ? DescriptionEssayTask2Value.textContent :'Not found';




          // PHP data embedded into JavaScript 
          
const task1Description = `<?php echo json_encode(array_column($task1_data, 'id_test')); ?>: <?php echo json_encode(array_column($task1_data, 'stt')); ?>: <?php echo json_encode(array_column($task1_data, 'question_content')); ?>`;
const task2Description = `<?php echo json_encode(array_column($task2_data, 'id_test')); ?>: <?php echo json_encode(array_column($task2_data, 'question_content')); ?>`;
const task3Description = `<?php echo json_encode(array_column($task3_data, 'id_test')); ?>: <?php echo json_encode(array_column($task3_data, 'stt')); ?>: <?php echo json_encode(array_column($task3_data, 'question_content')); ?>`;


    /*
    const IdQuestionTask1 = <?php echo json_encode($task1_data['id_test']); ?>;
    const IdQuestionTask2 = <?php echo json_encode($task2_data['id_test']); ?>;


    const bandScoreOverall = "<?php echo esc_html($result->band_score); ?>";
    const bandScoreExpandOverall =  "<?php echo esc_html($result->band_score_expand); ?>";
    //const bandScoreTask1 =  "<?php echo esc_html($result->band_score); ?>";
    const bandScoreTask1Expand = `${taskAchievementValueTask1}, ${coherenceAndCohesionValueTask1}, ${lexicalResourceValueTask1}, ${grammaticalRangeAndAccuracyValueTask1} `;
    //const bandScoreTask2 =  "<?php echo esc_html($result->band_score); ?>";
    const bandScoreTask2Expand = `${taskAchievementValueTask2}, ${coherenceAndCohesionValueTask2}, ${lexicalResourceValueTask2}, ${grammaticalRangeAndAccuracyValueTask2} `;

    const taskAchievementTask2 = `${taskAchievementValueTask2}`;
    const coherenceAndCohesionTask2 = `${coherenceAndCohesionValueTask2}`;
    const lexicalResourceTask2 = `${lexicalResourceValueTask2}`;
    const grammaticalRangeAndAccuracyTask2 = `${grammaticalRangeAndAccuracyValueTask2}`;
    const taskAchievementTask1 = `${taskAchievementValueTask1}`;
    const coherenceAndCohesionTask1 = `${coherenceAndCohesionValueTask1}`;
    const lexicalResourceTask1 = `${lexicalResourceValueTask1}`;
    const grammaticalRangeAndAccuracyTask1 = `${grammaticalRangeAndAccuracyValueTask1}`;


    const task1Sample = <?php echo json_encode($task1_data['sample_writing']); ?>;
    const task2Sample = <?php echo json_encode($task2_data['sample_writing']); ?>;
    const task1UserEssay = <?php echo wp_json_encode($result->task1userform); ?>;
    const task2UserEssay = <?php echo wp_json_encode($result->task2userform); ?>;
    */

    // Keep track of the currently active task
let currentTask = 'overall'; // Default task on page load
let detailsCommentTask1 =``;
let detailsCommentTask2 =``;
let detailsCommentTask3 =``;

/*
const tasks = {
    overall: {
        description: "this is overall content",
        user_level:"",
        wordCount: "",
        description_level:"",
        generalSidebar: "Overall general content here.",
        //detailSidebar: "",
        band_score: bandScoreOverall,
        band_score_expand: bandScoreExpandOverall,
        youpass: "Overall Content",
        original: "Overall Original",
        sample: "Overall sample",
        id_question: IdQuestionTask2,
        suggestions: "Overall suggestiong",
        sidebar: [
            "Bình luận 1: Overall 1",
            "Bình luận 2: Overall 2",
            "Bình luận 3: Nên kết luận mạnh mẽ hơn."
        ],
        imageLink: "" // Add image link for task 2
    },
    task1: {
        description: task1Description,
        wordCount: WordCountTask1Final,
        band_score: bandScoreTask1,
        task_achievement_score: taskAchievementTask1,
        coherence_and_cohesion_score: coherenceAndCohesionTask1,
        lexical_resource_score: lexicalResourceTask1,
        grammatical_range_and_accuracy_score: grammaticalRangeAndAccuracyTask1,
        generalSidebar: generalSidebarContentTask1,
        //detailSidebar: `oked ${detailsCommentTask1}`,
        band_score_expand: '',
        user_level: userLevelTask1,
        description_level: DescriptionEssayTask1,
        youpass: "YouPass feedback for Task 1: Your analysis is clear and well-structured, but make sure to include more comparisons between countries.",
        original: task1UserEssay,
        sample: task1Sample,
        id_question: IdQuestionTask1,
        suggestions: "Suggestions for Task 1: Include more statistical data to strengthen your arguments.",
        sidebar: [
            "Bình luận 1: Cần cải thiện phần mở đầu.",
            "Bình luận 2: Số liệu cần rõ ràng hơn.",
            "Bình luận 3: Nên thêm ví dụ cụ thể."
        ],
        imageLink: "<?php echo esc_url($task1_data['image_link']); ?>", // Add image link for task 1
        unchange: {
            wordCount: userAnswerAndComment.unchange.wordCount,
            uniqueWords:  userAnswerAndComment.unchange.uniqueWords,
        }
    }, 
    
               
    task2: {
        description: task2Description,
        band_score: bandScoreTask2,
        task_achievement_score: taskAchievementTask2,
        coherence_and_cohesion_score: coherenceAndCohesionTask2,
        lexical_resource_score: lexicalResourceTask2,
        grammatical_range_and_accuracy_score: grammaticalRangeAndAccuracyTask2,

        wordCount: WordCountTask2Final,
        generalSidebar: generalSidebarContentTask2,
        //detailSidebar: detailsCommentTask2,
        user_level: userLevelTask2,
        description_level: DescriptionEssayTask2,
        band_score_expand: '',
        youpass: "YouPass feedback for Task 2: Your argument is compelling, but it could be enhanced with more concrete examples.",
        original: task2UserEssay,
        sample: task2Sample,
        id_question: IdQuestionTask2,
        suggestions: "Suggestions for Task 2: Ensure that each paragraph focuses on a single idea to improve coherence.",
        sidebar: [
            "Bình luận 1: Cần tăng cường lý lẽ trong bài viết.",
            "Bình luận 2: Tránh sử dụng ngôn ngữ quá phức tạp.",
            "Bình luận 3: Nên kết luận mạnh mẽ hơn."
        ],
        imageLink: "",
       
    },
    task3: {
        description: task2Description,
        band_score: bandScoreTask2,
        task_achievement_score: taskAchievementTask2,
        coherence_and_cohesion_score: coherenceAndCohesionTask2,
        lexical_resource_score: lexicalResourceTask2,
        grammatical_range_and_accuracy_score: grammaticalRangeAndAccuracyTask2,

        wordCount: WordCountTask2Final,
        generalSidebar: generalSidebarContentTask2,
        //detailSidebar: detailsCommentTask2,
        user_level: userLevelTask2,
        description_level: DescriptionEssayTask2,
        band_score_expand: '',
        youpass: "YouPass feedback for Speaking Part 3: Your argument is compelling, but it could be enhanced with more concrete examples.",
        original: task2UserEssay,
        sample: task2Sample,
        id_question: IdQuestionTask2,
        suggestions: "Suggestions for Task 3: Ensure that each paragraph focuses on a single idea to improve coherence.",
        sidebar: [
            "Bình luận 1: Cần tăng cường lý lẽ trong bài viết.",
            "Bình luận 2: Tránh sử dụng ngôn ngữ quá phức tạp.",
            "Bình luận 3: Nên kết luận mạnh mẽ hơn."
        ],
        imageLink: "",
       
    }
}; */


const tasks = {
    overall: {
        description: "this is overall content",
        user_level:"",
        wordCount: "",
        description_level:"",
        generalSidebar: "Overall general content here.",
        //detailSidebar: "",
        band_score: "band score overall",
        band_score_expand: "band score overall expand",
        youpass: "Overall Content",
        original: "Overall Original",
        sample: "Overall sample",
        id_question: "ID Ques overall",
        suggestions: "Overall suggestiong",
        sidebar: [
            "Bình luận 1: Overall 1",
            "Bình luận 2: Overall 2",
            "Bình luận 3: Nên kết luận mạnh mẽ hơn."
        ],
        imageLink: "" // Add image link for task 2
    },
    task1: {
        description: task1Description,
        wordCount: "task 1 wc",
        band_score: "task 1 bancd",
        task_achievement_score: "task 1 ta",
        coherence_and_cohesion_score: "task 1 cc",
        lexical_resource_score: "task 1 lx",
        grammatical_range_and_accuracy_score: "task 1 gra",
        generalSidebar: generalSidebarContentPart1,
        detailSidebar: `"task 1 detail sb" `,
        band_score_expand: '',
        user_level: "task 1 ul",
        description_level: "task 1 des lev",
        youpass: "YouPass feedback for Task 1: Your analysis is clear and well-structured, but make sure to include more comparisons between countries.",
        original: getAnswersForTask(task1Data), // Matches id_test and stt
        sample: "task 1 ",
        id_question: "task 1 - ID Question",
        suggestions: "Suggestions for Task 1: Include more statistical data to strengthen your arguments.",
        sidebar: [
            "Bình luận 1: Cần cải thiện phần mở đầu.",
            "Bình luận 2: Số liệu cần rõ ràng hơn.",
            "Bình luận 3: Nên thêm ví dụ cụ thể."
        ],
        imageLink: "", // Add image link for task 1
        
    }, 
    
               
    task2: {
        description: task2Description,
        band_score: "task 2 ",
        task_achievement_score: "task 2 ",
        coherence_and_cohesion_score: "task 2 ",
        lexical_resource_score: "task 2 ",
        grammatical_range_and_accuracy_score: "task 2 ",

        wordCount: "task 2 ",
        generalSidebar: generalSidebarContentPart2,
        //detailSidebar: detailsCommentTask2,
        user_level: "task 2 ",
        description_level: "task 2 ",
        band_score_expand: '',
        youpass: "YouPass feedback for Task 2: Your argument is compelling, but it could be enhanced with more concrete examples.",
        original: getAnswersForTask(task2Data), // Only matches id_test
        sample: "task 2 ",
        id_question: "task 2 ",
        suggestions: "Suggestions for Task 2: Ensure that each paragraph focuses on a single idea to improve coherence.",
        sidebar: [
            "Bình luận 1: Cần tăng cường lý lẽ trong bài viết.",
            "Bình luận 2: Tránh sử dụng ngôn ngữ quá phức tạp.",
            "Bình luận 3: Nên kết luận mạnh mẽ hơn."
        ],
        imageLink: "",
       
    },
    task3: {
        description: task3Description,
        band_score: "task 3 ",
        task_achievement_score: "task 3 ",
        coherence_and_cohesion_score: "task 3 ",
        lexical_resource_score: "task 3 ",
        grammatical_range_and_accuracy_score: "task 3 ",

        wordCount: "task 3 ",
        generalSidebar: generalSidebarContentPart3,
        //detailSidebar: detailsCommentTask2,
        user_level: "task 3 ",
        description_level: "task 3 ",
        band_score_expand: '',
        youpass: "YouPass feedback for Speaking Part 3: Your argument is compelling, but it could be enhanced with more concrete examples.",
        original: getAnswersForTask(task3Data), // Matches id_test and stt
        sample: "task 3 ",
        id_question: "task 3 ",
        suggestions: "Suggestions for Task 3: Ensure that each paragraph focuses on a single idea to improve coherence.",
        sidebar: [
            "Bình luận 1: Cần tăng cường lý lẽ trong bài viết.",
            "Bình luận 2: Tránh sử dụng ngôn ngữ quá phức tạp.",
            "Bình luận 3: Nên kết luận mạnh mẽ hơn."
        ],
        imageLink: "",
       
    }
};
//processEssay();
console.log(tasks)
// Function to set sidebar content based on the task and selected type (general/details)
function setSidebarContent(task, type) {
    const taskData = tasks[task];
    
    let sidebarContent = '';
    let activeSidebar = '';

    // Depending on the type (general or details), set the sidebar content
    if (type === 'general') {
        sidebarContent = taskData.generalSidebar || '';  // Default to general sidebar content for the task
        activeSidebar = 'general-sidebar';  // Set active to general

    } else if (type === 'details') {
        sidebarContent = taskData.detailSidebar || '';  // Default to details sidebar content for the task
        activeSidebar = 'details-sidebar';  // Set active to details

    }

    document.getElementById('sidebarContent').innerHTML = sidebarContent;
    document.getElementById('general-sidebar').classList.remove('active');
    document.getElementById('details-sidebar').classList.remove('active');

    // Add active class to the selected sidebar button
    document.getElementById(activeSidebar).classList.add('active');
}

// Add event listeners for switching between General and Details Sidebar
document.getElementById('general-sidebar').addEventListener('click', function() {
    setSidebarContent(currentTask, 'general');  // Show General Sidebar content for the current task
});

document.getElementById('details-sidebar').addEventListener('click', function() {
    setSidebarContent(currentTask, 'details');  // Show Details Sidebar content for the current task
});

document.getElementById('details-sidebar').addEventListener('click', function() {
    setSidebarContent(currentTask, 'details');  // Show Details Sidebar content for the current task

    // Remove 'active' class from general button and add it to details button
    document.getElementById('general-sidebar').classList.remove('active');
    this.classList.add('active');
});


// Function to set active task and update content
function setActiveTask(task) {
    currentTask = task;  // Update the active task
    
    const taskData = tasks[task];
    
    // Update content sections for the active task
    document.getElementById('taskDescription').innerText = taskData.description;
    document.getElementById('originalContent').innerHTML = taskData.original;
    document.getElementById('youpassContent').innerText = taskData.youpass;
    document.getElementById('sampleContent').innerHTML = taskData.sample;
    document.getElementById('suggestionsContent').innerText = taskData.suggestions;
    document.getElementById("score").innerText = taskData.band_score; 
    document.getElementById("wordCount").innerText = taskData.wordCount; 
    document.getElementById("score-expand").innerText = taskData.band_score_expand;
   
    document.getElementById("task_achievement_score").innerText = taskData.task_achievement_score;
    document.getElementById("coherence_and_cohesion_score").innerText = taskData.coherence_and_cohesion_score;
    document.getElementById("lexical_resource_score").innerText = taskData.lexical_resource_score;
    document.getElementById("grammatical_range_and_accuracy_score").innerText = taskData.grammatical_range_and_accuracy_score;
               


    document.getElementById("user_level").innerText = taskData.user_level;
    document.getElementById("description_level").innerText = taskData.description_level;

    // Update sidebar with comments
    document.getElementById('sidebarContent').innerHTML = taskData.sidebar.map(comment => `<div class="comment">${comment}</div>`).join('');

    // Update image container with task-specific image
   
    // Update image container with task-specific image  
    if (taskData.imageLink && taskData.imageLink.trim() !== "") {
        document.getElementById('taskImageContainer').innerHTML = `<img src="${taskData.imageLink}" alt="Chart Image">`;
    } else {
        document.getElementById('taskImageContainer').innerHTML = ""; // Clear image container if no image link
    }    

    if (taskData.task_achievement_score && taskData.task_achievement_score.trim() !== "") {
        document.getElementById('task_achievement_score').innerHTML = `${taskData.task_achievement_score}`;
    } else {
        document.getElementById('task_achievement_score').innerHTML = "";
    } 

    if (taskData.coherence_and_cohesion_score && taskData.coherence_and_cohesion_score.trim() !== "") {
        document.getElementById('coherence_and_cohesion_score').innerHTML = `${taskData.coherence_and_cohesion_score}`;
    } else {
        document.getElementById('coherence_and_cohesion_score').innerHTML = "";
    }
    if (taskData.lexical_resource_score && taskData.lexical_resource_score.trim() !== "") {
        document.getElementById('lexical_resource_score').innerHTML = `${taskData.lexical_resource_score}`;
    } else {
        document.getElementById('lexical_resource_score').innerHTML = "";
    }
    if (taskData.grammatical_range_and_accuracy_score && taskData.grammatical_range_and_accuracy_score.trim() !== "") {
        document.getElementById('grammatical_range_and_accuracy_score').innerHTML = `${taskData.grammatical_range_and_accuracy_score}`;
    } else {
        document.getElementById('grammatical_range_and_accuracy_score').innerHTML = "";
    }

    if (taskData.band_score_expand && taskData.band_score_expand.trim() !== "") {
        document.getElementById('score-expand').innerHTML = `${taskData.band_score_expand}`;
    } else {
        document.getElementById('score-expand').innerHTML = "";
    }

    document.getElementById('id_test_div').innerHTML = `${taskData.id_question}`;

    // Reset the tab to "original" by default
    openTab('original');
    
    // Show General sidebar content for the active task
    setSidebarContent(task, 'general');
    
}

// Function to open a specific tab
function openTab(tabName) {
    // Hide all tab content
    var contents = document.querySelectorAll('.tab-content');
    contents.forEach(function(content) {
        content.classList.remove('active');
    });

    // Show the selected tab content
    document.getElementById(tabName).classList.add('active');

    // Update active button state
    var buttons = document.querySelectorAll('.tab-button');
    buttons.forEach(function(button) {
        button.classList.remove('active');
    });
    document.querySelector(`.tab-button[onclick="openTab('${tabName}')"]`).classList.add('active');
}

// Set initial active task on page load
window.onload = function() {
    setActiveTask('overall');
};
</script>



</body>
</html>

<?php

get_footer();