<?php
/*
 * Template Name: Result Template Writing
 * Template Post Type: ieltswritingtests
 */

$post_id = get_the_ID();

// Get the custom number field value
//$custom_number = get_post_meta($post_id, '_ieltswritingtests_custom_number', true);
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



$testsavenumber = get_query_var('testsaveieltswriting');


    $results = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM save_user_result_ielts_writing WHERE testsavenumber = %d",
            $testsavenumber
        )
    );



$custom_number = 0; // Default value
if (!empty($results)) {
    // Assuming you want the first result's id_test
    $custom_number = $results[0]->idtest;

}


// Set custom_number as id_test
$id_test = $custom_number;

// Prepare the SQL statement
$sql = "SELECT testname, time, test_type, question_choose, tag, book FROM ielts_writing_test_list WHERE id_test = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $id_test);
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
            "SELECT * FROM ielts_writing_test_list WHERE id_test = %d",
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
             
        $task1_data = null;
        $task2_data = null;

        


        // Loop through all question IDs in the questions array
        foreach ($questions as $question_id) {
              // Query for Task 1
                $sql_question = "SELECT task, id_test, question_type, question_content, image_link, sample_writing FROM ielts_writing_task_1_question WHERE id_test = ?";
                $stmt_question = $conn->prepare($sql_question);
                $stmt_question->bind_param("s", $question_id);
                $stmt_question->execute();
                $result_question = $stmt_question->get_result();

                // Query for Task 2
                $sql_question_task2 = "SELECT task, id_test, question_type, question_content, topic, sample_writing FROM ielts_writing_task_2_question WHERE id_test = ?";
                $stmt_question_task2 = $conn->prepare($sql_question_task2);
                $stmt_question_task2->bind_param("s", $question_id);
                $stmt_question_task2->execute();
                $result_question_task_2 = $stmt_question_task2->get_result();

            if ($result_question->num_rows > 0) {
                $task1_data = $result_question->fetch_assoc();

                error_log(print_r($task1_data, true)); // Check the output in your PHP error log
             /*   echo '<p>ID Test Task 1: '.  $task1_data['id_test'].  '</p>'; 
                echo '<p>Question Task 1: '.  $task1_data['question_content'].  '</p>'; 
                echo '<p>Image Link: '.  $task1_data['image_link'].  '</p>'; 
                echo '<p>Sample Task 1: '.  $task1_data['sample_writing'].  '</p>'; */

                
            }

            if ($result_question_task_2->num_rows > 0) {
                $task2_data = $result_question_task_2->fetch_assoc();

                error_log(print_r($task2_data, true)); // Check the output in your PHP error log
              /*  echo '<p>ID Test Task 2: '.  $task2_data['id_test'].  '</p>'; 
                echo '<p>Question Task 2: '.  $task2_data['question_content'].  '</p>'; 
                echo '<p>Sample Task 2: '.  $task2_data['sample_writing'].  '</p>'; */

                
            }

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
            display: none;
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

        
        .need_replace{
            color: red;
        }

        /* Content Section (Left Column) */
        .content {
            width: 100%; /* Adjust width as needed */
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




        .task-buttons {
            display: flex;
            gap: 10px; /* Khoảng cách giữa các button */
            justify-content: flex-start; /* Căn các button sang góc trái */

        }

        .task-buttons button {
            padding: 10px 20px;
            background-color: #ffefd8;
            border: 1px solid #ff8f5a;
            border-radius: 5px;
            cursor: pointer;
        }

        .task-buttons .active {
            background-color: #ff8f5a;
            color: white;
        }





.improvement-box {
    border: 1px solid #ccc;
    border-radius: 8px;
    padding: 12px;
    margin-bottom: 10px;
    background-color: #f9f9f9;
    box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
}

.improvement-box p {
    margin: 5px 0;
}


    </style>
</head>
<script src="https://kit.fontawesome.com/acfb8e1879.js" crossorigin="anonymous"></script>
<body>
    <div class="container1">
    <div class="intro-result">
            <span class="time-Spent">Tổng thời gian làm bài: <span id="timeSpent"><?php echo esc_html($result->timeSpent); ?></span></span><br>
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
            <button id="overall" class ="button-10 active"  onclick="setActiveTask('overall')">Overall</button>
            <button id="task1"  class= "button-10" onclick="setActiveTask('task1')">Writing Task 1</button>
            <button id="task2" class= "button-10" onclick="setActiveTask('task2')">Writing Task 2</button>
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

                <div class="tab-content active" id="youpass">
                    <div class="text-analysis" id="youpassContent"></div>
                </div>
                <div class="tab-content" id="original">
                    <p id="originalContent"></p>
                </div>
                <div class="tab-content" id="sample">
                    <p id="sampleContent"></p>
                </div>
                <div class="tab-content" id="suggestions">
                    <p id="suggestionsContent"></p>
                </div>
                <div id = "someElement" ></div>
            </div>

            <div class="right-column" style = "display: none;" >
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
                        <button class ="btn-sidebar active" id ="general-sidebar"><i class="fa-solid fa-circle" style="color: #74C0FC;"></i>Overview</button>
                        <button class ="btn-sidebar" id ="details-sidebar"> <i class="fa-solid fa-circle" style="color: #B197FC;"></i> Detail Comment</button>
                        <button class ="btn-sidebar" id ="suggestion-sidebar"> <i class="fa-solid fa-circle" style="color: #B197FC;"></i> Suggestion</button>

                    </div>
                <div class="sidebar" id="sidebarContent"></div>

            </div>
        </div>
    </div>
   <!-- <script src="http://localhost/wordpress/contents/themes/tutorstarter/ielts-writing-toolkit/process_result.js"></script> 
    <script src="http://localhost/wordpress/contents/themes/tutorstarter/ielts-writing-toolkit/submit_result.js"></script>  -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script> 
    

   let task_achievement1_1_comment = ``; 
   let task_achievement1_2_comment = ``; 
   let task_achievement1_3_comment = ``;
   let task_achievement1_4_comment = ``;


   let task_achievement2_1_comment = ``; 
   let task_achievement2_2_comment = ``; 
   let task_achievement2_3_comment = ``;
   let task_achievement2_4_comment = ``;
   

   let coherenceandcohesion1_1_comment = ``; 
   let coherenceandcohesion1_2_comment = ``; 
   let coherenceandcohesion1_3_comment = ``;
   let coherenceandcohesion1_4_comment = ``;

   let coherenceandcohesion2_1_comment = ``; 
   let coherenceandcohesion2_2_comment = ``; 
   let coherenceandcohesion2_3_comment = ``;
   let coherenceandcohesion2_4_comment = ``;


   let lexical_resource1_1_comment = ``;
   let lexical_resource1_2_comment = ``;
   let lexical_resource1_3_comment = ``;
   let lexical_resource1_4_comment = ``;
   let lexical_resource2_1_comment = ``;
   let lexical_resource2_2_comment = ``;
   let lexical_resource2_3_comment = ``;
   let lexical_resource2_4_comment = ``;

   


   let grammatical_range_and_accuracy1_1_comment = ``;
   let grammatical_range_and_accuracy1_2_comment = ``;
   let grammatical_range_and_accuracy1_3_comment = ``;
   let grammatical_range_and_accuracy1_4_comment = ``;
   let grammatical_range_and_accuracy2_1_comment = ``;
   let grammatical_range_and_accuracy2_2_comment = ``;
   let grammatical_range_and_accuracy2_3_comment = ``;
   let grammatical_range_and_accuracy2_4_comment = ``;

 // Decode HTML entities first
const decodeHTML = (html) => {
  const txt = document.createElement('textarea');
  txt.innerHTML = html;
  return txt.value;
};

// Decode the task1BreakdownForm string
const decodedTask1BreakdownForm = decodeHTML('<?php echo esc_js(wp_kses_post($result->task1breakdownform)); ?>');
const decodedTask2BreakdownForm = decodeHTML('<?php echo esc_js(wp_kses_post($result->task2breakdownform)); ?>');

const decodedTask1Summary = decodeHTML('<?php echo esc_js(wp_kses_post($result->task1summaryuserform)); ?>');
const decodedTask2Summary = decodeHTML('<?php echo esc_js(wp_kses_post($result->task2summaryuserform)); ?>');

const dataTask1Essay = <?php echo json_encode(json_decode($result->task1detailscommentform, true), JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_HEX_TAG); ?>;
const dataTask2Essay = <?php echo json_encode(json_decode($result->task2detailscommentform, true), JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_HEX_TAG); ?>;





    // Decode và parse JSON từ PHP
    const decodeUserEssay = decodeHTML('<?php echo esc_js(wp_kses_post($result->user_essay)); ?>');
    const decodeUserEssayOverallAndSuggestion = decodeHTML('<?php echo esc_js(wp_kses_post($result->user_band_score_and_suggestion)); ?>');
    console.log("Raw JSON decodeUserEssayOverallAndSuggestion:", decodeUserEssayOverallAndSuggestion);

    let userEssayData = {};
    let userEssayOverallData = {};

    try {
        userEssayData = JSON.parse(decodeUserEssay); // Chuyển JSON thành object
        userEssayOverallData = JSON.parse(decodeUserEssayOverallAndSuggestion);
    } catch (error) {
        console.error("Lỗi parse JSON:", error);
    }

    // Lấy nội dung dựa vào key bắt đầu bằng '1' hoặc '2'
    let task1Essay = "";
    let task2Essay = "";

    let task1WordCount;
    let task2WordCount;
    let task1SentenceCount;
    let task2SentenceCount;
    let typeOfTask1 = "";
    let typeOfTask2 = "";
    let task1ParagraphCount;
    let task2ParagraphCount;


    let overallbandTask1 = "";
    let overallbandTask2 = "";
    let scoreTATask1;
    let scoreTATask2;
    let scoreCCTask1;
    let scoreCCTask2;
    let scoreGraTask1;
    let scoreGraTask2;
    let scoreLrTask1;
    let scoreLrTask2;

    let commentTATask1;
    let commentTATask2;
    let commentCCTask1;
    let commentCCTask2;
    let commentGraTask1;
    let commentGraTask2;
    let commentLrTask1;
    let commentLrTask2;

    let suggestImprovementTask1;
    let suggestImprovementTask2;

    
    



    for (let key in userEssayData) {
        if (key.startsWith("1")) {
            task1Essay = userEssayData[key];

            overallbandTask1 = userEssayOverallData[key].band.overallband;
            scoreTATask1 = userEssayOverallData[key].band.ta;
            scoreCCTask1 = userEssayOverallData[key].band.cc;
            scoreGraTask1 = userEssayOverallData[key].band.gra;
            scoreLrTask1 = userEssayOverallData[key].band.lr;

            task1WordCount = userEssayOverallData[key].overview_essay.word_count;
            typeOfTask1 = userEssayOverallData[key].overview_essay.essay_type;
            task1SentenceCount = userEssayOverallData[key].overview_essay.sentence_count;
            task1ParagraphCount = userEssayOverallData[key].overview_essay.paragraph_count;
            task1GrammarErrorCount = userEssayOverallData[key].overview_essay.total_errors_count;
            task1LinkingWordCount = userEssayOverallData[key].overview_essay.foundLinkingWords;


            commentTATask1 = userEssayOverallData[key].detail_recommendation.ta_tr;
            commentCCTask1 = userEssayOverallData[key].detail_recommendation.cc;
            commentGraTask1 = userEssayOverallData[key].detail_recommendation.gra;
            commentLrTask1 = userEssayOverallData[key].detail_recommendation.lr;

            suggestReplaceTask1 = userEssayOverallData[key].improvement_words;


            suggestImprovementTask1 = userEssayOverallData[key].suggestion.improvement_suggestions;

            
        } else if (key.startsWith("2")) {
            task2Essay = userEssayData[key];
            overallbandTask2 = userEssayOverallData[key].band.overallband;
            scoreTATask2 = userEssayOverallData[key].band.ta;
            scoreCCTask2 = userEssayOverallData[key].band.cc;
            scoreGraTask2 = userEssayOverallData[key].band.gra;
            scoreLrTask2 = userEssayOverallData[key].band.lr;

            task2WordCount = userEssayOverallData[key].overview_essay.word_count;
            typeOfTask2 = userEssayOverallData[key].overview_essay.essay_type;
            task2SentenceCount = userEssayOverallData[key].overview_essay.sentence_count;
            task2ParagraphCount = userEssayOverallData[key].overview_essay.paragraph_count;
            task2GrammarErrorCount = userEssayOverallData[key].overview_essay.total_errors_count;
            task2LinkingWordCount = userEssayOverallData[key].overview_essay.foundLinkingWords;


            commentTATask2 = userEssayOverallData[key].detail_recommendation.ta_tr;
            commentCCTask2 = userEssayOverallData[key].detail_recommendation.cc;
            commentGraTask2 = userEssayOverallData[key].detail_recommendation.gra;
            commentLrTask2 = userEssayOverallData[key].detail_recommendation.lr;


            suggestReplaceTask2 = userEssayOverallData[key].improvement_words;

            suggestImprovementTask2 = userEssayOverallData[key].suggestion.improvement_suggestions;

        }
    }

    function generateImprovementHTML(improvementData) {
    if (!Array.isArray(improvementData)) {
        console.error("improvementData is not an array:", improvementData);
        return "<p style='color: red;'>Không có dữ liệu cải thiện.</p>";
    }

    return improvementData.map((item, index) => `
        <div id="box-improve-${index}" style="border: 1px solid #ccc; border-radius: 8px; padding: 12px; margin-bottom: 10px; background-color: #f9f9f9; box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);">
            <p><strong>Original:</strong> ${item.original}</p>
            <p><strong>Suggestion:</strong> ${item.suggestion}</p>
            <p><strong>Reason:</strong> ${item.reason}</p>
        </div>
    `).join("");
}

// Kiểm tra dữ liệu có phải mảng không, nếu không thì lấy .improvement_words
if (suggestReplaceTask1 && !Array.isArray(suggestReplaceTask1)) {
    suggestReplaceTask1 = suggestReplaceTask1.improvement_words || [];
}
if (suggestReplaceTask2 && !Array.isArray(suggestReplaceTask2)) {
    suggestReplaceTask2 = suggestReplaceTask2.improvement_words || [];
}

// Gán HTML vào replaceRecommendBarTask1 & replaceRecommendBarTask2
replaceRecommendBarTask1 = generateImprovementHTML(suggestReplaceTask1);
replaceRecommendBarTask2 = generateImprovementHTML(suggestReplaceTask2);

function applyReplacements(essay, suggestions) {
    if (!suggestions || !Array.isArray(suggestions)) return essay; // Kiểm tra nếu suggestions không hợp lệ

    suggestions.forEach((item, index) => {
        let regex = new RegExp(item.original, "g");
        essay = essay.replace(regex, `
            <span class="need_replace" id="replace_text_${index}" style="text-decoration: line-through;">${item.original}</span>
            <span class="replaced_text" id="replace_${index}" style="color: green; font-weight: bold; margin-left: 5px;">${item.suggestion}</span>
        `);
    });

    return essay;
}

let EssayUpdatedTask1 = applyReplacements(task1Essay, suggestReplaceTask1 ?? []);
let EssayUpdatedTask2 = applyReplacements(task2Essay, suggestReplaceTask2 ?? []);

   
    // Debug kết quả
   /* console.log("Task 1 User Essay:", task1Essay);
    console.log("Task 2 User Essay:", task2Essay);

    console.log("Task 1 User Overall Band:", overallbandTask1);
    console.log("Task 2 User Overall Band:", overallbandTask2);*/


/*console.log("Datee semt ", dataTask1Essay); // To check the structure
console.log(dataTask1Essay.unchange); // Check if this is undefined
*/
let detailsCommentTask1 =``;
let detailsCommentTask2 =``;

detailsCommentTask1 = `
        <h3 style="color:red">Analysis your test</h3>
        <h4>Task Achievement (TA): ${scoreTATask1}</h4>
        <h5 style="font-weight:bold">Completeness</h5>
            - ${task_achievement1_1_comment}<br>
            - ${task_achievement1_2_comment}<br>
        <h5 style="font-weight:bold">Accuracy</h5>
            - ${task_achievement1_3_comment}<br>
        <h5 style="font-weight:bold">Feedback</h5>
            - ${commentTATask1}<br>

        
        <h4>Coherence and Cohesion (CC): ${scoreCCTask1}</h4>
        <h5 style="font-weight:bold">Logical Organization</h5>
            - ${coherenceandcohesion1_1_comment}<br>
        <h5 style="font-weight:bold">Paraphrasing</h5>
            - ${coherenceandcohesion1_2_comment}<br>
        <h5 style="font-weight:bold">Linking Words</h5>
            - ${coherenceandcohesion1_3_comment}<br>
        <h5 style="font-weight:bold">Feedback</h5>
            - ${commentCCTask1}<br>
    
        <h4>Lexical Resource (LR): ${scoreLrTask1}</h4>
        <h5 style="font-weight:bold">Vocabulary Range and Complexity</h5>
            - ${lexical_resource1_1_comment}<br>
            - ${lexical_resource1_2_comment}<br>
        <h5 style="font-weight:bold">Feedback</h5>
            - ${commentLrTask1}<br>
        
        <h4>Grammatical Range and Accuracy (GRA): ${scoreGraTask1}</h4>
        <h5 style="font-weight:bold">Sentence Structure</h5>
            - ${grammatical_range_and_accuracy1_1_comment}<br>
        <h5 style="font-weight:bold">Grammar</h5>
            - ${grammatical_range_and_accuracy1_2_comment}<br>
        <h5 style="font-weight:bold">Feedback</h5>
            - ${commentGraTask1}<br>

            
        <h4 style = "color:red">Suggest To Improve</h4>
            -  ${suggestImprovementTask1}

    `;



    detailsCommentTask2 = `
        <h3 style="color:red">Analysis your test</h3>
        <h4>Task Achievement (TA): ${scoreTATask2}</h4>
        <h5 style="font-weight:bold">Completeness</h5>
            - ${task_achievement2_2_comment}<br>
            - ${task_achievement2_2_comment}<br>
        <h5 style="font-weight:bold">Accuracy</h5>
            - ${task_achievement2_3_comment}<br>
        <h5 style="font-weight:bold">Feedback</h5>
            - ${commentTATask2}<br>

        
        <h4>Coherence and Cohesion (CC): ${scoreCCTask2}</h4>
        <h5 style="font-weight:bold">Logical Organization</h5>
            - ${coherenceandcohesion2_2_comment}<br>
        <h5 style="font-weight:bold">Paraphrasing</h5>
            - ${coherenceandcohesion2_2_comment}<br>
        <h5 style="font-weight:bold">Linking Words</h5>
            - ${coherenceandcohesion2_3_comment}<br>
        <h5 style="font-weight:bold">Feedback</h5>
            - ${commentCCTask2}<br>
    
        <h4>Lexical Resource (LR): ${scoreLrTask2}</h4>
        <h5 style="font-weight:bold">Vocabulary Range and Complexity</h5>
            - ${lexical_resource2_2_comment}<br>
            - ${lexical_resource2_2_comment}<br>
        <h5 style="font-weight:bold">Feedback</h5>
            - ${commentLrTask2}<br>
        
        <h4>Grammatical Range and Accuracy (GRA): ${scoreGraTask2}</h4>
        <h5 style="font-weight:bold">Sentence Structure</h5>
            - ${grammatical_range_and_accuracy2_2_comment}<br>
        <h5 style="font-weight:bold">Grammar</h5>
            - ${grammatical_range_and_accuracy2_2_comment}<br>
        <h5 style="font-weight:bold">Feedback</h5>
            - ${commentGraTask2}<br>

        <h4 style = "color:red">Suggest To Improve</h4>
            -  ${suggestImprovementTask2}
    `;


// Create a DOMParser to parse the decoded HTML string
const parser = new DOMParser();
const doc = parser.parseFromString(decodedTask1BreakdownForm, 'text/html');
const doc2 = parser.parseFromString(decodedTask2BreakdownForm, 'text/html');
const doc3 = parser.parseFromString(decodedTask1Summary, 'text/html');
const doc4 = parser.parseFromString(decodedTask2Summary, 'text/html');

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
const RelationPointEssayAndQuestionTask2Value = doc4.getElementById('relation_point_essay_and_question_task_2');

const WordCountTask1Final = WordCountTask1Value ? WordCountTask1Value.textContent : 'Not found';
const CurrentEssayTask1Final = CurrentEssayTask1Value ? CurrentEssayTask1Value.textContent : 'Not found';
const TypeOfEssayTask1Final = TypeOfEssayTask1Value ? TypeOfEssayTask1Value.textContent : 'Not found';
const ParagraphCountTask1Final = ParagraphCountTask1Value ? ParagraphCountTask1Value.textContent : 'Not found';
const NumberOfSpellingAndGrammarTask1Final = NumberOfSpellingAndGrammarTask1Value ? NumberOfSpellingAndGrammarTask1Value.textContent : 'Not found';
const TotalLinkingWordCountTask1Final = TotalLinkingWordCountTask1Value ? TotalLinkingWordCountTask1Value.textContent : 'Not found';
const RelationPointEssayAndQuestionTask1Final = RelationPointEssayAndQuestionTask1Value ? RelationPointEssayAndQuestionTask1Value.textContent : 'Not found';

const generalSidebarContentTask1 = `
Số từ: ${task1WordCount}<br>
Task: 1<br> 
Loại essay: ${typeOfTask1}<br>
Số câu: ${task1SentenceCount}<br>
Số đoạn: ${task1ParagraphCount}<br>
Số lượng sai ngữ pháp/ từ vựng: ${task1GrammarErrorCount}<br>
Số linking Words: ${task1LinkingWordCount}<br>
Độ mạch lạc: ${RelationPointEssayAndQuestionTask1Final}<br>

`;


const WordCountTask2Final = WordCountTask2Value ? WordCountTask2Value.textContent : 'Not found';
const CurrentEssayTask2Final = CurrentEssayTask2Value ? CurrentEssayTask2Value.textContent : 'Not found';
const TypeOfEssayTask2Final = TypeOfEssayTask2Value ? TypeOfEssayTask2Value.textContent : 'Not found';
const ParagraphCountTask2Final = ParagraphCountTask2Value ? ParagraphCountTask2Value.textContent : 'Not found';
const NumberOfSpellingAndGrammarTask2Final = NumberOfSpellingAndGrammarTask2Value ? NumberOfSpellingAndGrammarTask2Value.textContent : 'Not found';
const TotalLinkingWordCountTask2Final = TotalLinkingWordCountTask2Value ? TotalLinkingWordCountTask2Value.textContent : 'Not found';
const RelationPointEssayAndQuestionTask2Final = RelationPointEssayAndQuestionTask2Value ? RelationPointEssayAndQuestionTask2Value.textContent : 'Not found';


const generalSidebarContentTask2 = `
Số từ: ${task2WordCount}<br>
Task: 2<br> 
Loại essay: ${typeOfTask2}<br>
Số câu: ${task2SentenceCount}<br>
Số đoạn: ${task2ParagraphCount}<br>
Số lượng sai ngữ pháp/ từ vựng: ${task2GrammarErrorCount}<br>
Số linking Words: ${task2LinkingWordCount}<br>
Độ mạch lạc: ${RelationPointEssayAndQuestionTask2Final}<br>

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
    const task1Description = <?php echo json_encode($task1_data['question_content']); ?>;
    const task2Description = <?php echo json_encode($task2_data['question_content']); ?>;
    const IdQuestionTask1 = <?php echo json_encode($task1_data['id_test']); ?>;
    const IdQuestionTask2 = <?php echo json_encode($task2_data['id_test']); ?>;


    const bandScore = '<?php echo addslashes($result->band_score_expand); ?>';
    const bandScoreData = JSON.parse(bandScore);

    const bandScoreOverall = '<?php echo addslashes($result->band_score); ?>';

    let bandScoreExpandOverall = "";

    if (bandScoreData.task_1 && bandScoreData.task_2) {
        bandScoreExpandOverall = `Task 1: ${bandScoreData.task_1.overallband}; Task 2: ${bandScoreData.task_2.overallband}`;
    } else if (bandScoreData.task_1) {
        bandScoreExpandOverall = `Task 1: ${bandScoreData.task_1.overallband}`;
    } else if (bandScoreData.task_2) {
        bandScoreExpandOverall = `Task 2: ${bandScoreData.task_2.overallband}`;
    }


// Tính trung bình các tiêu chí
const avgTA = (scoreTATask1 + scoreTATask2) / 2;
const avgCC = (scoreCCTask1 + scoreCCTask2) / 2;
const avgGra = (scoreGraTask1 + scoreGraTask2) / 2;
const avgLr = (scoreLrTask1 + scoreLrTask2) / 2;

// Xác định tiêu chí tốt nhất và kém nhất
const criteria = {
    TA: avgTA,
    CC: avgCC,
    Gra: avgGra,
    Lr: avgLr
};
const bestCriteria = Object.keys(criteria).reduce((a, b) => criteria[a] > criteria[b] ? a : b);
const worstCriteria = Object.keys(criteria).reduce((a, b) => criteria[a] < criteria[b] ? a : b);

// Hiển thị kết luận
const conclusion = `
    <p><strong>Kết luận:</strong></p>
    <p>Bạn tốt nhất ở <strong>${bestCriteria}</strong> (${criteria[bestCriteria].toFixed(1)})</p>
    <p>Bạn kém nhất ở <strong>${worstCriteria}</strong> (${criteria[worstCriteria].toFixed(1)})</p>
`;

// Tạo nội dung overallUserTest
var overallUserTest = `
    <p><strong>Band score overall:</strong> ${bandScoreOverall}</p>
    <p><strong>Your overall Test:</strong></p>
    <div>
        <div>
            <h2>Band Scores</h2>
            <canvas id="bandScoreChart"></canvas>
        </div>

        <div>
            <h2>Task 1 Scores</h2>
            <canvas id="task1PieChart"></canvas>
        </div>

        <div>
            <h2>Task 2 Scores</h2>
            <canvas id="task2PieChart"></canvas>
        </div>

        <div>
            <h2>Average Scores by Criteria</h2>
            <canvas id="averageLineChart"></canvas>
        </div>

        <h3>Task 1</h3>
        <p><strong>Overall Band:</strong> ${overallbandTask1}</p>
        <p><strong>TA:</strong> ${scoreTATask1}</p>
        <p><strong>CC:</strong> ${scoreCCTask1}</p>
        <p><strong>Gra:</strong> ${scoreGraTask1}</p>
        <p><strong>Lr:</strong> ${scoreLrTask1}</p>
    </div>
    <div>
        <h3>Task 2</h3>
        <p><strong>Overall Band:</strong> ${overallbandTask2}</p>
        <p><strong>TA:</strong> ${scoreTATask2}</p>
        <p><strong>CC:</strong> ${scoreCCTask2}</p>
        <p><strong>Gra:</strong> ${scoreGraTask2}</p>
        <p><strong>Lr:</strong> ${scoreLrTask2}</p>
    </div>
    ${conclusion}
`;

// Hiển thị overallUserTest (ví dụ: gán vào một phần tử khác)

// Vẽ biểu đồ (sau khi overallUserTest được thêm vào DOM)
function drawCharts() {
    // Vẽ biểu đồ Band Scores (Bar Chart)
    const bandScoreCtx = document.getElementById('bandScoreChart').getContext('2d');
    new Chart(bandScoreCtx, {
        type: 'bar',
        data: {
            labels: ['Overall Band', 'Task 1', 'Task 2'],
            datasets: [{
                label: 'Band Scores',
                data: [bandScoreOverall, overallbandTask1, overallbandTask2],
                backgroundColor: ['#36a2eb', '#ff6384', '#4bc0c0'],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    max: 9.0
                }
            }
        }
    });

    // Vẽ biểu đồ Task 1 (Pie Chart)
    const task1PieCtx = document.getElementById('task1PieChart').getContext('2d');
    new Chart(task1PieCtx, {
        type: 'pie',
        data: {
            labels: ['TA', 'CC', 'Gra', 'Lr'],
            datasets: [{
                label: 'Task 1 Scores',
                data: [scoreTATask1, scoreCCTask1, scoreGraTask1, scoreLrTask1],
                backgroundColor: ['#ff6384', '#36a2eb', '#ffce56', '#4bc0c0'],
                borderWidth: 1
            }]
        }
    });

    // Vẽ biểu đồ Task 2 (Pie Chart)
    const task2PieCtx = document.getElementById('task2PieChart').getContext('2d');
    new Chart(task2PieCtx, {
        type: 'pie',
        data: {
            labels: ['TA', 'CC', 'Gra', 'Lr'],
            datasets: [{
                label: 'Task 2 Scores',
                data: [scoreTATask2, scoreCCTask2, scoreGraTask2, scoreLrTask2],
                backgroundColor: ['#ff6384', '#36a2eb', '#ffce56', '#4bc0c0'],
                borderWidth: 1
            }]
        }
    });

    // Vẽ biểu đồ trung bình (Line Chart)
    const averageLineCtx = document.getElementById('averageLineChart').getContext('2d');
    new Chart(averageLineCtx, {
        type: 'line',
        data: {
            labels: ['TA', 'CC', 'Gra', 'Lr'],
            datasets: [{
                label: 'Average Scores',
                data: [avgTA, avgCC, avgGra, avgLr],
                borderColor: '#36a2eb',
                fill: false
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    max: 9.0
                }
            }
        }
    });
}

// Gọi hàm vẽ biểu đồ sau khi overallUserTest được thêm vào DOM
// Ví dụ: nếu bạn gán overallUserTest vào một phần tử khác, hãy gọi drawCharts() sau đó
document.getElementById('someElement').innerHTML = overallUserTest;
 drawCharts();


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
    const task1UserEssay = task1Essay;
    const task2UserEssay = task2Essay;
    

    // Keep track of the currently active task
let currentTask = 'overall'; // Default task on page load


const tasks = {
    overall: {
        description: "",
        user_level:"",
        wordCount: "",
        description_level:"",
        EssayUpdated: "",
        generalSidebar: "Overall general content here.",
        //detailSidebar: "",
        band_score: bandScoreOverall,
        band_score_expand: bandScoreExpandOverall,
        youpass: "",
        original: "",
        sample: "",
        id_question: IdQuestionTask2,
        suggestions: "",
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
        band_score: overallbandTask1,
        task_achievement_score: scoreTATask1,
        coherence_and_cohesion_score: scoreCCTask1,
        lexical_resource_score: scoreLrTask1,
        grammatical_range_and_accuracy_score: scoreGraTask1,

        generalSidebar: generalSidebarContentTask1,
        detailSidebar: `${detailsCommentTask1}`,
        band_score_expand: '',
        EssayUpdated: EssayUpdatedTask1,
        user_level: userLevelTask1,
        description_level: DescriptionEssayTask1,
        youpass: "YouPass feedback for Task 1: Your analysis is clear and well-structured, but make sure to include more comparisons between countries.",
        original: task1UserEssay,
        sample: task1Sample,
        id_question: IdQuestionTask1,
        replaceRecommendBar: replaceRecommendBarTask1,
        suggestions: "Suggestions for Task 1: Include more statistical data to strengthen your arguments.",
        sidebar: [
            "Bình luận 1: Cần cải thiện phần mở đầu.",
            "Bình luận 2: Số liệu cần rõ ràng hơn.",
            "Bình luận 3: Nên thêm ví dụ cụ thể."
        ],
        imageLink: "<?php echo esc_url($task1_data['image_link']); ?>" // Add image link for task 1
    }, 
    
               
    task2: {
        description: task2Description,
        band_score: overallbandTask2,
        task_achievement_score: scoreTATask2,
        coherence_and_cohesion_score: scoreCCTask2,
        lexical_resource_score: scoreLrTask2,
        grammatical_range_and_accuracy_score: scoreGraTask2,
        replaceRecommendBar: replaceRecommendBarTask2,

        wordCount: WordCountTask2Final,
        generalSidebar: generalSidebarContentTask2,
        detailSidebar: `${detailsCommentTask2}`,
        user_level: userLevelTask2,
        description_level: DescriptionEssayTask2,
        band_score_expand: '',
        EssayUpdated: EssayUpdatedTask2,
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
    else if (type === 'suggestion') {
        sidebarContent = taskData.replaceRecommendBar || '';  // Default to details sidebar content for the task
        activeSidebar = 'suggestion-sidebar';  // Set active to details

    }

    document.getElementById('sidebarContent').innerHTML = sidebarContent;
    document.getElementById('general-sidebar').classList.remove('active');
    document.getElementById('details-sidebar').classList.remove('active');
    document.getElementById('suggestion-sidebar').classList.remove('active');

    // Add active class to the selected sidebar button
    document.getElementById(activeSidebar).classList.add('active');
}

// Add event listeners for switching between General and Details Sidebar
document.getElementById('general-sidebar').addEventListener('click', function() {
    setSidebarContent(currentTask, 'general');  // Show General Sidebar content for the current task
  
});
document.getElementById('suggestion-sidebar').addEventListener('click', function() {
    setSidebarContent(currentTask, 'suggestion');  // Show General Sidebar content for the current task
    

});

document.getElementById('details-sidebar').addEventListener('click', function() {
    setSidebarContent(currentTask, 'details');  // Show Details Sidebar content for the current task

    // Remove 'active' class from general button and add it to details button
    document.getElementById('general-sidebar').classList.remove('active');
    document.getElementById('suggestion-sidebar').classList.remove('active');

    this.classList.add('active');
});


// Function // Hàm setActiveTask
function setActiveTask(task) {

    
    // Update active button state
    var buttons = document.querySelectorAll('.button-10');
    buttons.forEach(function(button) {
        button.classList.remove('active');
    });
    document.querySelector(`.button-10[onclick="setActiveTask('${task}')"]`).classList.add('active');
    
    // Show or hide the right-column
    const rightColumn = document.querySelector('.right-column');
    const topNav = document.querySelector('.top-nav'); 
    if (task === "overall") {
        rightColumn.style.display = 'none';
        topNav.style.display = 'none';
    } else {
        rightColumn.style.display = 'block';
        topNav.style.display = 'block';

    }



    
    currentTask = task;  // Cập nhật task hiện tại
    console.log(currentTask);

    // Lấy phần tử someElement
    const someElement = document.getElementById('someElement');

    // Kiểm tra nếu task là 'overall'
    if (task === 'overall') {
        someElement.style.display = 'block'; // Hiển thị someElement
    } else {
        someElement.style.display = 'none'; // Ẩn someElement
    }

    // Cập nhật nội dung của task hiện tại
    const taskData = tasks[task];

    
    
    // Update content sections for the active task
    document.getElementById('taskDescription').innerText = taskData.description;
    document.getElementById('youpassContent').innerHTML = taskData.EssayUpdated;
    document.getElementById('originalContent').innerHTML = taskData.original;
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