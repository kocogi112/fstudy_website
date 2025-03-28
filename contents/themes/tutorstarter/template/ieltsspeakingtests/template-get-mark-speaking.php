<?php
/*
 * Template Name: Result Template Writing
 * Template Post Type: ieltsspeakingtests
 */

$post_id = get_the_ID();

// Get the custom number field value
//$custom_number = get_post_meta($post_id, '_ieltsspeakingtests_custom_number', true);
global $wpdb; // Use global wpdb object to query the DB

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

$testsavenumber = get_query_var('testsaveieltsspeaking');


    $results = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM save_user_result_ielts_speaking WHERE testsavenumber = %d",
            $testsavenumber
        )
    );



    // Assign $custom_number using the id_test field from the query result if available
$custom_number = 0; // Default value
if (!empty($results)) {
    // Assuming you want the first result's id_test
    $custom_number = intval($results[0]->idtest);

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
    $testname = $data['testname'];
    add_filter('document_title_parts', function ($title) use ($testname) {
        $title['title'] = $testname; // Use the $testname variable from the outer scope
        return $title;
    });
    
    
} else {
    echo "<script>console.log('No data found for the given id_test');</script>";
}



get_header();


    // Get current user's username
    $current_user = wp_get_current_user();
    $current_username = $current_user->user_login;
    
    
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

        audio{
            width: 100%;
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
            /*display: none;*/
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
            <button class="tab-button active" onclick="openTab('question_seperate')">Bài gốc</button>
            <button class="tab-button" onclick="openTab('sample_seperate')">Sample Essay</button>
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

                    <div class="tab-content" id = "question_seperate"></div>

                </div>

                <div class="table-container" id="taskImageContainer">
                <!-- Image will be dynamically inserted here -->
                </div>

                
                <div class="tab-content" id = "sample_seperate"></div>



                <div class="tab-content active" id="youpass">
                    <div class="text-analysis" id="youpassContent"></div>
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
                                <p id="lexical_resource_score"></p>
                                <p id="fluency_and_coherence_score"></p>
                                <p id="grammatical_range_and_accuracy_score"></p>
                                <p id="pronunciation_score"></p>
                            </div>
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

        // Find matching answers for each question based on id_test and stt
        const matches = userAnswerAndComment.filter(item => 
            item.id_question === id_test && (stt === null || item.stt === stt)
        );

        // Get the answers as a string
        const answers = matches.map(match => match.answer).join(", ");
        const checkFluencyAndCoherenceComment = matches.map(match => match.checkFluencyAndCoherenceSend.checkFluencyAndCoherenceComment).join(", ");
        const checkLexicalResourceComment = matches.map(match => match.checkLexicalResourceSend.checkLexicalResourceComment).join(", ");
        const checkGrammarticalRangeAndAccuracyComment = matches.map(match => match.checkGrammarticalRangeAndAccuracySend.checkGrammarticalRangeAndAccuracyComment).join(", ");
        const link_audios =  matches.map(match => match.link_audio).join(", ");
        
        // Return the question's answers along with the question content
        return {
            id_test: id_test,
            stt: stt,
            answers: answers,
            link_audios: link_audios,
            checkFluencyAndCoherenceComment: checkFluencyAndCoherenceComment,
            checkLexicalResourceComment: checkLexicalResourceComment,
            checkGrammarticalRangeAndAccuracyComment: checkGrammarticalRangeAndAccuracyComment,
        };
    });
}
// Ensure proper encoding of PHP arrays as JavaScript objects
const task1Data = {
    id_tests: <?php echo json_encode(array_column($task1_data, 'id_test')); ?>,
    stts: <?php echo json_encode(array_column($task1_data, 'stt')); ?>,
    question_contents: <?php echo json_encode(array_map('html_entity_decode', array_column($task1_data, 'question_content'))); ?>,
    samples: <?php echo json_encode(array_map('html_entity_decode', array_column($task1_data, 'sample'))); ?>

};

