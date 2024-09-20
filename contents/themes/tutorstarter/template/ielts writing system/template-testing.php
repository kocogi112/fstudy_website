<?php
/*
 * Template Name: Doing Template Writing
 * Template Post Type: ieltswritingtests
 
 */


if (is_user_logged_in()) {
    $post_id = get_the_ID();
    $user_id = get_current_user_id();
    $additional_info = get_post_meta($post_id, '_ieltswritingtests_additional_info', true);

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['doing_text'])) {
        $textarea_content = sanitize_textarea_field($_POST['doing_text']);
        update_user_meta($user_id, "ieltswritingtests_{$post_id}_textarea", $textarea_content);

        wp_safe_redirect(get_permalink($post_id) . 'get-mark/');
        exit;
    }
$post_id = get_the_ID();

// Get the custom number field value
$custom_number = get_post_meta($post_id, '_ieltswritingtests_custom_number', true);

   //get_header();
    ?>
    <script>
    // Log the additional info to the console.
</script>
        <div><?php the_content(); ?></div>



<html lang="en">
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://smtpjs.com/v3/smtp.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    <script type="text/javascript" src="http://localhost/wordpress/contents/themes/tutorstarter/ielts-writing-toolkit/function/handwriting/handwriting.js"></script>

  
    <!--<script type="text/javascript" async src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.0/MathJax.js?config=TeX-MML-AM_CHTML">
    if (window.MathJax) {
        MathJax.Hub.Config({
            tex2jax: {
                inlineMath: [["$", "$"], ["\\(", "\\)"]],
                processEscapes: true
            }
        });
    }
    </script>    -->

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Onluyen247</title>
    <link rel="stylesheet" href="http://localhost/wordpress/contents/themes/tutorstarter/ielts-writing-toolkit/style/style.css">
    
</head>

