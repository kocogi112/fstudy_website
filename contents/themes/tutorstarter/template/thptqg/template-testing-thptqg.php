<?php
/*
 * Template Name: Doing Template Speaking
 * Template Post Type: thptqg
 
 */


if (is_user_logged_in()) {
    $post_id = get_the_ID();
    $user_id = get_current_user_id();

    $custom_number =intval(get_query_var('id_test'));


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

$sql = "SELECT id_test, subject, year, testname, link_file, time, number_question, testcode FROM thptqg_question WHERE id_test = ?";

// Use prepared statements to execute the query
$stmt = $conn->prepare($sql);
$id_test = $custom_number;
$stmt->bind_param("i", $id_test);
$stmt->execute();

// Get the result
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $testname = $row['testname']; // Fetch the testname field
    $testcode = $row['testcode']; // Fetch the testname field
    $link_file = $row['link_file']; // Fetch the testname field
    $subject = $row['subject']; // Fetch the testname field
    $year = $row['year']; // Fetch the testname field
    $time = $row['time']; // Fetch the testname field
    add_filter('document_title_parts', function ($title) use ($testname) {
      $title['title'] = $testname; // Use the $testname variable from the outer scope
      return $title;
  });
  

get_header(); // Gọi phần đầu trang (header.php)



}







// Output the quizData as JavaScript
echo '<script type="text/javascript">
const questions = [
    ' . $testcode . '
];


console.log(questions);
</script>';



// Đóng kết nối
$conn->close();

?>
<!-- Bản quyền thuộc về HỆ THỐNG GIÁO DỤC Onluyen247-->





<!-- Lưu ý: ĐỌC KĨ TRƯỚC KHI SETUP

    Đây là trang đề thi (Để Làm bài thi)

    Thay lần lượt theo từ khóa " Sửa "

    Tổng thể trang này PHẢI  SỬA 6 LẦN

-->


<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="/wordpress/contents/themes/tutorstarter/dethithptqg_toolkit/style/style2.css">
</head>
<script src ="/wordpress/contents/themes/tutorstarter/dethithptqg_toolkit/js/toogleCheckbox2.js"></script>
<script src ="/wordpress/contents/themes/tutorstarter/dethithptqg_toolkit/js/open_fullscreen1.js"></script>
<script src ="/wordpress/contents/themes/tutorstarter/dethithptqg_toolkit/js/trackingmode5.js"></script> 
<style>

.container1{
  height: 100%;
  width: 99%;
  margin-bottom:20px;
}

.intro-test{
  height: 5%;
  font-size: 15px;
  text-align:center;
}
.container-test {
  display: flex;
  height: 650px;
  background-color: #ffffff;
}

.content1 {
  box-shadow: 5px 5px 5px 5px #888888;
  height: 100%; /* Replace with the desired height for the content */
  background-color: #ffffff;
  border-radius: 2%;
}
.content2{
  box-shadow: 5px 5px 5px 5px #888888;
  height: 100%; /* Replace with the desired height for the content */
  background-color: #ffffff;
  border-radius: 2%;
  overflow-y: auto;
}
.content3{
  box-shadow: 5px 5px 5px 5px #888888;
  height: 100%; /* Replace with the desired height for the content */
  background-color: #ffffff;
  border-radius: 2%;
  overflow-y: auto;
}
.part1 {
  flex: 0 0 60%;
  height: 100%;
  margin-right:5px
}

.part1-clone {
  display: none;
  flex: 0 0 0%;
  height: 100%;
  margin-right: 5px;
  background-color: #f0f0f0;
}

.part2 {
  flex: 0 0 20%;
  height: 100%;
  background-color:#ffffff;
  /*overflow-y: auto;*/
}


.part3 {
  margin-left: 5px;
   flex: 0 0 20%;
  height: 100%;
  background-color: #ffffff;
  /*overflow-y: auto;*/
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
  align-items: center;
}
.input-answer {
  margin: 5px;
  width: 80%;
  height: 20px;
}
.true_false{
  display: flex;                /* Sử dụng Flexbox để đặt các phần tử trên cùng một dòng */
  align-items: center;          /* Canh giữa các phần tử theo chiều dọc */
  margin-bottom: 10px;  
}
.true_false  label {
  margin-right: 10px;           /* Thêm khoảng cách giữa label và select */
  font-weight: bold;             /* Làm đậm chữ label */
}
.true_false select {
  padding: 5px;                 /* Thêm padding cho select để nó trông đẹp hơn */
  width: 100px;                 /* Điều chỉnh chiều rộng của select nếu cần */
}