const task2Data = {
    id_tests: <?php echo json_encode(array_column($task2_data, 'id_test')); ?>,
    question_contents: <?php echo json_encode(array_map('html_entity_decode', array_column($task2_data, 'question_content'))); ?>,
    samples: <?php echo json_encode(array_map('html_entity_decode', array_column($task2_data, 'sample'))); ?>

};

const task3Data = {
    id_tests: <?php echo json_encode(array_column($task3_data, 'id_test')); ?>,
    stts: <?php echo json_encode(array_column($task3_data, 'stt')); ?>,
    question_contents: <?php echo json_encode(array_map('html_entity_decode', array_column($task3_data, 'question_content'))); ?>,
    samples: <?php echo json_encode(array_map('html_entity_decode', array_column($task3_data, 'sample'))); ?>

};

/*console.log("Datee semt ", userAnswerAndComment); // To check the structure
console.log(userAnswerAndComment.unchange); // Check if this is undefined
*/


// Create a DOMParser to parse the decoded HTML string
const parser = new DOMParser();
const doc = parser.parseFromString(bandDetails, 'text/html');
//const doc2 = parser.parseFromString(ResultTest, 'text/html');





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



          // PHP data embedded into JavaScript 
          
const task1Description = `<?php echo json_encode(array_column($task1_data, 'id_test')); ?>: <?php echo json_encode(array_column($task1_data, 'stt')); ?>: <?php echo json_encode(array_column($task1_data, 'question_content')); ?>`;
const task2Description = `<?php echo json_encode(array_column($task2_data, 'id_test')); ?>: <?php echo json_encode(array_column($task2_data, 'question_content')); ?>`;
const task3Description = `<?php echo json_encode(array_column($task3_data, 'id_test')); ?>: <?php echo json_encode(array_column($task3_data, 'stt')); ?>: <?php echo json_encode(array_column($task3_data, 'question_content')); ?>`;
// Assuming task1Data, task2Data, and task3Data are now properly formatted and contain the data
// Modified render function to ensure it loads task-specific content
function renderQuestionsWithAnswers(taskData, partIndex, hasStt = true) {
    const idTests = taskData.id_tests;
    const stts = taskData.stts || [];
    const questionContents = taskData.question_contents;

    const taskAnswers = getAnswersForTask(taskData);

    idTests.forEach((id_test, index) => {
        const stt = hasStt ? stts[index] : null;
        const questionContent = questionContents[index];
        const answers = taskAnswers[index].answers;
        const link_audios = taskAnswers[index].link_audios;

        const checkFluencyAndCoherenceComment = taskAnswers[index].checkFluencyAndCoherenceComment;
        const checkLexicalResourceComment = taskAnswers[index].checkLexicalResourceComment;
        const checkGrammarticalRangeAndAccuracyComment = taskAnswers[index].checkGrammarticalRangeAndAccuracyComment;

        const uniqueId = hasStt
            ? `part-${partIndex}-idTest-${id_test}-stt-${stt}`
            : `part-${partIndex}-idTest-${id_test}`;
        const audioContainerId = `audioContainer-${uniqueId}`;

        // Append question content and answers
        let questionContainer = document.getElementById("question_seperate");
        questionContainer.innerHTML += `
            <div class="task-container" id="original">
                <p class="task-description" id="taskDescription-${uniqueId}">
                    ${questionContent}
                </p>
                <div>
                    <p id="originalContent-${uniqueId}">
                        ${answers ? `<strong>Your Answers:</strong> ${answers}<br>` : ''}
                        ${link_audios ? `<div id="${audioContainerId}"><strong>Audio:</strong> <audio controls src="${link_audios}"></audio></div><br>` : ''}
                        ${checkFluencyAndCoherenceComment ? `<strong>Fluency and Coherence:</strong> ${checkFluencyAndCoherenceComment}<br>` : ''}
                        ${checkLexicalResourceComment ? `<strong>Lexical Resource:</strong> ${checkLexicalResourceComment}<br>` : ''}
                        ${checkGrammarticalRangeAndAccuracyComment ? `<strong>Grammatical Range and Accuracy:</strong> ${checkGrammarticalRangeAndAccuracyComment}` : ''}
                    </p>
                </div>
            </div>
        `;
    });
}