<body onload="main()">
    <div class="header">
          <a class="logo">Onluyen247.net</a>
          <div class="header-right">
            <a  href="#home">Home</a>
            
          </div>
        </div>
    <div class="container"  >
        <div class="main-block">

         


            <h1 style="text-align: center;" id="title"></h1>
            <div id="basic-info">
                <div style="display: flex;">
                    <b style="margin-right: 5px;">Description </b>
                    <div id="description"></div>
                </div>
                <div style="display: flex;">
                    <b style="margin-right: 5px;">Thời gian làm bài: </b>
                    <div id="duration"></div>
                </div>
                <div style="display: flex;">
                    <b style="margin-right: 5px;">ID Test:</b>
                    <div id="id_test_div"></div>
                </div>
                <div style="display: flex;">
                    <b style="margin-right: 5px;">ID Category: </b>
                    <div id="id_category"></div>
                </div>
                





        <div id ="info-div">
                <div id="date-div"></div>
                <div id="number-of-question-div" ></div>
                <div id="id-category-div"></div>
                <div id="question-test-div" ></div>
                <div id="user-essay-div"></div>
                <div id="sample-essay-div" ></div>
                <div id="overall-band-div" ></div>
                <div id="time-do-test-div" ></div>
                <div id="summarize-test-div" ></div>
                <div id="overall-band-and-comment-div" ></div>
                <div id="analysis-test-div" ></div>
            </div>

                <div style="display: flex;">
                    <b style="margin-right: 5px;">Số câu hỏi: </b>
                    <div id="number-questions"></div>
                </div>
                <div style="display: flex;">
                    <b style="margin-right: 5px;">Percentage of correct answers that pass the test: </b>
                    <div id="pass_percent"></div>
                </div>
                <div style="display: flex;">
                    <b style="margin-right: 5px;">List of question: </b>
                    <button id = "show-list-popup-btn">Show list</button>
                </div>

                <div id= "show-list-popup" class="popup">
                    <div class="popup-content-1">
                
                        <span class="close" onclick="closeListQuestion()">&times;</span>
                         <h2>Danh sách câu hỏi</h2>
                         <div id="question-list"></div>
                    </div>
                 </div>


                 
            </div>
            <button class="button-3"  id="start-test" onclick="showLoadingPopup()">Bắt đầu làm bài</button>
            
            
            <div id ="overall_band_test_container" style="display:none">

                <div id ="full_band_chart" class="chart_overall_full">
                    <div class ="left_chart">
                        <h3 style="color:red">FULL OVERALL</h3>
                        <canvas id="barchartfull" style="width:100%;max-width:600px"></canvas>
                    </div>
                    <div class ="right_chart">
                        <div id="detail_score"></div>
                    </div>


                </div>
                <div id ="area-final">
                    <button class ="button-3">Rent teacher to mark this essay</button>
                    <button onclick="window.print();" class ="button-3">Print your essay</button>

                </div>
                

            
            </div>
         <!-- New add sửa đổi 3/8/2024-->

            <div class = "fixedrightsmallbuttoncontainer">
                <button  class ="buttonsidebar"  id="report-error"><img width="22px" height="22px" src="http://localhost/wordpress/contents/themes/tutorstarter/ielts-writing-toolkit/assets/images/report.png" ></button><br>  
                   <button class ="buttonsidebar"  id="full-screen-button">⛶</button><br>
                    <button  onclick=" DarkMode()" class ="buttonsidebar"><img width="30px" height="30px" src="http://localhost/wordpress/contents/themes/tutorstarter/ielts-writing-toolkit/assets/images/dark-mode.png"></img></button><br>
                </div>
                
         
         
         <div id="report-error-popup" class="popup-report">
                    
            <div class="popup-content-report">
         
         
         <span class="close" onclick="closeReportErorPopup()">&times;</span>
                <section class ="contact">
                <h2 style="text-align: center;" > Báo lỗi đề thi, đáp án </h2>
         
         
                <form action="#" reset()>
                <div class="input-box">
                    <div class="input-field field">
                <input type="text" id ="name" class="item" placeholder="Tên của bạn" autocomplete="off">
                            <div class="error-txt">Không được bỏ trống ô này</div>
         
            </div>
         
            <div class="input-field field">
                <input type="email" class="item" id="email" placeholder="Nhập email của bạn" autocomplete="off">
                <div class="error-txt email">Không được bỏ trống ô này</div>
         
            </div>
         </div>
         
         <div class="input-box">
                    <div class="input-field field">
                <input id="testnamereport" class="item" type="text" name="testnamereport" autocomplete="off" placeholder="Nhập tên đề thi báo lỗi" >
                            <div class="error-txt">Không được bỏ trống ô này</div>
         
            </div>
         
         
                    <div class="input-field field">
         
                <input type="text" name="testnumberreport" id="testnumberreport" class="item" autocomplete="off" placeholder="Bạn muốn báo lỗi câu số mấy" >
                            <div class="error-txt">Không được bỏ trống ô này</div>
         
            </div>
         </div>
         
         <div class ="textarea-field field">
                <textarea id = "descriptionreport" autocomplete="off" rows="4" name="description" placeholder="Mô tả thêm về lỗi" class="item"> </textarea>
                <div class="error-txt">Không được bỏ trống ô này</div>
            </div>
                  <div class="g-recaptcha" data-sitekey="6Lc9TNkpAAAAAKCIsj_j8Ket1UH5nBwoO2DmKZ_e
         "></div>
         
                <button type="submit">Gửi báo lỗi</button>
            </form>
         </section>
            </div>
         </div>
         
         
         
         
         
                
                
         </div>
         <!-- End new add sửa đổi 3/8/2024-->
  
            <div id="quiz-container"></div>
            
        </div>
        
        <div id="time-remaining-container">
            <div id="button-container">
                <div id="clock-block">
                    <h3 id="countdown"></h3>
                </div>
                
            </div>
            <div id = "navigation-button" style="display: none;" >
                <button id="prev-button"  onclick="showPrevQuestion()">Quay lại</button>
                <button id="next-button"  onclick="showNextQuestion()">Tiếp theo</button>

                
            </div>

            <div id = "submit-button-container">  
                <button onclick="preSubmitTest()" style="display: none;" id="submit-button">Nộp bài làm</button>                
            </div>
        </div>

        <div id="canvas-container"></div>

 <!-- Next button -->




        <div id="loading-popup" style="display: none;">
            <div id="loading-spinner"></div>
        </div>
    </div>
  <!-- giấu form send kết quả bài thi -->


    
  <!--
  <span id="message" style="display: none;"></span>
  <form id="frmContactUs"  style="display: none;">
          <div class="card">
              <div class="card-header">Form lưu kết quả</div>
              <div class="card-body" >

                
 
     <div class = "form-group" >
          <input   type="text" id="resulttest" name="resulttest" placeholder="Kết quả"  class="form-control form_data" />
          <span id="result_error" class="text-danger" ></span>
 
     </div>

     <div class = "form-group">
        <input type="text" id="dateform" name="dateform" placeholder="Ngày"  class="form-control form_data"  />
        <span id="date_error" class="text-danger" ></span>
    </div>
 
 
     <div class = "form-group">
          <input type="text" id="numberofquestionform" name="numberofquestionform" placeholder="Số câu hỏi"  class="form-control form_data"  />
          <span id="date_error" class="text-danger" ></span>
    </div>

    <div class = "form-group" >
        <input type="text" id="idtest" name="idtest" placeholder="Id test"  class="form-control form_data" />
        <span id="idtest_error" class="text-danger" ></span>
   </div>

    
 
      
 
     <div class = "form-group"  >
          <input type="text" id="timedotest" name="timedotest" placeholder="Thời gian làm bài"  class="form-control form_data" />
          <span id="time_error" class="text-danger"></span>
     </div>
 
    
 
     <div class = "form-group" >
          <input type="text" id="idcategory" name="idcategory" placeholder="Id category"  class="form-control form_data" />
          <span id="idcategory_error" class="text-danger"></span>
     </div>
 
      <div class = "form-group"   >
          <input type="text"  id="testname" name="testname" placeholder="Test Name"  class="form-control form_data" />
          <span id="testname_error" class="text-danger"></span>
     </div>
 
     </div>
 
    <div class="card-footer" style="display: none;">
        <button type="button" name="submit" id="submit" class="btn btn-primary" onclick="save_data(); return false;">Save</button>
                       <td><input type="submit" id="submit" name="submit"/></td> 
 
      </div>
          
  </div>
  <div id="result_msg" style="display: none;"></div>
 </form>-->
 <!-- kết thúc send form -->	

    <script >
    