.input-container {
  display: flex;
  align-items: center;
}
.unit-label {
  margin-left: 5px;
  font-size: 14px;
  color: #666;
}

.toggle-active .part1 {
  flex: 0 0 40%;
}

.toggle-active .part1-clone {
  display: block;
  flex: 0 0 40%;
}

.toggle-active .part2 {
  flex: 0 0 20%;
}

.toggle-active .part3 {
 display: none;
}
</style>
<body>
<div class = "container1">
  <div id ="intro-test" class ="intro-test">
    <b><?php echo htmlspecialchars($testname, ENT_QUOTES, 'UTF-8'); ?></b>
    <button id ="addScreen">Add Screen</button>
  </div>
  <div class="container-test">




    <div class="part1">
       

      <div class="content1">
        <!-- <div id="bottomleft"><p id="time">00:00:00</p></div>  -->
        <div  style="width: 100%; height: 100%; position: relative;">


<!-- src=" <?php echo htmlspecialchars($link_file, ENT_QUOTES, 'UTF-8'); ?> "  -->
          <iframe src=" <?php echo htmlspecialchars($link_file, ENT_QUOTES, 'UTF-8'); ?> "
          id="documentFrame" 
          width="100%" 
          height="100%" 
          allow="fullscreen" 
          frameborder="0" 
          scrolling="no" 
          seamless=""
          sandbox="allow-same-origin allow-scripts allow-popups">
        </iframe>
        
        <!-- Sửa 4: Link Đề thi -->
  </iframe>
  <div style="width: 48px; height: 48px; position: absolute; right: 6px; top: 6px;">
    <!--<img src="https://i.ibb.co/bJNBHXp/guitar-1.png"> -->
  </div>
  </div>



        <!-- Your content for Part 1 goes here -->
    <!--    <iframe src="https://drive.google.com/file/d/1jHgOkoz_6jMzhscvakN-7o3hpOM-TWWK/preview" width="100%" height="100%" allow="autoplay"></iframe> -->


      </div>
    </div>

    <div class="part1-clone">
        <div class="content1">
        <div  style="width: 100%; height: 100%; position: relative;">


          <iframe src=" <?php echo htmlspecialchars($link_file, ENT_QUOTES, 'UTF-8'); ?> "
          id="documentFrame" 
          width="100%" 
          height="100%" 
          allow="fullscreen" 
          frameborder="0" 
          scrolling="no" 
          seamless=""
          sandbox="allow-same-origin allow-scripts allow-popups">
        </iframe>
        
        <!-- Sửa 4: Link Đề thi -->
  </iframe>
  <div style="width: 48px; height: 48px; position: absolute; right: 6px; top: 6px;">
    <!--<img src="https://i.ibb.co/bJNBHXp/guitar-1.png"> -->
  </div>
  </div>



        <!-- Your content for Part 1 goes here -->
    <!--    <iframe src="https://drive.google.com/file/d/1jHgOkoz_6jMzhscvakN-7o3hpOM-TWWK/preview" width="100%" height="100%" allow="autoplay"></iframe> -->


      </div>
      </div>



    <div id="timer"></div>
        <div id="resizableButton"></div>

    <div class="part2">
      <div class="content2">
        
        <!-- Your content for Part 2 goes here -->
    <form id="quiz-form" onsubmit="submitAnswers(event)">
    <div id="questions"></div>
    <div id="loading">
         <div class="spinner"></div>
    <p>Vui lòng đợi trong giây lát....</p>
       
    </div>
    <div id="message-document">
      <p>Bạn cần nộp bài để xem kết quả chi tiết</p>
    </div>
  <br>
  </form>

  
  <div id="result"></div>
</div>
</div>

<div class ="part3"> 
  <div class ="content3"> 
    


<div id ="save-resultform">
    <div id="date" ></div>
    <div id="save-result"></div>
    <div id="timedotest"></div>
    <div id = "name_test"></div>
    <div id = "subject_test"></div>
    <div id = "exam_uuid"></div>

    <div id = "id_test"></div> 
    <div id="finalresult"></div>
    <div id="number_correct_ans"></div>

    <div id="finalresultinput"></div>