async function fetchAndPlayAudio(url, audioContainerId) {
    try {
        const response = await fetch(url);
        if (response.ok) {
            const blob = await response.blob();
            const audioUrl = URL.createObjectURL(blob);
            document.getElementById(audioContainerId).innerHTML = `<strong>Audio:</strong> <audio controls src="${audioUrl}"></audio>`;
        } else {
            console.error("Failed to retrieve audio from file.io.");
            document.getElementById(audioContainerId).innerHTML = `<strong>Audio unavailable.</strong>`;
        }
    } catch (error) {
        console.error("Error fetching audio:", error);
        document.getElementById(audioContainerId).innerHTML = `<strong>Audio unavailable.</strong>`;
    }
}


function getSampleForQuestion(taskData, partIndex, hasStt = true) {
    const idTests = taskData.id_tests;
    const stts = taskData.stts || [];
    const questionContents = taskData.question_contents;
    const samples = taskData.samples;

    const taskAnswers = getAnswersForTask(taskData);

    idTests.forEach((id_test, index) => {
        const stt = hasStt ? stts[index] : null;
        const questionContent = questionContents[index];
        const sample = samples[index];

        const answers = taskAnswers[index].answers;


        const uniqueId = hasStt
            ? `part-${partIndex}-idTest-${id_test}-stt-${stt}`
            : `part-${partIndex}-idTest-${id_test}`;

        // Append question content and answers
        let sampleContainer = document.getElementById("sample_seperate");
        sampleContainer.innerHTML += `
         <div  class="task-container"  id="sample">
                <p class="task-description" id="question-${uniqueId}">
                    ${questionContent}
                </p>


                    <p id="sampleContent-"${uniqueId}">
                        Sample: ${sample}
                    </p>
                </div>


            </div>
        `;
    });
}

function renderOverall(){
    let questionContainer = document.getElementById("question_seperate");
        questionContainer.innerHTML += `
            <div class="task-container">
                <p class="task-description" id="overall-comment">
                    Hello overall
                </p>
                <div class="tab-content" id="question_seperate">
                    <p id="originalContent-comment">
                       Hello overall original comment
                    </p>
                </div>
            </div>
        `;
 

}


// Example usage of setActiveTask for each button click
document.getElementById("task1").addEventListener("click", () => setActiveTask('task1'));
document.getElementById("task2").addEventListener("click", () => setActiveTask('task2'));
document.getElementById("task3").addEventListener("click", () => setActiveTask('task3'));
document.getElementById("overall").addEventListener("click", () => setActiveTask('overall'));


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