<?php echo wp_kses_post($additional_info);
 ?>

        

 
                    document.getElementById("title").innerHTML = quizData.title;

                    var questionList = document.getElementById("question-list");
                    questionList.innerHTML = ""; // Clear any existing content

                for (let i = 0; i < quizData.questions.length; i++) {
                    questionList.innerHTML += `Question ${i + 1}: ${quizData.questions[i].question}<br>`;
                }
              

               
                    
            

      /*  (function() {
    const devtools = { open: false };

    const threshold = 160;
    const check = () => {
        const widthThreshold = window.outerWidth - window.innerWidth > threshold;
        const heightThreshold = window.outerHeight - window.innerHeight > threshold;
        
        if (!(heightThreshold && widthThreshold) && ((window.Firebug && window.Firebug.chrome && window.Firebug.chrome.isInitialized) || widthThreshold || heightThreshold)) {
            devtools.open = true;
            alert('Phát hiện dev tool '); // sau này sẽ redirect đến trang khác để tránh access 
        } else {
            devtools.open = false;
        }
    };

    window.addEventListener('resize', check);
    check();
})();*/
const currentDate = new Date();

            // Get day, month, and year
const day = currentDate.getDate();
const month = currentDate.getMonth() + 1; // Adding 1 because getMonth() returns zero-based month index
const year = currentDate.getFullYear();

            // Display the date