</div>
   

    <!-- giấu form send kết quả bài thi -->


                    
                
    <span id="message"></span>
             
<form id="saveUserTHPTQGTest" >
                        <div class="card">
                            <div class="card-header">Form lưu kết quả 21/11</div>
                            <div class="card-body" >

                    


                    <div class = "form-group">
                        <input type="text" id="dateform" name="dateform" placeholder="Ngày"  class="form-control form_data"  />
                        <span id="date_error" class="text-danger" ></span>
                    </div>

                    <div class = "form-group" >
                        <input type="text" id="idtest" name="idtest" placeholder="Id test"  class="form-control form_data" />
                        <span id="idtest_error" class="text-danger" ></span>
                    </div>
                    <div class = "form-group"   >
                        <input type="text"  id="testname" name="testname" placeholder="Test Name"  class="form-control form_data" />
                        <span id="testname_error" class="text-danger"></span>
                    </div>
                    <div class = "form-group"   >
                        <input type="text"  id="test_type" name="test_type" placeholder="Subject"  class="form-control form_data" />
                        <span id="testname_error" class="text-danger"></span>
                    </div>
                    <div class = "form-group"  >
                        <input type="text" id="timedotestform" name="timedotestform" placeholder="Thời gian làm bài"  class="form-control form_data" />
                        <span id="time_error" class="text-danger"></span>
                    </div>
                    
                    <div class = "form-group"   >
                        <input type="text"  id="band_score_form" name="band_score_form" placeholder="Final Result"  class="form-control form_data" />
                        <span id="correctanswer_error" class="text-danger"></span>  
                    </div>
                   
                    <div class = "form-group"   >
                        <textarea type="text"  id="userAnswerSave" name="userAnswerSave" placeholder="User Answer"  class="form-control form_data" ></textarea>
                        <span id="correctanswer_error" class="text-danger"></span>  
                    </div>

                    <div class = "form-group"   >
                        <input type="text"  id="number_correct" name="number_correct" placeholder="number correct answer"  class="form-control form_data" />
                        <span id="correctanswer_error" class="text-danger"></span>  
                    </div>
                    <div class = "form-group"   >
                        <input type="text"  id="testsavenumber" name="testsavenumber" placeholder="testsavenumber"  class="form-control form_data" />
                        <span id="correctanswer_error" class="text-danger"></span>  
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








    <div style="display: flex; align-items: center;">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clock-history" viewBox="0 0 16 16"><path d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022zm2.004.45a7 7 0 0 0-.985-.299l.219-.976q.576.129 1.126.342zm1.37.71a7 7 0 0 0-.439-.27l.493-.87a8 8 0 0 1 .979.654l-.615.789a7 7 0 0 0-.418-.302zm1.834 1.79a7 7 0 0 0-.653-.796l.724-.69q.406.429.747.91zm.744 1.352a7 7 0 0 0-.214-.468l.893-.45a8 8 0 0 1 .45 1.088l-.95.313a7 7 0 0 0-.179-.483m.53 2.507a7 7 0 0 0-.1-1.025l.985-.17q.1.58.116 1.17zm-.131 1.538q.05-.254.081-.51l.993.123a8 8 0 0 1-.23 1.155l-.964-.267q.069-.247.12-.501m-.952 2.379q.276-.436.486-.908l.914.405q-.24.54-.555 1.038zm-.964 1.205q.183-.183.35-.378l.758.653a8 8 0 0 1-.401.432z"/><path d="M8 1a7 7 0 1 0 4.95 11.95l.707.707A8.001 8.001 0 1 1 8 0z"/><path d="M7.5 3a.5.5 0 0 1 .5.5v5.21l3.248 1.856a.5.5 0 0 1-.496.868l-3.5-2A.5.5 0 0 1 7 9V3.5a.5.5 0 0 1 .5-.5"/></svg> 
        <div id="display-time-option" style="margin-left: 8px;"></div>
    </div>
      <p id="time">00:00:00</p>

    <form id="quiz-form" onsubmit="submitAnswers(event)">
      <input id ="myButton" onclick="abort = true" class="submit-button-exam"  type="submit" value="Nộp bài">
    </form>



      
      <button onclick="openFullscreen();">Mở toàn màn hình</button><br>
      <button onclick="closeFullscreen();">Thoát toàn màn hình</button>
        <br>




  <!-- Khung save data -->

      





