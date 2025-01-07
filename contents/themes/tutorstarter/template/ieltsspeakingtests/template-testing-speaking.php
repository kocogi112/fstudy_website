<?php
/*
 * Template Name: Doing Template Speaking
 * Template Post Type: ieltsspeakingtests
 
 */

 get_header(); // Gọi phần đầu trang (header.php)

if (is_user_logged_in()) {
    $post_id = get_the_ID();
    $user_id = get_current_user_id();
    $additional_info = get_post_meta($post_id, '_ieltsspeakingtests_additional_info', true);

    /*if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['doing_text'])) {
        $textarea_content = sanitize_textarea_field($_POST['doing_text']);
        update_user_meta($user_id, "ieltsspeakingtests_{$post_id}_textarea", $textarea_content);

        wp_safe_redirect(get_permalink($post_id) . 'get-mark-speaking/');
        exit;
    }*/
$post_id = get_the_ID();
// Get the custom number field value
$custom_number = get_post_meta($post_id, '_ieltsspeakingtests_custom_number', true);

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

$id_test = $custom_number;

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

if (!$id_test) {
    $id_test = '00'; // Set id_test to "00" if invalid
}


 // Create result_id
 $result_id = $hour . $minute . $second . $id_test . $user_id . $random_number;

 echo "<script> 
        var resultId = '" . $result_id . "';
        console.log('Result ID: ' + resultId);
    </script>";



// Fetch the question_choose and time for the given id_test
$sql = "SELECT id_test, question_choose,testname, test_type, tag,book  FROM ielts_speaking_test_list WHERE id_test = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $custom_number); // 'i' is used for integer
$stmt->execute();
$result = $stmt->get_result();

$question_choose = [];

if ($row = $result->fetch_assoc()) {
    // Split the question_choose string into an array
    $question_choose = explode(',', $row['question_choose']);
    // Get the time directly from the query result
    $testname = $row['testname'];
    $test_type = $row['test_type'];
}

// Prepare an array to store the questions
$questions = [];
$topic = ''; // Variable to store the topic

// Fetch speaking_part 1 questions based on question_choose
if (!empty($question_choose)) {
    $placeholders = implode(',', array_fill(0, count($question_choose), '?'));
    $sql1 = "SELECT id_test, speaking_part, topic, stt, question_content, sample, important_add 
              FROM ielts_speaking_part_1_question WHERE id_test IN ($placeholders)";
    $stmt1 = $conn->prepare($sql1);
    $stmt1->bind_param(str_repeat("i", count($question_choose)), ...$question_choose); // Bind as integers
    $stmt1->execute();
    $result1 = $stmt1->get_result();

    while ($row = $result1->fetch_assoc()) {
        $questions[] = [
            "question" => $row['question_content'],
            "part" => $row['speaking_part'],
            "stt" => $row['stt'],
            "topic" => $row['topic'],
            "id" => $row['id_test'],
            "sample" => $row['sample']

        ];
    }

    // Fetch speaking_part 2 questions based on question_choose
    $sql2 = "SELECT id_test, speaking_part, topic, question_content, sample 
              FROM ielts_speaking_part_2_question WHERE id_test IN ($placeholders)";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param(str_repeat("i", count($question_choose)), ...$question_choose); // Bind as integers
    $stmt2->execute();
    $result2 = $stmt2->get_result();

    while ($row = $result2->fetch_assoc()) {
        $questions[] = [
            "question" => $row['question_content'],
            "part" => $row['speaking_part'],
            "id" => $row['id_test'],
            "stt" => 1,
            "topic" => $row['topic'],
            "sample" => $row['sample']
        ];
    }


    // Fetch speaking_part 2 questions based on question_choose
    $sql3 = "SELECT id_test, speaking_part, topic, stt, question_content, sample 
              FROM ielts_speaking_part_3_question WHERE id_test IN ($placeholders)";
    $stmt3 = $conn->prepare($sql3);
    $stmt3->bind_param(str_repeat("i", count($question_choose)), ...$question_choose); // Bind as integers
    $stmt3->execute();
    $result3 = $stmt3->get_result();

    while ($row = $result3->fetch_assoc()) {
        $questions[] = [
            "question" => $row['question_content'],
            "part" => $row['speaking_part'],
            "stt" => $row['stt'],
            "id" => $row['id_test'],
            "topic" => $row['topic'],
            "sample" => $row['sample']
        ];

        // Capture the topic (assuming the same topic for all rows)
        if (!$topic) {
            $topic = $row['topic'];
        }
    }
}



// Output the quizData as JavaScript
echo '<script type="text/javascript">
const quizData = {
    "title": "' . htmlspecialchars($testname) . '",
    "testtype": "' . htmlspecialchars($test_type) . '",
    "questions": ' . json_encode($questions) . '
};
console.log(quizData);
</script>';



// Close the database connection
$conn->close();

    ?>
    
              


<html lang="en" dir="ltr">

<head>
     
    <script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="https://code.responsivevoice.org/responsivevoice.js?key=qt48zGVy"></script>


    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <title>Speaking Ielts Simulation</title>
    <link rel="stylesheet" href="\wordpress\contents\themes\tutorstarter\ielts-speaking-toolkit\style\style.css">

    <style>
        .parent-container {
            display: flex; /* Use Flexbox */
            justify-content: center; /* Center horizontally */
            align-items: center; /* Center vertically */
        }
        .video-container-2 {
            border: 1px solid rgb(73, 61, 61);

            border-radius: 10%;
            width: 40%;   
            overflow: hidden; /* Ensure video does not overflow container */
        }
        .video-container-intro{
         
            overflow: hidden; /* Ensure video does not overflow container */
        }
        

        .video-container-2 video {

            width: 100%; /* Make the video responsive within its container */
            height: 100%;
            display: block;
        }

    </style>
</head>

<body>
    



    <div class="container1" id ="container1">
     <!-- Add "Get Started" button in the right column -->
        <div class ="getstartpage" id ="column0">
            <div class="column0" > 
                <div class="video-container-intro">
                    <video id="examinerVideo_homepage" class="video-background" autoplay playsinline style="pointer-events: none;">
                        <source src="\wordpress\contents\themes\tutorstarter\ielts-speaking-toolkit\examiner.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
                <p > <b>Chọn chế độ làm bài thi</b></p>
                <select id="exam-option">
                    <option value="practice">Chế độ luyện tập</option>
                    <option value="real-test">Chế độ phòng thi</option>                
                  </select>

                  <p><b>Chế độ phòng thi: </b><br> - Sau 3 giây sẽ tự động ghi âm từng câu hỏi<br> - Không cho phép trả lời lại</p><br>
                  <p><b>Chế độ luyện tập: </b><br> - Cho phép thí sinh suy nghĩ trước mỗi câu hỏi rồi ghi âm<br> - Cho phép thí sinh trả lời lại</p>
                  
                  
                
                <p > <b>Thiết lập giám khảo</b></p>
                <button id ="change_examiner"> Change examiner</button>
                <button id ="change_examiner_voice"> Change examiner's voice</button>
                <button id="change_examiner_speed">Change examiner's voice speed</button>
                <div id="data-save-full-speaking" ></div>


             </div>

            <div  class="column0" > 
                
                <div id ="intro_test">
                    <h3 id="title"></h3>
                    <div id="id_test"></div>
                    <div id="testtype"></div>




                     <h2  style="font: bold;" id ="title"></h2>
                     <h3>Hướng dẫn</h3>
                     <p>     Trước hết, bạn cần cho phép truy cập microphone xuyên suốt quá trình làm bài để không ảnh hưởng đến kết quả</p>
                     <p>     Sau đó, bên dưới có phần mic check, hãy kiểm tra mic của bạn bằng cách ấn RECORD rồi STOP để nghe đoạn ghi âm</p>
                     <p>     Tại mỗi câu hỏi, sau khi examiner đọc xong câu hỏi, hãy nhấn nút START RECORD để bắt đầu ghi âm. Nếu bạn trả lời xong, hãy ấn SUBMIT ANSWER để chuyển sang câu tiếp theo</p>
                     <p>     Bạn có thể yêu cầu examiner đọc lại câu hỏi/ nhắc lại câu hỏi bằng cách ấn nút COMMAND GUILDLINE bên dưới examiner </p>
                     
                     
                     <div id="list-question">
                        <h2  style="font: bold;">List question</h2>
                        <button id ="show-list-popup-btn">See question</button>
                    </div>
                    <div id= "show-list-popup" class="popup">
                        <div class="practice-pronunciation-content">
                    
                            <span class="close" onclick="closeListQuestion()">&times;</span>
                             <h2>Danh sách câu hỏi</h2>
                             <div id="question-list"></div>
                        </div>
                     </div>




                     <div id="mic-check" >

                         <h2 style="font: bold;"> Mic check</h2>
                         <p> Before starting exam, you can check your mic </p>
                         <audio id='audioPlayer'></audio>
                         <button class ="recorder">Record</button>
                         <button class="stopRecorder" >Stop</button>
                         
                         

                     </div>


                     <button id="getStartedButton" onclick="initializeTest()">Get Started</button>
                </div>
             </div>


        </div>


    <div id ="virtual-room">
            <div class="column1" id="column1">
                <h2 id ="speaking-part"></h2>

            <div class="parent-container">
                <div class="video-container-2">
                    <video id="examinerVideo" class="video-background" autoplay playsinline style="pointer-events: none;">
                        <source src="\wordpress\contents\themes\tutorstarter\ielts-speaking-toolkit\examiner.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            </div>

            


             <!--  <button id ="hide_show_question" onclick="toggle_question()">Show/Hide question</button>-->
                <p id="question" class="question"></p>

                <div class="button-container">

                    <button class="main-button" id="startButton" onclick="startRecording()">Start Record</button>
                    <button class="main-button" id="stopButton" disabled onclick="stopRecording()">Submit Answer</button>
                    <button class="main-button" id="reAnswerButton"  onclick="reAnswerQuestion()">Reanswer question</button>
                    
                </div>
                <div class="timer-display-id">
                    <p id="timer">00:00:00 </p>
                </div>
                <br>
                <textarea id="answerTextarea"  class="answerTextarea" ></textarea>
               
                

                <div id ="check-answer"></div>
                <div id ="set-time-to-record" style="display: none;">Recording ... You should say now !</div>


                <div id ="confidence-level"></div>



                <button id="submitButton" onclick="endTest()" style="display: none;">Submit</button>




                
                
            </div>
    </div>


    
    </div>
    <div id="practice-pronunciation-popup" class="popup">
        <div class="practice-pronunciation-content">
    
            <span class="close" onclick="closePracticePronunciation()">&times;</span>
            <h2>Các từ/ cụm từ bạn đọc chưa chính xác</h2>
            <div id="pronunciation-list"></div>
        </div>
    </div>

    
    <div id ="result-full-page">
        <div class="tab">
            <button class="tablinks" onclick="changeTabResult(event, 'result-page-tabs-1')">Overall</button>
            <button class="tablinks" onclick="changeTabResult(event, 'result-page-tabs-2')">Your test</button>
            <button class="tablinks" onclick="changeTabResult(event, 'result-page-tabs-3')">Sample</button>
            <button class="tablinks" onclick="changeTabResult(event, 'result-page-tabs-4')">Practice</button>


        </div>

        <div id ="result-page-tabs-1" class="tabcontent">
            <h3>Result </h3>
            <div style="display: none;" id="date" style="visibility:hidden;"></div>

            <div id ="final-result"></div>
            <div id ="breakdown"></div>
            <div id ="band-decriptor"></div>
            <h3>General Comment</h3>
            <div id ="general-comment"></div>

        </div>
        <div id ="result-page-tabs-2" class="tabcontent">
            <button id ="btn-show-practice-pronunciation" style="display: none;">Practice to improve your pronunciation</button>
            <div id ="result-tab-your-answer">
                <div id="recordingsList"class="column0"></div>
                <div id="resultColumn"class="column0"></div>
            </div>
        </div>
        <div id ="result-page-tabs-3"class="tabcontent">
            <h3>Sample Answer</h3>
            <div id = "sample-tab-container"></div>

        </div>
        <div id ="result-page-tabs-4"class="tabcontent">
                <h3>This is tab 4 result</h3>
                <div id="userResult"></div>  
                <div id="userBandDetail"></div>            
                <div id="userAnswerAndComment"></div>   
                
                    <!-- giấu form send kết quả bài thi -->


                    
                
                <span id="message"></span>
                <form id="saveUserResultTest" >
                        <div class="card">
                            <div class="card-header">Form lưu kết quả</div>
                            <div class="card-body" >

                    <div class = "form-group" >
                        <textarea   type="text" id="resulttest" name="resulttest" placeholder="Kết quả"  class="form-control form_data" ></textarea>
                        <span id="result_error" class="text-danger" ></span>

                    </div>


                    <div class = "form-group">
                        <textarea type="text" id="dateform" name="dateform" placeholder="Ngày"  class="form-control form_data" ></textarea>
                        <span id="date_error" class="text-danger" ></span>
                    </div>

                    

                    <div class = "form-group" >
                        <textarea type="text" id="idtest" name="idtest" placeholder="Id test"  class="form-control form_data"></textarea>
                        <span id="idtest_error" class="text-danger" ></span>
                    </div>

                 

                    <div class = "form-group"   >
                        <textarea type="text"  id="testname" name="testname" placeholder="Test Name"  class="form-control form_data"></textarea>
                        <span id="testname_error" class="text-danger"></span>
                    </div>


                    <div class = "form-group"   >
                        <textarea type="text"  id="band_detail" name="band_detail" placeholder="Band Detail"  class="form-control form_data" ></textarea>
                        <span id="correctanswer_error" class="text-danger"></span>  
                    </div>
                    
                    <div class = "form-group"   >
                        <textarea type="text"  id="test_type" name="test_type" placeholder="Test Type"  class="form-control form_data" ></textarea>
                        <span id="correctanswer_error" class="text-danger"></span>  
                    </div>

                    <div class = "form-group"   >
                        <textarea type="text"  id="user_answer_and_comment" name="user_answer_and_comment" placeholder="Data Save Speaking"  class="form-control form_data"></textarea>
                        <span id="useranswer_error" class="text-danger"></span>
                    </div>
                     <div class = "form-group"   >
                        <textarea type="text"  id="testsavenumber" name="testsavenumber" placeholder="testsavenumber"  class="form-control form_data"></textarea>
                        <span id="testsavenumber_error" class="text-danger"></span>
                    </div>
                




                    </div>

                <div class="card-footer">
                    <!--  <button type="button" name="submit" id="submit" class="btn btn-primary" onclick="save_data(); return false;">Save</button>-->
                                    <td><input type="submit" id="submit" name="submit"/></td> 

                    </div>
                        
                </div>
                <div id="result_msg" ></div>
                </form>
                <!-- kết thúc send form -->	
         

        </div>
        
    </div>
    
    <!---
    <div id = "ads-container">
        <p style="font-weight: bold;">This is place for banner ( advertisement)____</p>
         <p style="color: red;font-weight: bold;font-style: italic;">____DEV TAG: POWERED BY NGUYEN MINH LONG</p>
    </div>-->

    <script>
        
// save date (ngày làm bài cho user)
const currentDate = new Date();

// Get day, month, and year
const day = currentDate.getDate();
const month = currentDate.getMonth() + 1; // Adding 1 because getMonth() returns zero-based month index
const year = currentDate.getFullYear();

// Display the date
const dateElement = document.getElementById('date');

dateElement.innerHTML = `${year}-${month}-${day}`;


    let os = null; 
    
    document.addEventListener('DOMContentLoaded', function () {
        getOS();
        checkLocationAndIpAddress();   
            
    // Get the video element
    const videoElement = document.getElementById('examinerVideo');

    // Get the button to change the examiner video
    const changeExaminerButton = document.getElementById('change_examiner');

    // Array of video options
    const videoOptions = [
        { name: 'Examiner 1', src: '\wordpress\contents\themes\tutorstarter\ielts-speaking-toolkit\examiner.mp4' },
        { name: 'Examiner 2', src: '\wordpress\contents\themes\tutorstarter\ielts-speaking-toolkit\examiner1.mp4' },
        { name: 'Examiner 3', src: '\wordpress\contents\themes\tutorstarter\ielts-speaking-toolkit\examiner2.mp4' }
    ];


    // Create the dropdown menu
    const dropdownMenu = document.createElement('select');
    dropdownMenu.setAttribute('id', 'examinerOptions');

    // Populate the dropdown menu with options
    videoOptions.forEach(option => {
        const optionElement = document.createElement('option');
        optionElement.textContent = option.name;
        optionElement.setAttribute('value', option.src);
        dropdownMenu.appendChild(optionElement);
    });

    // Add event listener to the button
    changeExaminerButton.addEventListener('click', function () {
        // Replace the button with the dropdown menu
        changeExaminerButton.parentNode.replaceChild(dropdownMenu, changeExaminerButton);
    });

    // Add event listener to the dropdown menu
    dropdownMenu.addEventListener('change', function () {
        // Change the source of the video based on the selected option
        videoElement.src = this.value;
    });

            // Speed selection feature

     const changeSpeedButton = document.getElementById('change_examiner_speed');
            const speedOptions = [1, 1.5, 2, 3, 5];

            // Create the speed dropdown menu
            const speedDropdownMenu = document.createElement('select');
            speedDropdownMenu.setAttribute('id', 'speedOptions');

            // Populate the speed dropdown menu with options
            speedOptions.forEach(speed => {
                const speedOptionElement = document.createElement('option');
                speedOptionElement.textContent = speed;
                speedOptionElement.setAttribute('value', speed);
                speedDropdownMenu.appendChild(speedOptionElement);
            });

            // Add event listener to the speed button
            changeSpeedButton.addEventListener('click', function () {
                // Replace the button with the speed dropdown menu
                changeSpeedButton.parentNode.replaceChild(speedDropdownMenu, changeSpeedButton);
            });


});
    function initializeTest(){
        let timerInterval;
            Swal.fire({
            title: "Đang thiết lập phòng speaking cho bạn",
            html: "Vui lòng đợi trong giây lát.",
            timer: 1000,
            allowOutsideClick: false,

            timerProgressBar: true,
            didOpen: () => {
                Swal.showLoading();
                const timer = Swal.getPopup().querySelector("b");
                timerInterval = setInterval(() => {
                
                }, 100);
            },
            willClose: () => {
                clearInterval(timerInterval);
            }
            }).then((result) => {
            /* Read more about handling dismissals below */
            showContent();
            });
    }
    function changeTabResult(evt, tabResultName) {
                var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(tabResultName).style.display = "block";
        evt.currentTarget.className += " active";
        }

        // Automatically click the first tab when the page loads
        document.addEventListener('DOMContentLoaded', function() {
        document.querySelector('.tablinks').click();
    });
        

    function endTest(){
        let timerInterval;
            Swal.fire({
            title: "Đang nộp bài cho thí sinh",
            html: "Chúc mừng bạn đã hoàn thành bài thi. Vui lòng đợi trong giây lát.",
            timer: 1000,
            allowOutsideClick: false,

            timerProgressBar: true,
            didOpen: () => {
                Swal.showLoading();
                const timer = Swal.getPopup().querySelector("b");
                timerInterval = setInterval(() => {
                
                }, 100);
            },
            willClose: () => {
                clearInterval(timerInterval);
            }
            }).then((result) => {
            /* Read more about handling dismissals below */
            submitAnswers();
            });
    }

    
        function showContent() {
        
            ChooseExamOption();
            //mediaRecorderCheck.stop();
           // document.getElementById("ads-container").style.display = "none";
            document.getElementById('column1').style.display = 'block';
            document.getElementById ('column0').style.display = 'none';
            loadQuestion();

        }



        function ChooseExamOption(){
           
            var x = document.getElementById("exam-option").value;
            if(x == "real-test"){
                document.getElementById("startButton").style.display="none";
                document.getElementById("reAnswerButton").style.display ="none";
                
            setInterval(function(){
                
                document.getElementById("stopButton").disabled=false;
                startRecording1();
                
                startRecording();
                console.log("You can start right now !!!");
                document.getElementById("set-time-to-record").style.display="block";
                document.getElementById('submitButton').disabled = false;
                
            },7000);

            }
        }



        function toggle_question(){

            var x = document.getElementById("question");
            if (x.style.display === "none") {
                x.style.display = "block";
            } else {
                x.style.display = "none";
            }

        }


// popup open list question
document.getElementById("show-list-popup-btn").addEventListener('click', openListQuestion);
        
        // Close the draft popup when the close button is clicked
        function closeListQuestion() {
            document.getElementById('show-list-popup').style.display = 'none';
        }

        function openListQuestion() {
            document.getElementById('show-list-popup').style.display = 'block';
          
        }


let pre_id_test_ = `<?php echo esc_html($custom_number);?>`;
        console.log(`${pre_id_test_}`)
     
 



// function save data qua ajax


jQuery('#saveUserResultTest').submit(function(event) {
event.preventDefault(); // Prevent the default form submission

 var link = "<?php echo admin_url('admin-ajax.php'); ?>";

 var form = jQuery('#saveUserResultTest').serialize();
 var formData = new FormData();
 formData.append('action', 'save_user_result_ielts_speaking');
 formData.append('save_user_result_ielts_speaking', form);

 jQuery.ajax({
     url: link,
     data: formData,
     processData: false,
     contentType: false,
     type: 'post',
     success: function(result) {
         jQuery('#submit').attr('disabled', false);
         if (result.success == true) {
             jQuery('#saveUserResultTest')[0].reset();
         }
         jQuery('#result_msg').html('<span class="' + result.success + '">' + result.data + '</span>');
     }
 });
});

         //end

document.addEventListener("DOMContentLoaded", function () {
    document.addEventListener("submitForm", function () {
        setTimeout(function () {
            let form = document.getElementById("saveUserResultTest");
            form.submit(); // This should work now that there's no conflict
        }, 2000); 
    });
});


async function uploadRecording(blob) {
    const formData = new FormData();
    formData.append('file', blob, 'recording.mp3');
    formData.append('upload_preset', 'ccgbws2m');  // Replace with your preset name

    const response = await fetch('https://api.cloudinary.com/v1_1/dloq2wl7k/upload', {  // Replace with your cloud name
        method: 'POST',
        body: formData,
    });

    const result = await response.json();

    // Check if the upload was successful
    if (result.secure_url) {
        return result.secure_url; // Return the URL of the uploaded audio file
    } else {
        console.error('Upload failed:', result);
        return null;
    }
}


        let part1Count = 0;
        let part2Count = 0;
        let part3Count = 0;
        let unknownPartCount = 0;

        // Iterate through each question and count based on the 'part' property
        quizData.questions.forEach((question) => {
        switch (question.part) {
            case 1:
            part1Count++;
            break;
            case 2:
            part2Count++;
            break;
            case 3:
            part3Count++;
            break;
            default:
            unknownPartCount++;
            break;
        }
        });
        let number_of_question = 0;
        for (let i = 1; i <= quizData.questions.length; i++){
            number_of_question ++;
        }



        document.getElementById("title").innerHTML = quizData.title;
        document.getElementById("testtype").innerHTML = quizData.testtype;
        let recognition;
        let textarea = document.getElementById('answerTextarea');
        let questionElement = document.getElementById('question');
        let currentQuestionIndex = 0;
        let answers = {};
        let counters = {};
        let mediaRecorder;
        let recordedChunks = [];
        let recordingsList = [];
        let numRecordings = 0;

        

        let audioChunks = [];
        let recordingInterval;

       
        let pronunciation_words_list = {};

        let isAnswerSubmitted = false;

document.addEventListener('DOMContentLoaded', function () {
    const videoElement = document.getElementById('examinerVideo');

    navigator.mediaDevices.getUserMedia({ audio: true })
        .then(stream => {
            mediaRecorder = new MediaRecorder(stream);

            mediaRecorder.ondataavailable = event => {
                recordedChunks.push(event.data);
            };
            let answer = textarea.value.trim();

            answers['answer' + (currentQuestionIndex + 1)] = answer;
            //counters['counter' + (currentQuestionIndex + 1)] = counter;
            if (answer == "can you repeat question"){
             
             speakText(questionElement.textContent);
             //console.log("Answer " + (currentQuestionIndex + 1) + ": " + answer);

         }  
         else{
                
                if (mediaRecorder.state === 'recording') {
                mediaRecorder.stop(); // Ensure the recorder is stopped
            }
       
            clearInterval(interval);

            mediaRecorder.onstop = () => {
                if (!isAnswerSubmitted) {
                    const blob = new Blob(recordedChunks, { type: 'audio/mp3' });
                    recordedChunks = [];
                    recordingsList.push(blob);

                    uploadRecording(blob).then(link => {
                        answers['link_audio' + (currentQuestionIndex)] = link;
                        console.log(`Uploaded audio for Question ${currentQuestionIndex}: ${link}`);
                    });

                    // Collect and save the user's answer
                    let answer = textarea.value.trim();
                    answers['answer' + (currentQuestionIndex + 1)] = answer;
                    console.log("Question " + (currentQuestionIndex + 1) + ": " + questionElement.textContent);
                    console.log("Answer " + (currentQuestionIndex + 1) + ": " + answer);

                    counter = 0; // Reset counter
                    ret.innerHTML = convertSec(counter); // Update display
                    currentQuestionIndex++; // Move to next question or end the quiz
                   
               
                    if (currentQuestionIndex < quizData.questions.length) {
                        loadQuestion();
                    } else {
                        document.getElementById('startButton').style.display = 'none';
                        document.getElementById('stopButton').style.display = 'none';
                        document.getElementById('submitButton').style.display = 'block';
                        endTest();
                        showRecordings();
                    }
                }
            };
        }

            document.getElementById('startButton').addEventListener('click', () => {
                isAnswerSubmitted = true; // Reset for next question

                startRecording1();

                document.getElementById('startButton').disabled = true;
                document.getElementById('stopButton').disabled = false;
            });

            document.getElementById('stopButton').addEventListener('click', () => {
                stopRecording();
                isAnswerSubmitted = false; // Reset for next question

                document.getElementById('startButton').disabled = false;
                document.getElementById('stopButton').disabled = true;
            });

            document.getElementById('submitButton').addEventListener('click', () => {
                showRecordings();
            });
        })
        .catch(console.error);
});

var questionList = document.getElementById("question-list");
        questionList.innerHTML = ""; // Clear any existing content

        for (let i = 0; i < quizData.questions.length; i++) {
            questionList.innerHTML += `Question ${i + 1}: ${quizData.questions[i].question}<br>`;
        }




function startRecording1() {
    recordedChunks = [];
    if (mediaRecorder.state !== 'recording') {
        mediaRecorder.start();
    }
    document.getElementById('submitButton').disabled = true;
}




        let wordsSpelling = {};
        function startRecording() {
            
            //startBtn.disabled = true;
            interval = setInterval(function() {
                    ret.innerHTML = convertSec(counter++); // Timer starts counting here...
                }, 1000);

                //resultColumn.innerHTML = ''; // Clear previous content
            const resultContainer = document.getElementById('confidence-level');


            recognition = new webkitSpeechRecognition();
            recognition.continuous = true;
            recognition.interimResults = true;
            recognition.lang = 'en-US';
            var finalTranscript = '';
            recognition.onresult = function (event) {
                let interimTranscript = '';
                
                var interTranscript  = '';

                for (let i = event.resultIndex; i < event.results.length; ++i) {
                    let transcript = event.results[i][0].transcript;

                    
                    if(event.results[i].isFinal){
                  finalTranscript += transcript;
                 


                }else{
                  interTranscript += transcript;
                }
                textarea.value = finalTranscript + interTranscript +' ';
                const transcripta = event.results[i][0].transcript;
                const confidence = event.results[i][0].confidence;
                let confidencelevel = (confidence * 100).toFixed(2);
                resultContainer.innerHTML = `
                    <p>Recognizing: <strong>${transcripta}</strong></p>
                    <!--<p>Confidence: <strong>${confidencelevel}%</strong></p>-->
                `;

                if (confidencelevel < 90 && confidencelevel > 50) { 
                
                    wordsSpelling += transcript;
                    
                    console.log(`Confidential error`, wordsSpelling);


                }   


                }
            };
            recognition.start();
            isAnswerSubmitted = true;
        }


        let reanswers = {};

        function reAnswerQuestion()
        {
           

            let answer = textarea.value.trim();

            //answers['answer' + (currentQuestionIndex + 1)] = answer;
            textarea.value = '';
            document.getElementById('startButton').disabled = false;

            console.log("Reanswer for question " + (currentQuestionIndex + 1) + ": " + questionElement.textContent);
            console.log("Old Answer " + (currentQuestionIndex + 1) + ": " + answer);
            
            // Save the reanswer message for the current question
            reanswers['reanswer' + (currentQuestionIndex + 1)] = answer;

        }

const ret = document.getElementById("timer");
const startBtn = document.querySelector("#start-timer");
document.getElementById("id_test").innerHTML = pre_id_test_;


let counter = 0;
let interval;


function convertSec(cnt) {
    let sec = cnt % 60;
    let min = Math.floor(cnt / 60);
    if (sec < 10) {
        if (min < 10) {
            return "0" + min + ":0" + sec;
        } else {
            return min + ":0" + sec;
        }
    } else if ((min < 10) && (sec >= 10)) {
        return "0" + min + ":" + sec;
    } else {
        return min + ":" + sec;
    }
}




function stopRecording() {
    let questionText = questionElement.textContent;

    let wordCount = questionText.trim().split(/\s+/).length;

    if(isAnswerSubmitted != true){
            Swal.fire({
                title: "Missing Answer",
                text: `Bạn hãy trả lời câu hỏi này trước bằng cách nhấn "Start Record" để ghi âm câu trả lời rồi nhấn "Submit Answer" để chuyển sang câu hỏi tiếp theo nhé !`,
                icon: "question"
            });
    }
    
    else{
        if (recognition) {
            recognition.stop();
            counters['counter' + (currentQuestionIndex + 1)] = counter;

            clearInterval(interval);
        
        }

        if (mediaRecorder.state === 'recording') {
            mediaRecorder.stop(); // Ensure the recorder is stopped
            
        }

        document.getElementById('stopButton').disabled = false;
        isAnswerSubmitted = true; 
    }
}



        function speakText(text) {
           if(os == 'Windows'){

            
            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = 'en-US';

            /* utterance.voice = speechSynthesis.getVoices().filter(function(voice) {
            return voice.name == "Google UK English Female"
        })[0]; */


            // Get the selected speed value
            const speedDropdown = document.getElementById('speedOptions');


            if (speedDropdown) {
                const selectedSpeed = speedDropdown.value;
                utterance.rate = selectedSpeed;
            }

            speechSynthesis.speak(utterance);
        }
        else if(os == 'iOS'){
            responsiveVoice.speak(text);
        }
        else{
            responsiveVoice.speak(text);
        }



        }



        let examiner_test = document.getElementById("examinerVideo");


        function loadQuestion() {
            isAnswerSubmitted = false;
            if (currentQuestionIndex >= 0) {
                    let questionText = questionElement.textContent;
                    let wordCount = questionText.trim().split(/\s+/).length;

                    // Play corresponding audio file
                    

                    // Play examiner.mp4 in left corner

                    var times = parseInt(wordCount/15)
                     
  
                    examiner_test.addEventListener('ended', function() {
                        if (times >= 1) {
                          times--;
                          examiner_test.play();
                        }
                      });
                      
                      examiner_test.play();
                }



            const currentQuestion = quizData.questions[currentQuestionIndex];
            const currentQuestionPart = quizData.questions.part; 

            questionElement.textContent = `Question ${currentQuestion.stt}: ${currentQuestion.question}`;
            document.getElementById("speaking-part").innerHTML = `Speaking part ${currentQuestion.part} - Topic: ${currentQuestion.topic}`;
            
            
            // Stop any ongoing speech before speaking the new question
            speechSynthesis.cancel(); // This line stops the previous speech
            speakText(currentQuestion.question);
            textarea.value = '';

            document.getElementById('stopButton').disabled = false; // Ensure the stop button is enabled for each new question
            /*if (mediaRecorder.state !== 'recording') {
                mediaRecorder.start(); // Start the recorder for the new question
            }*/
        }

        function showRecordings() {
            const recordingsListDiv = document.getElementById('recordingsList');
            recordingsListDiv.innerHTML = ''; // Clear previous recordings

            recordingsList.forEach((blob, index) => {
                const audioElement = document.createElement('audio');
                audioElement.src = URL.createObjectURL(blob);
                audioElement.controls = true;
                audioElement.preload = 'metadata';

                const recordingLabel = document.createElement('p');
                recordingLabel.textContent = `Question ${index + 1}:`;

                recordingsListDiv.appendChild(recordingLabel);
                recordingsListDiv.appendChild(audioElement);

                // Retrieve and display the saved link from answers
                const link = answers['link_audio' + (index + 1)];
                if (link) {
                    const linkElement = document.createElement('a');
                    linkElement.href = link;
                    linkElement.target = '_blank';
                    linkElement.textContent = 'Download link';
                    recordingsListDiv.appendChild(linkElement);
                }

                recordingsListDiv.appendChild(document.createElement('br'));
                recordingsListDiv.appendChild(document.createElement('br'));
            });

            document.getElementById('submitButton').style.display = 'none';
        }



// mic-check
function playAudio(audioChunksCheck) {
    const blob = new Blob(audioChunksCheck, { type: 'audio/x-mpeg-3' });
    const audioPlayer = document.getElementById('audioPlayer');
    audioPlayer.src = URL.createObjectURL(blob);
    audioPlayer.controls = true;
    audioPlayer.autoplay = true;
}

var mediaRecorderCheck;
var audioChunksCheck = [];

const getmiceacesses = function () {
    audioChunksCheck = []; // Reset the audio chunks each time we start recording
    navigator.mediaDevices.getUserMedia({ audio: true })
        .then(function (stream) {
            mediaRecorderCheck = new MediaRecorder(stream);

            mediaRecorderCheck.start();
            console.log('Recording started');

            setTimeout(stopRecorder, 10000); // Automatically stop the recorder after 10 seconds

            mediaRecorderCheck.addEventListener("dataavailable", (event) => {
                audioChunksCheck.push(event.data);
                console.log('Data available event fired');
            });

            mediaRecorderCheck.addEventListener("stop", () => {
                playAudio(audioChunksCheck);
                console.log('Recording stopped and audio is playing');
            });
        })
        .catch(function (err) {
            console.error('Error accessing the microphone: ', err);
        });
};

const stopRecorder = function () {
    if (mediaRecorderCheck && mediaRecorderCheck.state === 'recording') {
        mediaRecorderCheck.stop();
        console.log('Recording stopped');
    } else {
        console.error('MediaRecorder is not initialized or not recording.');
    }
};

document.addEventListener('DOMContentLoaded', (event) => {
    const recordingcheck = document.querySelector('.recorder');
    const stopRecordingCheck = document.querySelector('.stopRecorder');

    if (recordingcheck && stopRecordingCheck) {
        recordingcheck.addEventListener('click', getmiceacesses);
        stopRecordingCheck.addEventListener('click', stopRecorder);
    } else {
        console.error('Elements with classes "recorder" and "stopRecorder" not found in the DOM.');
    }
});
// end mic check


let fluency_and_coherence_all_point_part1 = 0;
let lexical_resource_all_point_part1 = 0;
let fluency_and_coherence_all_point_part2 = 0;
let lexical_resource_all_point_part2 = 0;
let fluency_and_coherence_all_point_part3 = 0;
let lexical_resource_all_point_part3 = 0;


let grammatical_range_and_accuracy_all_point_part1 = 0;
let grammatical_range_and_accuracy_all_point_part2 = 0;
let grammatical_range_and_accuracy_all_point_part3 = 0;

let pronunciation_all_point_part1 = 0;
let pronunciation_all_point_part2 = 0;
let pronunciation_all_point_part3 = 0;


async function submitAnswers() {
    //console.log(wordsSpelling);
    for (let element of document.getElementsByClassName("video-container-2")) {
        element.style.display = "none";
    }
    document.getElementById("confidence-level").style.display="none";
    document.getElementById("timer").style.display="none";

    document.getElementById("btn-show-practice-pronunciation").style.display="block";

    document.getElementById("reAnswerButton").style.display="none";

    document.getElementById("check-answer").style.display = "none";
    document.getElementById('answerTextarea').style.display = 'none';
    document.getElementById("result-full-page").style.display="block";
    document.getElementById("container1").style.display = "none"
    textarea.value = '';

    // mới, cách sửa: resultColumn vào hết submitAnswers => không lưu local storage, dễ sử dụng hơn
    questionElement.textContent = '';
    
    let resultColumn = document.getElementById('resultColumn');
    resultColumn.innerHTML = ''; // Clear previous content


    let res = 0;

    // Tính Điểm/ Nhận xét cho Part 1



for (let i = 0; i < quizData.questions.length; i++){
    part = quizData.questions[i].part;
    await GetSummaryPart1(i);
    
}
    quizData.questions.forEach((question, i) => {
        
        let answer = answers['answer' + (i + 1)] || "";
        const link = answers['link_audio' + (i + 1)];

        let counter = counters['counter' + (i + 1)] || "";
        let reanswer = reanswers['reanswer' + (i + 1)] || "";
        let result = '';
        let keywordResult = checkForGreatKeyword(answer);
        let lengthResult = checkAnswerLength(answer);

        // Determine the final result and update res
        if (keywordResult.result === "Good (+1 point)") {
            res += 10;
            result = keywordResult.result;
        } 
        else if (lengthResult.result === "Good length") {
            res += 5;
            result = lengthResult.result;
        } 
        else {
            res += 1;
            result = lengthResult.result;
        }





        let highlightedAnswer = answer;
        let Timeused = counter;
        let wordCount = answer.split(/\s+/).length;

        //console.log(wordsSpellingCheck);


        // Only highlight if there is a reanswer
        if (reanswer) {
            let answerWords = answer.split(/\s+/);
            let reanswerWords = reanswer.split(/\s+/);

            let pronunciation_words_list = [];

            highlightedAnswer = answerWords.map(word => {
                if (reanswerWords.includes(word)) {
                    return word;
                } else {
                    if (!pronunciation_words_list.includes(word)) {
                        pronunciation_words_list.push(word);
                    }
                    return `<span style="color: red;">${word}</span>`;
                }
            }).join(' ');

            // Append reanswer, question, answer, and result to the result column
            resultColumn.innerHTML += `<p><strong>Reanswer for Question ${i + 1}:</strong> ${reanswer}</p>`;

            // Create the table inside the pronunciation-list div if it doesn't exist
            if (!document.querySelector("#pronunciation-list table")) {
                let tableHTML = `
                    <table border="1">
                        <thead>
                            <tr>
                                <th>Words List</th>
                                <th>Pronunciation</th>
                                <th>Listen</th>
                                <th>Record Word</th>
                                <th>Accuaracy</th>
                                <th>Qualified ?</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>`;
                document.getElementById("pronunciation-list").innerHTML += tableHTML;
            }

            // Add each word to a new row in the first column
            pronunciation_words_list.forEach(word => {
                let rowHTML = `
                    <tr>
                        <td>${word}</td>
                        <td>${word}</td>
                        <td><button onclick="speakWord('${word}')">Listen</button></td>
                        <td><button onclick="recordWord('${word}', this)">Record</button></td>
                        <td class="confidence-level"></td>
                        <td class="qualification"></td>
                    </tr>`;
                document.querySelector("#pronunciation-list tbody").innerHTML += rowHTML;
            });
        }
        });

    

        ResultInput();
    }



function ResultInput() {
        // Copy the content to the form fields

    var contentToCopy1 = document.getElementById("data-save-full-speaking").textContent;
    var contentToCopy2 = document.getElementById("date").textContent;
    var contentToCopy4 = document.getElementById("title").textContent;
    var contentToCopy6 = document.getElementById("id_test").textContent;
    var contentToCopy7 = document.getElementById("userResult").textContent;
    var contentToCopy8 = document.getElementById("userBandDetail").innerHTML;
    var contentToCopy9 = document.getElementById("testtype").textContent;


    document.getElementById("user_answer_and_comment").value = contentToCopy1;
    document.getElementById("dateform").value = contentToCopy2;
    document.getElementById("testname").value = contentToCopy4;
    document.getElementById("idtest").value = contentToCopy6;
    document.getElementById("resulttest").value = contentToCopy7;
    document.getElementById("band_detail").value = contentToCopy8;
    document.getElementById("test_type").value = contentToCopy9;

    document.getElementById("testsavenumber").value = resultId;

   /* // Add a delay before submitting the form
setTimeout(function() {
// Automatically submit the form
jQuery('#saveUserResultTest').submit();
}, 5000); // 5000 milliseconds = 5 seconds */

}
          


function checkForGreatKeyword(answer) {
    if (answer.toLowerCase().includes('great')) {
        return { result: "Good (+1 point)", points: 10 };
    } else {
        return { result: "OK", points: 1 };
    }
}


function checkAnswerLength(answer) {
    let wordCount = answer.split(/\s+/).length;
    if (wordCount > 5) {
        return { result: "Good length", points: 5 };
    } else {
        return { result: "Not enough length", points: 1 };
    }
}


function speakWord(word) {
    let utterance = new SpeechSynthesisUtterance(word);
    speechSynthesis.speak(utterance);
}


function recordWord(expectedWord, buttonElement) {
    if (!('webkitSpeechRecognition' in window)) {
        alert('Web Speech API is not supported by this browser.');
        return;
    }

    let recognition = new webkitSpeechRecognition();
    recognition.continuous = false;
    recognition.interimResults = false;
    recognition.lang = 'en-US';

    recognition.onstart = function() {
        console.log('Voice recognition started.');
    };

    recognition.onerror = function(event) {
        console.error('Voice recognition error', event);
    };

    recognition.onend = function() {
        console.log('Voice recognition ended.');
    };

    recognition.onresult = function(event) {
        let transcript = event.results[0][0].transcript.trim().toLowerCase();
        let confidence = event.results[0][0].confidence * 100;
        let confidenceCell = buttonElement.parentElement.nextElementSibling;
        let qualificationCell = confidenceCell.nextElementSibling;

        confidenceCell.textContent = confidence.toFixed(2) + '%';

        if (transcript === expectedWord.toLowerCase()) {
            if (confidence > 80) {
                qualificationCell.textContent = "YES";
            } else {
                qualificationCell.textContent = "Try again";
            }
        } else {
            qualificationCell.textContent = "Misunderstand";
        }
    };

    recognition.start();
}

     /*  <!-- // Load the first question but don't display it until "Get Started" is clicked
        document.addEventListener('DOMContentLoaded', (event) => {
            loadQuestion();
        });  */

        document.getElementById("btn-show-practice-pronunciation").addEventListener('click', openPracticePronunciation);
        
            // Close the draft popup when the close button is clicked
            function closePracticePronunciation() {
                document.getElementById('practice-pronunciation-popup').style.display = 'none';
            }

            function openPracticePronunciation() {
                document.getElementById('practice-pronunciation-popup').style.display = 'block';
              
            }



            
</script>

<script src = "\wordpress\contents\themes\tutorstarter\ielts-speaking-toolkit\function\analysis\speaking-part-1\summarynew.js"></script>
<script src = "\wordpress\contents\themes\tutorstarter\ielts-speaking-toolkit\function\analysis\speaking-part-1\overalltab.js"></script>
<script src = "\wordpress\contents\themes\tutorstarter\ielts-speaking-toolkit\function\analysis\speaking-part-1\sample-tab.js"></script>
<script src = "\wordpress\contents\themes\tutorstarter\scan-device\location_ip.js"></script>
<script src = "\wordpress\contents\themes\tutorstarter\scan-device\system_check.js"></script>


<!--
<script src = "function\analysis\speaking-part-1\criteria\fluency_and_coherence.js"></script>
<script src = "function\analysis\speaking-part-1\criteria\lexical_resource.js"></script>
<script src = "function\analysis\speaking-part-1\criteria\pronunciation.js"></script>
<script src = "function\analysis\speaking-part-1\criteria\grammatical_range_and_accuracy.js"></script>
-->
</body>

</html>
<?php
} else {
    get_header();
    echo '<p>Please log in start speaking test.</p>';
    get_footer();
}