const dateElement = document.getElementById('date-div');
dateElement.innerHTML = `${day}/${month}/${year}`;


        
        document.getElementById("show-list-popup-btn").addEventListener('click', openListQuestion);
        
        // Close the draft popup when the close button is clicked
        function closeListQuestion() {
            document.getElementById('show-list-popup').style.display = 'none';
        }

        function openListQuestion() {
            document.getElementById('show-list-popup').style.display = 'block';
          
        }




function openDraft(index) {
  var x = document.getElementById("draft-"+index);
  if (x.style.display === "block") {
    x.style.display = "none";
  } else {
    x.style.display = "block";
  }
}

let currentQuestionIndex = 0;
function main() {
    //MathJax.Hub.Queue(["Typeset", MathJax.Hub]);
    if (quizData.description !== "")
        document.getElementById("description").innerHTML = quizData.description;

    
    document.getElementById("duration").innerHTML = formatTime(quizData.duration);
    document.getElementById("pass_percent").innerHTML = quizData.pass_percent + "%";
    document.getElementById("number-questions").innerHTML = quizData.number_questions + " question(s)";
    document.getElementById("id_test_div").innerHTML = quizData.id_test;

    let contentQuestions = "";


 for (let i = 0; i < quizData.questions.length; i++) {
    let questionId = quizData.questions[i].id;
    let part = quizData.questions[i].part;
    let sampleEssay= quizData.questions[i].sample_essay;
    let questioncontent = quizData.questions[i].question;
    let explanationQuiz = quizData.questions[i].explanations;



        // if need bookmark, add to line class="question"<image src="bookmark-1.png" id="imageToggle" onclick="rememberQuestion(${i + 1})"></image>             

    contentQuestions += `


    <div class="questions" id="question-${i}" style="display:none;" >
        



        <div id ="current_band_detail_div-${questionId}" style="display:none;"></div>
        <div class="quiz-section">
            <div class="question-side">
                <p class="header-question">Question ${(i + 1)}:  </p>
                <div id="navigation-buttons" display='none'>
                <!-- Next button -->
           
        
                </div>

                <p class="question"><p style="font-style:italic"> Writing task ${part} </p> <b>${questioncontent}</b><img width="85%" src="${quizData.questions[i].image}"></p>
                
                <button onclick="openDraft(${i})" class ='open-draft-button'>Open Draft</button>

                <textarea class="draft" id="draft-${i}"></textarea>

                <div id ="sample-essay-area-${i}" style="display:none;"><h2 style='color:red'>Sample Essay:</h2> <br>${sampleEssay} </div> 

                <div id="overall-band-${questionId}" class="overall-band" style="display:none;"></div>
            </div>


            <div class="answer-side">
                <div class="answer-list">
                    <button id="input_normal" style ="display:none" onclick="toggleInputMode(${i}, 'normal')">Nhập văn bản bình thường</button>
                    <button id="input_handwrite" style ="display:none" onclick="toggleInputMode(${i}, 'handwrite')">Chuyển sang dạng viết (Phù hợp với thí sinh thi paper exam)</button>
                    <textarea spellcheck="false" class="exam_area" placeholder="You should start your essay here..." id="question-${i}-input" oninput="updateWordCount(${i})"></textarea>
                   <!--16/8/2024 -->
                <div class ="userEssayContainer" id = "userEssayCheckContainer${i+1}">
                    <h3>Your Essay</h3>
                    <div  id ="userEssayCheck-${i+1}" ></div>
                </div>
                <!--16/8/2024 -->
                    <div id="word-count-${i}" class="word-count">Word count: 0 Sentence count: 0 Paragraph: 0</div>
                    <button style="display:none" onclick="checkIncorrectSpellings(${i})" >Check</button>
                    <div id ="correction-${i}"></div>
               
                
                    </div>
                <div class="explain-zone" id="explanation-${questionId}" style="display:none;">
                    <p><b>Giải thích: </b>${explanationQuiz}</p>
                </div>

                <div id="summarize-${questionId}" style="display:none;"> </div>
                <div id="detail-cretaria-${questionId}" style="display:none;"> </div>
                <div id="recommendation-${questionId}" style="display:none;"> </div>

            </div>
        </div>
    </div>`;
}


    document.getElementById("quiz-container").innerHTML = contentQuestions;
    document.getElementById("quiz-container").style.display = 'none';
    document.getElementById("submit-button").style.display = 'none';
    document.getElementById("clock-block").style.display = 'none';

   // addEventListenersToInputs();
}




 