<!--Hết khung Save Data -->
      
    

      <p style="font-size: 20px;">Danh sách câu hỏi </p>
      <p style ="font-size:14px; color:red; display: none"><i>Ghi chú: Bạn có thể highlight lại câu chưa làm và phân vân bằng cách ấn vào hộp câu hỏi !</i></p>
      <div id="checkboxes"></div>
      




  </div>

</div>
                  

</div>
</div>
  
<script>
  document.getElementById("addScreen").addEventListener("click", function () {
      const containerTest = document.querySelector(".container-test");
      containerTest.classList.toggle("toggle-active");
    });


 // function save data qua ajax
 jQuery('#saveUserTHPTQGTest').submit(function(event) {
    event.preventDefault(); // Prevent the default form submission
    
     var link = "<?php echo admin_url('admin-ajax.php'); ?>";
    
     var form = jQuery('#saveUserTHPTQGTest').serialize();
     var formData = new FormData();
     formData.append('action', 'save_user_result_thptqg');
     formData.append('save_user_result_thptqg', form);
    
     jQuery.ajax({
         url: link,
         data: formData,
         processData: false,
         contentType: false,
         type: 'post',
         success: function(result) {
             jQuery('#submit').attr('disabled', false);
             if (result.success == true) {
                 jQuery('#saveUserTHPTQGTest')[0].reset();
             }
             jQuery('#result_msg').html('<span class="' + result.success + '">' + result.data + '</span>');
         }
     });
    });
    
             //end


// Thông báo xoay màn hình nếu để màn hình điện thoại dọc
  if(window.innerHeight > window.innerWidth){
    alert("Vui lòng xoay ngang màn hình để bài thi hiển thị tốt nhất !");
}




  const optionTimeSet = urlParams.get('option');
    const optionTrackSystem = urlParams.get('optiontrack');

    if (optionTimeSet) {
        option = optionTimeSet; // Override the option if provided in URL
        var timeleft = optionTimeSet / 60 + " phút";
        console.log(`Time left: ${timeleft}`);
    }

else{
    option = <?php echo htmlspecialchars($time, ENT_QUOTES, 'UTF-8'); ?> * 60;
    var timeleft = option/60 + " phút";
}


  // Display the selected option in the div
document.getElementById('display-time-option').innerText = timeleft ; 







const currentDate = new Date();

            // Get day, month, and year
const day = currentDate.getDate();
const month = currentDate.getMonth() + 1; // Adding 1 because getMonth() returns zero-based month index
const year = currentDate.getFullYear();

            // Display the date
const dateElement = document.getElementById('date');
dateElement.innerHTML = `${year}-${month}-${day}`;
var nametest = document.getElementById('name_test');
var subjectTest = document.getElementById('subject_test');
var examuuid =  document.getElementById('exam_uuid');
var idtest = document.getElementById('id_test');
const timestamp = new Date().toISOString().replace(/[-:.TZ]/g, ''); // Generate a compact timestamp

let testuid = `${timestamp}<?php echo htmlspecialchars($user_id, ENT_QUOTES, 'UTF-8'); ?>`

idtest.innerHTML = `<?php echo htmlspecialchars($id_test, ENT_QUOTES, 'UTF-8'); ?>`;        
nametest.innerHTML = `<?php echo htmlspecialchars($testname, ENT_QUOTES, 'UTF-8'); ?>`;        
subjectTest.innerHTML = `<?php echo htmlspecialchars($subject, ENT_QUOTES, 'UTF-8'); ?>`;        
examuuid.innerHTML =  `${testuid}`; 