const tasks = {
    overall: {
        description: "this is overall content",
        user_level:"",
        wordCount: "task overall wc", 
        wordCount: "",
        description_level:"",
        generalSidebar: "Overall general content here.",
        detailSidebar: "",
        youpass: "Overall Content",
        question_seperate: "Overall Original",
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
        generalSidebar: generalSidebarContentPart1,
        detailSidebar: `"task 1 detail sb" `,
        user_level: "task 1 ul",
        description_level: "task 1 des lev",
        youpass: "YouPass feedback for Task 1: Your analysis is clear and well-structured, but make sure to include more comparisons between countries.",
        question_seperate: getAnswersForTask(task1Data), // Matches id_test and stt
        sample: getSampleForQuestion(task1Data),
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
        wordCount: "task 2 ",
        generalSidebar: generalSidebarContentPart2,
        detailSidebar: `"task 2 detail 2" `,
        user_level: "task 2 ",
        description_level: "task 2 ",
        youpass: "YouPass feedback for Task 2: Your argument is compelling, but it could be enhanced with more concrete examples.",
        question_seperate: getAnswersForTask(task2Data), // Only matches id_test
        sample: getSampleForQuestion(task2Data),
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
        wordCount: "task 3 ",
        generalSidebar: generalSidebarContentPart3,
        detailSidebar: "detailsCommentTask3",
        user_level: "task 3 ",
        description_level: "task 3 ",
        youpass: "YouPass feedback for Speaking Part 3: Your argument is compelling, but it could be enhanced with more concrete examples.",
        question_seperate: getAnswersForTask(task3Data), // Matches id_test and stt
        sample: getSampleForQuestion(task3Data),
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

// Find the element by its ID
const final_lexical_resource_point = doc.getElementById('final_lexical_resource_point').textContent.trim();
const final_fluency_and_coherence_point = doc.getElementById('final_fluency_and_coherence_point').textContent.trim();
const final_grammatical_range_and_accuracy_point = doc.getElementById('final_grammatical_range_and_accuracy_point').textContent.trim();
const final_pronunciation_point = doc.getElementById('final_pronunciation_point').textContent.trim();
const bandScoreTask1Value = doc.getElementById('overall_band_score_task_1');
const userLevelTask1Value = doc.getElementById('user_level_task_1');
const DescriptionEssayTask1Value = doc.getElementById('description_user_essay_task_1');



// Now you can log the actual values
console.log('Lexical Resource:', final_lexical_resource_point);
console.log('Fluency and Coherence:', final_fluency_and_coherence_point);
console.log('Grammatical Range and Accuracy:', final_grammatical_range_and_accuracy_point);
console.log('Pronunciation:', final_pronunciation_point);
// Function to set active task and update content
function setActiveTask(task) {
    currentTask = task;  // Update the active task
    document.getElementById("question_seperate").innerHTML = "";
    document.getElementById("sample_seperate").innerHTML = "";

    const taskData = tasks[task];
    
    // Update content sections for the active task
   // document.getElementById('taskDescription').innerText = taskData.description;
    //document.getElementById('originalContent').innerHTML = taskData.original;
    document.getElementById('youpassContent').innerText = taskData.youpass;
    document.getElementById('suggestionsContent').innerText = taskData.suggestions;
    document.getElementById("wordCount").innerText = taskData.wordCount; 
   


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
        document.getElementById('score').textContent  = `${ResultTest}`;
        document.getElementById('lexical_resource_score').textContent  = `${final_lexical_resource_point}`;
        document.getElementById('fluency_and_coherence_score').textContent  = `${final_fluency_and_coherence_point}`;
        document.getElementById('grammatical_range_and_accuracy_score').textContent  = `${final_grammatical_range_and_accuracy_point}`;
        document.getElementById('pronunciation_score').textContent  = `${final_pronunciation_point}`;
    


    document.getElementById('id_test_div').innerHTML = `${taskData.id_question}`;
    switch (task) {
        case 'task1':
            renderQuestionsWithAnswers(task1Data, 1);  // Load Speaking Part 1
            getSampleForQuestion(task1Data, 1);
            break;
        case 'task2':
            renderQuestionsWithAnswers(task2Data, 2, false);  // Load Speaking Part 2
            getSampleForQuestion(task2Data, 2);

            break;
        case 'task3':
            renderQuestionsWithAnswers(task3Data, 3);  // Load Speaking Part 3
            getSampleForQuestion(task3Data, 3);

            break;
        case 'overall':
            renderOverall();  // Load Speaking Part 3
            break;
        default:
            // Handle the 'overall' tab or any default tab behavior here
            break;
    }
    // Reset the tab to "original" by default
    openTab('question_seperate');
    
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

//// Set initial active task on page load
window.onload = function() {
    setActiveTask('overall');
};
</script>



</body>
</html>

<?php

get_footer();