/*
function toggleInputMode(questionIndex, mode) {
    const textarea = document.getElementById(`question-${questionIndex}-input`);
    const canvasContainerId = `canvas-container-${questionIndex}`;
    let canvasContainer = document.getElementById(canvasContainerId);



    if (mode === 'handwrite') {
        
        if (!canvasContainer) {
            canvasContainer = document.createElement('div');
            canvasContainer.id = canvasContainerId;
            canvasContainer.innerHTML = `
                <div class = "canvas_exam_area">
                         <canvas id="canvas-${questionIndex}"   width="590px" height = "700px" style="border: 1px solid; cursor: pointer;"></canvas>
                </div>
                <div class='buttons'>
                    <button onclick="recognizeCanvas(${questionIndex})">Recognize</button>
                    <button onclick="eraseCanvas(${questionIndex})">Erase</button>
                    <button onclick="undoCanvas(${questionIndex})">Undo</button>
                    <button onclick="redoCanvas(${questionIndex})">Redo</button>
                    <button onclick="toggleEraser(${questionIndex})">Toggle Eraser</button>
                    <button onclick="drawLines(${questionIndex})">Draw Lines</button>
                </div>
                <div>Result: <span id='result-${questionIndex}'></span></div>
            `;
            textarea.parentNode.insertBefore(canvasContainer, textarea.nextSibling);
            initializeCanvas(questionIndex);






        }
        textarea.style.display = 'none';
        canvasContainer.style.display = 'block';
    } 
    
    
    else {
        if (canvasContainer) {
            canvasContainer.style.display = 'none';
        }
        textarea.style.display = 'block';
    }



    
} */
let undoStack = [];
let redoStack = [];

function initializeCanvas(questionIndex) {
    const canvasElement = document.getElementById(`canvas-${questionIndex}`);
    const canvas = new handwriting.Canvas(canvasElement, 3);
    canvas.setCallBack((data, err) => {
        if (err) throw err;
        document.getElementById(`result-${questionIndex}`).innerHTML = data;
    });

    canvas.setLineWidth(5);
    canvas.setOptions({
        language: "en",
        numOfReturn: 1
    });

    canvasElement.canvasObj = canvas;

    // Add drawing event listeners
    let isDrawing = false;

    canvasElement.addEventListener('mousedown', () => {
        isDrawing = true;
        saveState(canvasElement);
    });

    canvasElement.addEventListener('mousemove', (e) => {
        if (isDrawing) {
            canvasElement.canvasObj.stroke(e);
        }
    });

    canvasElement.addEventListener('mouseup', () => {
        if (isDrawing) {
            recognizeCanvas(questionIndex);
            isDrawing = false;
        }
    });

    canvasElement.addEventListener('mouseout', () => {
        if (isDrawing) {
            recognizeCanvas(questionIndex);
            isDrawing = false;
        }
    });
}