function createQuestionElement(question, isFirstOfPart) {
    const questionElement = document.createElement("div");
    let specificQuestionID = `part_${question.part}_question_${question.question}`;
    questionElement.id = `question-container-number-${specificQuestionID}`;
    questionElement.className = "question-containers";

    // Nếu là câu đầu tiên của part, hiển thị tiêu đề part
    if (isFirstOfPart) {
    const partHeader = document.createElement("div");
    partHeader.className = "part-header";
    partHeader.textContent = `Phần ${question.part}`;

    // Thêm kiểu trực tiếp
    partHeader.style.fontWeight = "bold";
    partHeader.style.fontSize = "20px";
    partHeader.style.textDecoration  = "underline";

    partHeader.style.marginBottom = "10px";
    partHeader.style.color = "#333";

    questionElement.appendChild(partHeader);
}


    // Gắn nội dung câu hỏi
    questionElement.innerHTML += `
        <div id="question-label-container-${specificQuestionID}" class="question-label-container">
            
            <b><span>Câu</span></b>
            <p id="question-${specificQuestionID}" style="margin: 0;"><b>${question.question}</b></p>
            <img id="bookmark-question-${specificQuestionID}" 
                 src="/wordpress/contents/themes/tutorstarter/dethithptqg_toolkit/assets/bookmark_empty.png" 
                 class="bookmark-btn" 
                 style="cursor: pointer;" 
                 onclick="rememberQuestion('${specificQuestionID}')">
        </div>`;

    // Xử lý câu hỏi dạng "multiple-choice"
    if (question.type === 'multiple-choice') {
        const choices = ['A', 'B', 'C', 'D'];
        for (let i = 0; i < choices.length; i++) {
            const circle = document.createElement("div");
            circle.classList.add("circle");

            const input = document.createElement("input");
            input.type = "radio";
            input.name = `question-${specificQuestionID}`;
            input.value = choices[i];
            input.id = `${specificQuestionID}_${choices[i]}`;

            const label = document.createElement("label");
            label.setAttribute("for", input.id);
            label.textContent = choices[i];

            circle.appendChild(input);
            circle.appendChild(label);
            questionElement.appendChild(circle);
        }
    }

    // Xử lý câu hỏi dạng "completion"
else if (question.type === 'completion') {
    const inputContainer = document.createElement("div");
    inputContainer.className = "input-container";

    const input = document.createElement("input");
    input.type = "text";
    input.className = "input-answer";

    input.name = `question-${specificQuestionID}`;
    input.id = `${specificQuestionID}_completion`;
    inputContainer.appendChild(input);

    // Kiểm tra và thêm đơn vị nếu có
    if (question.don_vi) {
        const unitSpan = document.createElement("span");
        unitSpan.className = "unit-label";
        unitSpan.textContent = ` ${question.don_vi}`;
        inputContainer.appendChild(unitSpan);
    }

    questionElement.appendChild(inputContainer);
}

    else if (question.type === 'true-false') {
    const choices = ['A', 'B', 'C', 'D'];  // Tạo lựa chọn A, B, C, D (có thể thay đổi tùy theo number_choice)
    const numberChoice = parseInt(question.number_choice);  // Số lượng lựa chọn (3, 4, ...)

    for (let i = 0; i < numberChoice; i++) {
        const choiceLetter = choices[i];  // Lấy chữ cái cho lựa chọn (A, B, C, D)

        const true_false = document.createElement("div");
        true_false.classList.add("true_false");

        // Tạo select dropdown cho từng lựa chọn
        const select = document.createElement("select");
        select.id = `${specificQuestionID}_${choiceLetter}`;  // ID cho từng select
        select.name = `question-${specificQuestionID}_${choiceLetter}`;

        // Tạo các option cho select (Đúng/Sai/Chưa chọn)
        const options = ['...', 'Đ', 'S'];  // Đúng, Sai, Chưa chọn
        options.forEach(option => {
            const optionElement = document.createElement("option");
            optionElement.value = option;  // Đặt giá trị cho option (_ cho chưa chọn, Đ cho đúng, S cho sai)
            optionElement.textContent = option === '...' ? ' ' : (option === 'Đ' ? 'Đúng' : 'Sai'); // Hiển thị label cho option
            select.appendChild(optionElement);  // Thêm option vào select
        });

        // Tạo label cho mỗi lựa chọn
        const label = document.createElement("label");
        label.setAttribute("for", select.id);
        label.textContent = choiceLetter;  // Hiển thị chữ cái A, B, C, D

        // Thêm label và select vào div chứa
        true_false.appendChild(label);
        true_false.appendChild(select);
        questionElement.appendChild(true_false);  // Thêm vào phần tử chứa câu hỏi
    }
}


    // Thêm câu hỏi vào container chính
    document.getElementById("questions").appendChild(questionElement);
}



let answerSubmitted = false; // Variable to track if answer has been submitted

function toggleDocument(type) {
  const iframe = document.getElementById('documentFrame');
  const message = document.getElementById('message-document');
  
  if (type === 'default') {
    iframe.src = "https://drive.google.com/file/d/1trIxAoWN_Q_ICi9mlS0ym2CCoBBTO-oM/preview"; //Chú thích: Lại đề thi, bên trên
// Sửa 5: Đề thi (Link giống sửa 4)

  } else if (type === 'new') {
    if (!answerSubmitted) {
      message.style.display = 'block'; // Show the message
      setTimeout(function() {
        message.style.display = 'none'; // Hide the message after a delay
      }, 4000); // Adjust the delay time as needed
      return;
    }
    iframe.src = "https://drive.google.com/file/d/1N9j7VKSZW9q9hsrzdbk1VZaepxEMDRid/preview"; //Sửa 6: Link đáp án chi tiết
  }
}




var elem = document.documentElement;




checktrackingmode();



  // tính thời gian làm bài  
      var hours = 0;
      var minutes = 0;
      var seconds = 0;
      var intervalId;

        function countUp() {
            seconds++;
            if (seconds >= 60) {
                seconds = 0;
                minutes++;
                if (minutes >= 60) {
                    minutes = 0;
                    hours++;
                }
            }
            // add a leading zero if the value is less than 10
            var formattedTime = (hours < 10 ? "0" : "") + hours + ":" +
                                (minutes < 10 ? "0" : "") + minutes + ":" +
                                (seconds < 10 ? "0" : "") + seconds;

            // display the time in the browser
            document.getElementById("time").innerHTML = formattedTime;
        }

        window.onload = function() {
            countUp();
            setInterval(countUp, 1000);
        };

        function saveTime() {
            var elapsedTime = document.getElementById("time").innerHTML;
            //alert("Time used: " + elapsedTime);
        }

   
   function submitAnswers(event) {
    event.preventDefault();
    //confirm('Bạn chắc chắn nộp bài chưa ?');
    var elapsedTime = document.getElementById("time").innerHTML;
    var button = document.getElementById("myButton");
    var loading = document.getElementById("loading");
    var quizContainer = document.getElementById("quiz-form");

 

    button.style.display = "none";
    loading.style.display = "block";

    answerSubmitted = true; // Set answerSubmitted to true when answer is submitted
    setTimeout(function() {
        loading.style.display = "block";
        var quizContainer = document.getElementById("quiz-form");
        var resultElement = document.getElementById("result");
        var resultElementFinal = document.getElementById("finalresult");
        var timedotestFinal = document.getElementById("timedotest");
        let finalRes = document.getElementById("finalresultinput");
        let numberCorrect =  document.getElementById('number_correct_ans'); 




      //document.getElementById('data-form').style.display = 'block';

      quizContainer.style.display = "none";

      var score = 0;

      for (let i = 0; i < questions.length; i++) {
    const question = questions[i];
    let userAnswer = null;
    let enteredAnswer = ""; // Initialize as an empty string

    if (question.type === "multiple-choice") {
        // Fetch the selected radio button
        const specificQuestionID = `part_${question.part}_question_${question.question}`;
        userAnswer = document.querySelector(`input[name='question-${specificQuestionID}']:checked`);
        enteredAnswer = userAnswer ? userAnswer.value.toUpperCase() : ""; // Get the value if selected
    } else if (question.type === "completion") {
    // Fetch the input field value
    const specificQuestionID = `part_${question.part}_question_${question.question}_completion`;
    const inputElement = document.getElementById(specificQuestionID);
    enteredAnswer = inputElement && inputElement.value ? inputElement.value.toUpperCase() : ""; // Get the value if not empty

    // Ensure question.answer is treated as an array
    const validAnswers = Array.isArray(question.answer) 
        ? question.answer.map(ans => ans.toUpperCase()) // If it's an array, map through it
        : [question.answer.toUpperCase()]; // If it's not an array, convert it to an array with the uppercased answer

    // Check if the entered answer matches any valid answer
    if (validAnswers.includes(enteredAnswer)) {
        question.alert = "Correct answer!";
    } else {
        question.alert = `Incorrect! Correct answers are: ${validAnswers.join(", ")}`;
    }
}
else if (question.type === "true-false") {
    const selectedAnswers = [];  // Mảng lưu đáp án đã chọn của người dùng

    // Lấy tất cả các lựa chọn từ A, B, C, D
    const choices = ['A', 'B', 'C', 'D'];
    const specificQuestionID = `part_${question.part}_question_${question.question}`;

    for (let i = 0; i < question.number_choice; i++) {
        const selectElement = document.getElementById(`${specificQuestionID}_${choices[i]}`);
        if (selectElement) {
            selectedAnswers.push(selectElement.value);  // Lưu đáp án Đ/S của người dùng
        }
    }

    // Ghép các đáp án vào chuỗi enteredAnswer (Đ/S/... cho từng lựa chọn)
    enteredAnswer = selectedAnswers.join("/");

    // Kiểm tra đáp án người dùng với đáp án đúng
    const correctAnswer = question.answer;  // Câu trả lời đúng: "Đ/S/Đ/Đ"

    // Kiểm tra xem đáp án người dùng có đúng không
    if (enteredAnswer === correctAnswer) {
        question.alert = "Correct answer!";
    } else {
        question.alert = `Incorrect! Correct answers are: ${correctAnswer}`;
    }
}


    // Save and display the result
    let SaveResult = document.getElementById("save-result");
    const specificQuestionID = `part_${question.part}_question_${question.question}`;

    SaveResult.innerText += `${specificQuestionID}: ${enteredAnswer}`;
    SaveResult.innerHTML += `<br>`;

    // Create a textarea for displaying the question and user answer
    const textarea = document.createElement("textarea");
    textarea.setAttribute("readonly", "true");
    textarea.classList.add("answer-textarea");
    textarea.value = `${question.question}\nCâu trả lời: ${enteredAnswer}`;

    // Add green border for correct answers, red border for incorrect answers
    if (question.type === "completion") {
    // Kiểm tra xem question.answer có phải là mảng không
    const validAnswers = Array.isArray(question.answer) 
        ? question.answer.map(ans => ans.toUpperCase())  // Nếu là mảng, chuyển tất cả phần tử thành chữ hoa
        : [question.answer.toUpperCase()];  // Nếu không phải mảng, đóng gói thành mảng có một phần tử

    // Kiểm tra xem enteredAnswer có nằm trong danh sách validAnswers không
    if (validAnswers.includes(enteredAnswer)) {
        score++;
        textarea.classList.add("correct-answer");
    } else {
        textarea.classList.add("wrong-answer");
        textarea.value += `\nĐáp án đúng: ${validAnswers.join(", ")}`;
    }
}
 else if (question.type === "multiple-choice") {
        const correctAnswer = question.answer.toUpperCase();
        if (enteredAnswer === correctAnswer) {
            textarea.classList.add("correct-answer");
            score++;
        } else {
            textarea.classList.add("wrong-answer");
            textarea.value += `\nĐáp án đúng: ${correctAnswer}`;
        }
    }
    else if (question.type === "true-false") {
    const selectedAnswers = [];  // Mảng lưu đáp án đã chọn của người dùng

    // Lấy tất cả các lựa chọn từ A, B, C, D
    const choices = ['A', 'B', 'C', 'D'];
    for (let i = 0; i < question.number_choice; i++) {
        const selectElement = document.getElementById(`${specificQuestionID}_${choices[i]}`);
        if (selectElement) {
            selectedAnswers.push(selectElement.value);  // Lưu đáp án Đ/S của người dùng
        }
    }

    // Ghép các đáp án vào chuỗi enteredAnswer (Đ/S/... cho từng lựa chọn)
    enteredAnswer = selectedAnswers.join("/");

    // Kiểm tra câu trả lời đúng
    const correctAnswer = question.answer;  // Câu trả lời đúng: "Đ/S/Đ/Đ"
    

    // So sánh enteredAnswer với đáp án đúng
    if (enteredAnswer === correctAnswer) {
      score++;
        textarea.classList.add("correct-answer");
    } else {
        textarea.classList.add("wrong-answer");
        textarea.value += `\nĐáp án đúng: ${correctAnswer}`;
    }

    // Append the textarea to the result element
    const resultElement = document.getElementById("result");
    resultElement.appendChild(textarea);
}
  

    // Append the textarea to the result element
    const resultElement = document.getElementById("result");
    resultElement.appendChild(textarea);
}

    // Display the final result
    let finalscore = parseFloat(((Math.round((score / questions.length) * 100) / 100) * 10) - luotthoat*0.25).toFixed(2);
    finalRes.innerHTML = `${finalscore}`;


    let trudiemluotthoat = luotthoat*0.25;
    resultElementFinal.innerHTML = `<p><br> <h2> Tổng kết bài làm </h2><br> - Thời gian làm bài: ${elapsedTime}<br> - Số câu làm đúng: ${score}/${questions.length}  <br> - Điểm bài làm: ${finalscore} /10 <br>- Số lượt thoát khỏi màn hình khi bài làm là: ${luotthoat} (- ${trudiemluotthoat} điểm) </p>`;

     resultElementFinal.insertAdjacentHTML("beforeend", `<p><br> <h2> Điểm : ${finalscore} /10  </p>`);


    resultElement.insertAdjacentHTML("beforeend", `<p>${finalscore} </p>`);


    timedotestFinal.innerHTML = `${elapsedTime}`;
    numberCorrect.innerHTML = `${score}/${questions.length}`;
    ResultInput();

    }, 3000);
  
  


 
                      
}