function saveState(canvasElement) {
    undoStack.push(canvasElement.toDataURL());
    if (undoStack.length > 50) { // Limit the undo stack size
        undoStack.shift();
    }
    redoStack = []; // Clear the redo stack
}

function restoreState(stack, canvasElement) {
    if (stack.length > 0) {
        const ctx = canvasElement.getContext('2d');
        const canvasState = stack.pop();
        const img = new Image();
        img.src = canvasState;
        img.onload = () => {
            ctx.clearRect(0, 0, canvasElement.width, canvasElement.height);
            ctx.drawImage(img, 0, 0);
            recognizeCanvas(0); // Automatically call recognizeCanvas
        };
    }
}

function recognizeCanvas(questionIndex) {
    document.getElementById(`canvas-${questionIndex}`).canvasObj.recognize();
}

function eraseCanvas(questionIndex) {
    const canvasElement = document.getElementById(`canvas-${questionIndex}`);
    const ctx = canvasElement.getContext('2d');
    ctx.clearRect(0, 0, canvasElement.width, canvasElement.height);
    saveState(canvasElement); // Save state after erasing
    recognizeCanvas(questionIndex);
}

function undoCanvas(questionIndex) {
    const canvasElement = document.getElementById(`canvas-${questionIndex}`);
    if (undoStack.length > 0) {
        redoStack.push(canvasElement.toDataURL());
        restoreState(undoStack, canvasElement);
    }
}

function redoCanvas(questionIndex) {
    const canvasElement = document.getElementById(`canvas-${questionIndex}`);
    if (redoStack.length > 0) {
        undoStack.push(canvasElement.toDataURL());
        restoreState(redoStack, canvasElement);
    }
}

function toggleEraser(questionIndex) {
    const canvasElement = document.getElementById(`canvas-${questionIndex}`);
    const canvas = canvasElement.canvasObj;
    canvas.isEraser = !canvas.isEraser;
    canvas.setLineWidth(canvas.isEraser ? 30 : 5);
    canvas.setPenColor(canvas.isEraser ? "white" : "black");
}

function drawLines(questionIndex) {
    const canvasElement = document.getElementById(`canvas-${questionIndex}`);
    const ctx = canvasElement.getContext("2d");
    ctx.lineWidth = 1;
    ctx.strokeStyle = "#e0e0e0";
    const lineHeight = 40;
    for (let y = lineHeight; y < canvasElement.height; y += lineHeight) {
        ctx.beginPath();
        ctx.moveTo(0, y);
        ctx.lineTo(canvasElement.width, y);
        ctx.stroke();
    }
    saveState(canvasElement); // Save state after drawing lines
}

// Call initializeCanvas for question 0
//initializeCanvas(0);

/*
document.getElementById('input_handwrite').addEventListener('click', () => toggleInputMode(currentQuestionIndex, 'handwrite'));
document.getElementById('input_normal').addEventListener('click', () => toggleInputMode(currentQuestionIndex, 'normal'));
*/

//submitTest();

function showLoadingPopup() {
    let timerInterval;
    Swal.fire({
        title: "Đang tải bài thi",
        html: "Vui lòng đợi trong giây lát.",
        timer: 1000,
        allowOutsideClick: false,
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading();
            const timer = Swal.getPopup().querySelector("b");
        },
        willClose: () => {
            clearInterval(timerInterval);
        }
    }).then((result) => {
        if (result.dismiss === Swal.DismissReason.timer) {
            Swal.fire({
                icon: "success",
                title: "Bài thi của bạn đã sẵn sàng",
                html: "Nhấn vào <b>Bắt đầu làm bài</b> để bắt đầu bài thi."
            });
            startTest();
        }
    });
}