function ResultInput(){
    var contentToCopy = document.getElementById("date").textContent;
    var contentToCopy2 = document.getElementById("id_test").textContent;
    var contentToCopy3 = document.getElementById("name_test").textContent;
    var contentToCopy4 = document.getElementById("subject_test").textContent;
    var contentToCopy6 = document.getElementById("timedotest").textContent;
    var contentToCopy7 = document.getElementById("finalresultinput").textContent;
    var contentToCopy8 = document.getElementById("save-result").textContent;
    var contentToCopy9 = document.getElementById("number_correct_ans").textContent;
    var contentToCopy10 = document.getElementById("exam_uuid").textContent;


    document.getElementById("dateform").value = contentToCopy;
    document.getElementById("idtest").value = contentToCopy2;
    document.getElementById("testname").value = contentToCopy3;
    document.getElementById("test_type").value = contentToCopy4;
    document.getElementById("timedotestform").value = contentToCopy6;
    document.getElementById("band_score_form").value = contentToCopy7;
    document.getElementById("userAnswerSave").value = contentToCopy8;
    document.getElementById("number_correct").value = contentToCopy9;
    document.getElementById("testsavenumber").value = contentToCopy10;


}

    function getUserAnswer(question) {
      
      return document.getElementById(question).value.trim();
    }

 