function startTest() {
    

    document.getElementById("quiz-container").style.display = 'block';
    document.getElementById("start-test").style.display = 'none';
    document.getElementById("clock-block").style.display = 'block';
    document.getElementById("submit-button").style.display = 'block';
    document.getElementById("navigation-buttons").style.display = 'flex';
    
    document.getElementById("navigation-button").style.display = 'block';

    hideBasicInfo();
    showQuestion(currentQuestionIndex);

    const duration = quizData.duration * 60;
    startCountdown(duration);
}
function doit(index, event) {
    event.preventDefault();  // Explicitly prevent form submission

    var langCode = document.getElementById(`lang-${index}`).value;
    tinyMCE.activeEditor.execCommand("mceWritingImprovementTool", langCode);
}

function hideBasicInfo() {
    document.getElementById("basic-info").style.display = 'none';
}

function showQuestion(index) {
    const questions = document.getElementsByClassName("questions");
    for (let i = 0; i < questions.length; i++) {
        questions[i].style.display = "none";
    }
    questions[index].style.display = "block";

    document.getElementById("prev-button").style.display = index === 0 ? "none" : "inline-block";
    document.getElementById("next-button").style.display = index === questions.length - 1 ? "none" : "inline-block";
}

function showPrevQuestion() {
    if (currentQuestionIndex > 0) {
        currentQuestionIndex--;
        showQuestion(currentQuestionIndex);
    }
}

function showNextQuestion() {
    if (currentQuestionIndex < quizData.questions.length - 1) {
        currentQuestionIndex++;
        showQuestion(currentQuestionIndex);
    }
}

function addEventListenersToInputs() {
    const inputs = document.getElementsByClassName('input');
    for (let i = 0; i < inputs.length; i++) {
        inputs[i].addEventListener('focus', function () {
            this.classList.add('input-focused');
        });
        inputs[i].addEventListener('blur', function () {
            this.classList.remove('input-focused');
        });
    }

    for (let i = 0; i < quizData.questions.length; i++) {
        document.getElementById(`input_handwrite-${i}`).addEventListener('click', () => switchToCanvas(i));
        document.getElementById(`input_normal-${i}`).addEventListener('click', () => switchToTextarea(i));
    }
}



// khai báo biến chạy xuyên suốt code các file js

        //common information test (Length, Paragraph,Sentence)
let length_essay = ``;
let paragraph_essay =``;
let sentence_count ;


let userLevel;
let band_description;
let overallband  = 0;
let task_achievement_comment =``;
let lexical_resource_comment =``;
let grammatical_range_and_accuracy_comment =``;
let coherence_and_cohesion_comment = ``;
let task_achievement_part_1 = 0;
let grammatical_range_and_accuracy_part_1 = 0;
let lexical_resource_part_1 = 0;
let coherence_and_cohesion_part_1 = 0;
        //linking word
let total_linking_word_count;
let linking_word_array_essay = ``;
let linking_word_to_accumulate = ``;
let unique_linking_word_count = ``;

let spelling_grammar_error_essay =``;
let spelling_grammar_error_count;

let point_for_intro_cheking_part_1_essay = ``;
let point_for_second_paragraph_cheking_part_1_essay = ``;


let position_introduction_task_1;
let position_overall_task_1;
let position_body_task_1;

        /* relation point between user essay and question 
            (* If count_common_number > 4) 
                relation_point_essay_and_question = point_for_intro_cheking_part_1_essay
            */
let relation_point_essay_and_question;


        //increase, decrease,...
let increase_word_array = ``;
let increase_word_count = ``;
let unique_increase_word_count;


let decrease_word_array = ``;
let decrease_word_count = ``;
let unique_decrease_word_count;


let unchange_word_array = ``;
let unchange_word_count = ``;
let unique_unchange_word_count;


let goodVerb_word_array = ``;
let goodVerb_word_count = ``;
let unique_goodVerb_word_count = ``;


let well_adjective_and_adverb_word_array = ``;
let well_adjective_and_adverb_word_count = ``;
let unique_well_adjective_and_adverb_word_count = ``;



let adjective_and_adverb_word_array = ``;
let adjective_and_adverb_word_count = ``;
let unique_adjective_and_adverb_word_count = ``;


let simple_sentences_count ;
let complex_sentences_count;
let compound_sentences_count;

let position_simple_sentences;
let position_complex_sentences;
let position_compound_sentences;
let simple_sentences = ``;
let complex_sentences = ``;
let compound_sentences = ``;




        //data checking  - common_numbers cần thiết trong đoạn
let count_common_number;
let type_of_essay; // agree/disagree, bar chart,...


        //structure Overall - Check thứ tự introduction, overall và detail 
let structure_info;

let countdownInterval;

function startCountdown(duration) {
    let timer = duration;
    const countdownElement = document.getElementById("countdown");

    countdownInterval = setInterval(function() {
        const minutes = Math.floor(timer / 60);
        const seconds = timer % 60;
        countdownElement.textContent = `${minutes}:${seconds < 10 ? '0' + seconds : seconds}`;
        if (--timer < 0) {
            clearInterval(countdownInterval);
            preSubmitTest();
        }
    }, 1000);
}

function formatTime(minutes) {
    return `${minutes} minutes`;
}

/*
The given table compares different means of transportation in terms of the annual distance traveled by adults in two separate years, 1977 and 2007. Units are measured in miles. Overall, cars were by far the most soar popular method of transport during the entire 40-year period, witnessing the most dramatic rise. In contrast, bicycles and motorcycles were the least common modes of transportation. Regarding changes in commuting patterns, there was an upward trend in the use of cars, trains, and taxis, while the remaining and rise methods of transport recorded a to soar decline. In 1977, cars occupied the position as the to continuing most prevalent vehicle, with 3500 miles traveled, nearly decrease quadruple the distance of the second and third most popular methods, buses and trains, which ranged from 800 to 900 miles. Meanwhile, the distance traveled on foot was 400 miles on average, twice as high as that of plummet taxis. Bicycles wass as common as motorbikes, with the average distancess for each vehicle standing at 100 miles. By 2007, the distance traveled by car had increase twofold to 7100 miles, solidifying its position as the most preferred mode of transportation. Similar changes were seen in the figures for trains and taxis, with the former witnessing a slight growth to 1000 miles and the latter recording a fourfold rise to 800 miles. In contrast, the other transport methods underwent a descending trend, with the most dramatic drop recorded in buses, falling by 300 miles to reach 500 miles in 2007. The distances traveled by walking, motorbikes, and bicycles dropped to 300, 90, and 80 miles, respectively.

*/




    </script>
    



    <script src="http://localhost/wordpress/contents/themes/tutorstarter/ielts-writing-toolkit/function/description/mark_description.js"></script> 




    <script src="http://localhost/wordpress/contents/themes/tutorstarter/ielts-writing-toolkit/function/full_overall_chart/full_band_chart.js"></script>
    <script  src="http://localhost/wordpress/contents/themes/tutorstarter/ielts-writing-toolkit/function/process.js"></script>
    


    <script  src="http://localhost/wordpress/contents/themes/tutorstarter/ielts-writing-toolkit/function/right_bar_feature/reload-test.js"></script>
    <script  src="http://localhost/wordpress/contents/themes/tutorstarter/ielts-writing-toolkit/function/right_bar_feature/fullscreen.js"></script>
    <script  src="http://localhost/wordpress/contents/themes/tutorstarter/ielts-writing-toolkit/function/right_bar_feature/report-error.js"></script>
    <script  src="http://localhost/wordpress/contents/themes/tutorstarter/ielts-writing-toolkit/function/right_bar_feature/change-mode.js"></script>


  <script src="http://localhost/wordpress/contents/themes/tutorstarter/ielts-writing-toolkit/submitTest_2.js"></script>





   

</body>
</html>
<?php
    get_footer();
} else {
    get_header();
    echo '<p>Please log in to submit your answer.</p>';
    get_footer();
}