// Hàm khởi tạo
function init() {
    let previousPart = null;
    for (let i = 0; i < questions.length; i++) {
       const currentPart = questions[i].part;
       let specificQuestionID =   `part_${questions[i].part}_question_${questions[i].question}`;

        const isFirstOfPart = currentPart !== previousPart;
        createQuestionElement(questions[i], isFirstOfPart);
        createCheckboxElement(questions[i].question, specificQuestionID); // Tạo checkbox tương ứng
        previousPart = currentPart;
    }
}

init();


function startTimer(durationInSeconds, submitFunction) {
    let seconds = durationInSeconds;

    const countdown = setInterval(function() {
        const minutes = Math.floor(seconds / 60);
        let remainingSeconds = seconds % 60;

        remainingSeconds = remainingSeconds < 10 ? '0' + remainingSeconds : remainingSeconds;


        if (seconds === 0) {
            clearInterval(countdown);
            //submitAnswers(event);
            // Disable input elements (checkboxes) when timer reaches 0

            disableInputElements();
        } else {
            seconds--;
        }
    }, 1000);
}






function disableInputElements() {
    const inputs = document.querySelectorAll('input[type="radio"]');
    inputs.forEach(input => {
        input.disabled = true;
    });
}


function submitFunction() {
    alert('Time is up!'); // Placeholder for your actual submit function
  }

      


        // Set the initial value (in seconds)
    const initialValue = option;

        // Check if the initial value is greater than 0 before starting the timer
    if (initialValue > 0) {
            startTimer(initialValue, submitFunction);
}




</script>
</body>
</html>

<?php
 get_footer();
} else {
    get_header();
    echo '<p>Please log in start reading test.</p>';
    get_footer();
}