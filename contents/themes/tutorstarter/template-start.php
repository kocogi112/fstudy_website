<?php
/*
 * Template Name: Doing Version 2 Template
 * Template Post Type: exam
 
 */

    if (!defined('ABSPATH')) {
        exit; // Exit if accessed directly.
    }

    remove_filter('the_content', 'wptexturize');
    remove_filter('the_title', 'wptexturize');
    remove_filter('comment_text', 'wptexturize');

if (is_user_logged_in()) {
    $post_id = get_the_ID();
    $user_id = get_current_user_id();
    $additional_info = get_post_meta($post_id, '_exam_additional_info', true);

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['doing_text'])) {
        $textarea_content = sanitize_textarea_field($_POST['doing_text']);
        update_user_meta($user_id, "exam_{$post_id}_textarea", $textarea_content);

        wp_safe_redirect(get_permalink($post_id) . 'get-result/');
        exit;
    }
$post_id = get_the_ID();

// Get the custom number field value
$custom_number = get_post_meta($post_id, '_testsonlinesystem_custom_number', true);
   get_header();

    ?>
        
        
    <script>
    // Log the additional info to the console.
</script>
              <h1><?php the_title(); ?></h1>
        <div><?php the_content(); ?></div>



        <html lang="en">
        <head>
        <script type="text/javascript" async src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.0/MathJax.js?config=TeX-MML-AM_CHTML">
        if (window.MathJax) {
                MathJax.Hub.Config({
                tex2jax: {
                    inlineMath: [["$", "$"], ["\\(", "\\)"]],
                    processEscapes: true
                }
            });
        }
        </script>    
<script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>


            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title></title>
            <link rel="stylesheet" href="/wordpress/contents/themes/tutorstarter/system-test-toolkit/style/style.css">
            <style type="text/css">
                .quiz-section {
                    display: flex;
                    flex-direction: row;
                }
                .question-side, .answer-side {
                    width: 50%;
                    padding: 10px;
                }
                @media (max-width: 768px) {
                    .quiz-section {
                        flex-direction: column;
                    }
                    .question-side, .answer-side {
                        width: 100%;
                    }
                }
        </style>
        </head>

            <body onload="main()">
               <div class="header">
        <a class="logo" id = 'logo'>Onluyen247.net</a>
        <div class="header-right">
            <a  href="#home">Home</a>
            
        </div>
        </div> 

                <div  class="container">


                    <div class="blank-block"></div>
                    <div class="main-block">
                        <h1 style="text-align: center;" id="title"></h1>
                        <div id="basic-info">
                            <div id="description"></div>
                            <div style="display: flex;">
                                <b style="margin-right: 5px;">Thời gian làm bài: </b>
                                <div id="duration"></div>
                            </div>

                            <div style="display: flex;">
                                <b style="margin-right: 5px;">Phân loại đề thi: </b>
                                <div id="test_type"></div>
                            </div>

                            <div style="display: flex;">
                                <b style="margin-right: 5px;">ID Loại đề:  </b>
                                <div id="id_category"></div>
                            </div>

                            <div style="display: flex;">
                                <b style="margin-right: 5px;">Loại đề thi: </b>
                                <div id="label"></div>
                            </div>

                        

                            <div style="display: flex;">
                                <b style="margin-right: 5px;">ID đề thi: </b>
                                <div id="id_test"></div>
                            </div>
                            <div style="display: flex;">
                                <b style="margin-right: 5px;">Số câu hỏi: </b>
                                <div id="number-questions"></div>
                            </div>
                            <div style="display: flex;">
                                <b style="margin-right: 5px;">Percentage of correct answers that pass the test: </b>
                                <div id="pass_percent"></div>
                            </div>

                            <div id ="select-part-test">
                            </div>


                            <div>
                                <b>Instruction:</b>
                                <br> - You can retake the test as many times as you would like. <br> - If you run out of time, a notification will appear and you will no longer be able to edit your test answer. Pay attention to the time, it's right in the bottom right corner. <br> - You can skip a question to come back to at the end of the exam. <br> - If you want to finish the test and see your results immediately, or you finished the exam, press the "Submit Answers" button. <br>
                            </div>
                            <!-- time otion chọn thời gian bài làm -->
                            <!-- If user want to practice, you can choose this, otherwise  just click button to start test without change time limit-->

                            <h2>Bạn có thể luyện tập bằng cách chọn thời gian làm bài. Nếu không chọn thời gian sẽ mặc định  </h2>
                            <div>
                                <input type="radio" name="time-limit" id="limit-86400" value="86400" onclick="setTimeLimit(this.value)">
                                <label for="limit-86400">Không giới hạn</label>

                                <input type="radio" name="time-limit" id="time-100" value="100" onclick="setTimeLimit(this.value)">
                                <label for="time-100">30 phút</label>

                                <input type="radio" name="time-limit" id="time-500" value="500" onclick="setTimeLimit(this.value)">
                                <label for="time-500">45 phút</label>

                                <input type="radio" name="time-limit" id="time-1000" value="1000" onclick="setTimeLimit(this.value)">
                                <label for="time-1000">60 phút</label>

                                <input type="radio" name="time-limit" id="time-1000" value="1000" onclick="setTimeLimit(this.value)">
                                <label for="time-1000">90 phút</label>

                                <input type="radio" name="time-limit" id="time-1000" value="1000" onclick="setTimeLimit(this.value)">
                                <label for="time-1000">120 phút</label>

                                <input type="radio" name="time-limit" id="time-1000" value="1000" onclick="setTimeLimit(this.value)">
                                <label for="time-1000">150 phút</label>

                                <input type="radio" name="time-limit" id="time-1000" value="1000" onclick="setTimeLimit(this.value)">
                                <label for="time-1000">180 phút</label>

                            
                            

                        </div>


                        </div>
                        <div id = "current_module_container" >
                            <h3 id = "current_module" style="display: none;"></h3>

                        </div>
                    
        <!-- Đổi giao diện bài thi 
        <button id="change_appearance" style="display: none;">Đổi giao diện</button>-->

        <div id="change_appearance_popup" class="popup">

            <div class="popup-content">
                <span class="close" onclick="closeChangeDisplayPopup()">&times;</span>
                <image width ="90px" height ="90px" id = "change_appearane_default" alt="Change to appearance default" src="/wordpress/contents/themes/tutorstarter/system-test-toolkit/appearance_image/appearance_default.png"></image>
                <image width ="90px" height ="90px" id = "change_appearane_1" alt="Change to appearance 1" src="/wordpress/contents/themes/tutorstarter/system-test-toolkit/appearance_image/appearance_1.png"></image>
                <image width ="90px" height ="90px" id = "change_appearane_2" alt="Change to appearance 2" src="/wordpress/contents/themes/tutorstarter/system-test-toolkit/appearance_image/appearance_2.png"></image>
            </div>
        </div>
      

        <!-- end đổi giao diện bài thi -->


            
        <!-- Hiện xem nhanh đáp án
        <button id="change_appearance" style="display: none;">Đổi giao diện</button>-->

        <div id="quick-view_popup" class="popup">

            <div class="popup-content">
                <span class="close" onclick="closeQuickViewPopup()">&times;</span>
                <div id ="quick-view-answer"></div>
                
            </div>
        </div>

        <!-- end Hiện xem nhanh đáp án -->

                        <button id="start-test" onclick="showLoadingPopup()">Bắt đầu làm bài</button>
                        <h1 style="display: none;" id="final-result"></h1>
                        <h5 style="display: none;" id="time-result"></h5>
                        <h5  style="display: none;" id ="useranswerdiv"></h5>
                        <h5   style="display: none;" id ="correctanswerdiv"></h5>
                        <h3 style="display: none;" id="final-review-result"></h3>
                        <div style="display: none;" id="date" style="visibility:hidden;"></div>
                        <div style="display: none;" id ="header-table-test-result">
                                <table>
                                    <tr>
                                        <th >Ngày làm bài</th>
                                        <th>Tên đề thi</th>
                                        <th>Kết quả</th>
                                        <th>Thời gian làm bài</th>
                                    </tr>
                                    <tr>
                                        <td><p id="date-table-result"></p></td>
                                        <td> <p id="title-table-result"></p> </td>
                                        <td> <p id="final-review-result-table"></p> </td>
                                        <td> <p id='time-result-table'></p> </td>
                                    </tr>
                                </table>
                        </div>
                        

                                    
                <!-- The Modal -->
                <div id="myModal" class="modal">
                    <div class="modal-content">
                        <span class="close-modal">&times;</span>

                    <img id="modalImage" src="" style="width: 150%; height: 150%;">
                    </div>
                </div>
        
                
                        <div  id="quiz-container" ></div>
                    
                        <div id="navigation-buttons" style="display: none;">
                            <button id="prev-button" onclick="showPrevQuestion()">Previous</button>
                            <button id="next-button" onclick="showNextQuestion()">Next</button>
                        </div>
                    </div>
                
                    <div class="blank-block"></div>
                    <button id="toggle-time-button" onclick="toggleTimeRemaining()">Hide</button> <!-- Button to toggle visibility -->

                    <div style="display: none;" id="time-remaining-container">
                        <div id="button-container">
                            <img class="small-button" src="/wordpress/contents/themes/tutorstarter/system-test-toolkit/icon-small-button/guide2.png" height="30px" width="30px" id="guide-button"></img>
            
                            <img class="small-button" src="/wordpress/contents/themes/tutorstarter/system-test-toolkit/icon-small-button/draft2.png" height="30px" width="30px" id="draft-button"></img>
                     
                            <img class="small-button" src="/wordpress/contents/themes/tutorstarter/system-test-toolkit/icon-small-button/highlight2.png" height="30px" width="30px" id="highlight-button"></img>
                     
                          
                     
                            <img class="small-button" src="/wordpress/contents/themes/tutorstarter/system-test-toolkit/icon-small-button/translate2.png" height="30px" width="30px" id="translate-button"></img>
            
                            <!--<img class="small-button" onclick="openColorPopup()" src="icon-small-button/color.png" height="30px" width="30px" id="colors-button"></img> -->
                     
                            <img class="small-button" src="/wordpress/contents/themes/tutorstarter/system-test-toolkit/icon-small-button/checkbox2.png" height="30px" width="30px" id="checkbox-button"></img>
                     
                            <img id = "change-mode-button"class="small-button" onclick="DarkMode()" src="/wordpress/contents/themes/tutorstarter/system-test-toolkit/icon-small-button/dark-mode.png" height="30px" width="30px" ></img>
            
                          
                     
                     
                        </div>
                    
                
                
                
                
                        <div class = "fixedrightsmallbuttoncontainer">
                            <button  class ="buttonsidebar"  id="report-error"><img width="22px" height="22px" src="/wordpress/contents/themes/tutorstarter/system-test-toolkit/report.png" ></button><br>  
                               <button class ="buttonsidebar"  id="full-screen-button">⛶</button><br>
                                   <button class ="buttonsidebar" id="zoom-in">+</button><br>
                                   <button  class ="buttonsidebar"  id="zoom-out">-</button><br>
                                   <button  class ="buttonsidebar"  id="notesidebar"><img width="20px" height="20px" src="/wordpress/contents/themes/tutorstarter/system-test-toolkit/notesidebar.png" ></button><br>
                            
                                     
                            
                            
                            
                            </div>
                            
                            
                            <div class = "fixedleftsmallbuttoncontainer">
                            
                                       <button  class ="buttonsidebar" onClick="reloadTest()"><img width="25px"  src="/wordpress/contents/themes/tutorstarter/system-test-toolkit/reload.png" ></button><br>
                            
                                          <button  class ="buttonsidebar" id="print-exam-button" ><img width="28px" src="/wordpress/contents/themes/tutorstarter/system-test-toolkit/print.png" ></button><br>
                            
                                          <button id="change_appearance"  class ="buttonsidebar" ><img width="28px" src="/wordpress/contents/themes/tutorstarter/system-test-toolkit/setting.png" ></button><br>
                   
                                          <button id="quick-view"  class ="buttonsidebar" ><img width="28px" src="/wordpress/contents/themes/tutorstarter/system-test-toolkit/quick-view.png" ></button>
                   
                            
                                     
                            
                            
                            </div>
                
        <!--
                <div id="highlight-buttons">
                    <button id="red-button">Red</button>
                    <button id="green-button">Green</button>
                    <button id="remove-button">Remove</button>
                </div> -->
                
                    <!-- Color choice popup 
                <div id="color-popup" class="popup">
                    <div class="popup-content">
                        <h4>Đổi màu nền bài thi:</h4>
                        <div>
                            <input type="radio" id="white-color" name="color" value="white" checked>
                            <label for="white-color">Trắng (Mặc định)</label>
                        </div>
                        <div>
                            <input type="radio" id="black-color" name="color" value="DarkTurquoise">
                            <label for="black-color">Xanh nhạt</label>
                        </div>
                        <div>
                            <input type="radio" id="blue-color" name="color" value="LightSlateGrey">
                            <label for="blue-color">Xám</label>
                        </div>
                        <div>
                            <input type="radio" id="yellow-color" name="color" value="yellow">
                            <label for="yellow-color">Vàng</label>
                        </div>
                        <div>
                            <input type="radio" id="brown-color" name="color" value="LavenderBlush">
                            <label for="brown-color">Hồng nhạt</label>
                        </div>
                        <button onclick="applyColor()">Đổi màu</button>
                    </div>
                </div>-->

                
                
                
                
                        <div id="clock-block"> 
                                <h3 id="countdown"></h3>
                        </div>
                        <button class="button-10" id="submit-button">Nộp bài làm</button>

                
                
                        </div>
                
                
                    <div id="loading-popup" style="display: none;">
                        <div id="loading-spinner"></div>
                
                    </div>
                
                
                
                    <div id="loading-popup-remember" style="display: none;">
                        
                    </div>
                
                
                
                
                        <div id="translate-popup" class="popup-translate">
                            <div class="popup-content-translate">
                                <span class="close-translate" onclick="closeTranslatePopup()">&times;
                                </span>
                            <div id="google_translate_element"></div>
                            <p>Đang phát triển thêm ...</p>
                
                                
                            </div>
                        </div>
                
                
                
                
                <div id="checkbox-popup" class="popup-checkbox">
                <span class="close-checkbox" onclick="closeCheckboxPopup()">&times;</span>
                        <b style="color: rgb(248, 23, 23);">Bạn có thể chuyển câu hỏi nhanh bằng cách ấn vào các câu tương ứng</b>
                        <div id="checkboxes-container"></div>
                        
                        
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
                
                
                
                
                <div id="print-popup" class="popup">
                
                    
                
                        
                    <div class="popup-content">
                        <span class="close" onclick="closePrintpopup()">&times;</span>
                        <h2 style="text-align: center; color: red">Bạn có thể tải đề tại đây </h2>
                
                        <button onclick="printExam()">Tải đề thi </button>
                        <button onclick = "printAnswer()"> Tải đáp án + lời giải </button>
                        <button>Tải full đề + đáp án + lời giải  </button>
                    </div>
                        
                        
                </div>
                
                
                <!-- draft -->
                        <div id="draft-popup" class="popup">
                
                    <div class="popup-content">
                
                
                        <span class="close" onclick="closeDraftPopup()">&times;</span>
                        <h3 style="text-align: center;" > Ghi chú bài làm </h3>
                        <h5 style="text-align: center; color: red;" > Những ghi chú này sẽ không được lưu lại sau bài làm.</h5><h5 style="text-align: center; color: red;"> Để lưu lại ghi chú trong bài làm, vui lòng ấn vào icon "Note" ở sidebar bên phải ! </h5>
                        <textarea  class="draft-textarea" rows="4"  id="editor" placeholder="Bạn có thể ghi chú tại đây"></textarea>
                    </div>
                </div>
                
                
                <!-- note sidebar -->
                <div id="notesidebar-popup" class="popup">
                    <div class="popup-content">
                                
                        <div id="sidebar">
                            <span class="close" onclick="closeNoteSidebarPopup()">&times;</span>
                <ul>
                    <li onclick="changeContent(1)">Hướng dẫn</li>
                    <li onclick="changeContent(2)">Lưu chú thích</li>
                    <li onclick="changeContent(3)">Lưu công thức</li>
                    <li onclick="changeContent(4)">Lưu từ vựng</li>
                </ul>
                </div>
                
                <div id="content">
                <h2>Lưu công thức/ ghi chú/ từ vựng,... của bạn vào trang cá nhân</h2>
                <p>Các bạn chọn menu 1, menu 2, ,menu 3 rồi ấn save vào từng nút !.</p>
                </div>
                
                
                        
                    </div>
                </div>
                
                <!-- end note sidebar -->
                
                
               <!-- giấu form send kết quả bài thi -->


    
  
 <span id="message" ></span>
 <form id="frmContactUs" >
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

     

    <div class = "form-group"  >
         <input type="text" id="timedotest" name="timedotest" placeholder="Thời gian làm bài"  class="form-control form_data" />
         <span id="time_error" class="text-danger"></span>
    </div>

    <div class = "form-group" >
         <input type="text" id="idtest" name="idtest" placeholder="Id test"  class="form-control form_data" />
         <span id="idtest_error" class="text-danger" ></span>
    </div>

    <div class = "form-group" >
         <input type="text" id="idcategory" name="idcategory" placeholder="Id category"  class="form-control form_data" />
         <span id="idcategory_error" class="text-danger"></span>
    </div>

     <div class = "form-group"   >
         <input type="text"  id="testname" name="testname" placeholder="Test Name"  class="form-control form_data" />
         <span id="testname_error" class="text-danger"></span>
    </div>


    <div class = "form-group"   >
        <input type="text"  id="correctanswer" name="correctanswer" placeholder="Correct Answer"  class="form-control form_data" />
        <span id="correctanswer_error" class="text-danger"></span>  
    </div>


    <div class = "form-group"   >
        <input type="text"  id="useranswer" name="useranswer" placeholder="User Answer"  class="form-control form_data" />
        <span id="useranswer_error" class="text-danger"></span>
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
         
                

                <script>

let submitTest = false;
let pre_id_test_ = `<?php echo esc_html($custom_number);?>`;
console.log(`${pre_id_test_}`)
// function save data qua ajax
jQuery('#frmContactUs').submit(function(event) {
event.preventDefault(); // Prevent the default form submission

 var link = "<?php echo admin_url('admin-ajax.php'); ?>";

 var form = jQuery('#frmContactUs').serialize();
 var formData = new FormData();
 formData.append('action', 'contact_us');
 formData.append('contact_us', form);

 jQuery.ajax({
     url: link,
     data: formData,
     processData: false,
     contentType: false,
     type: 'post',
     success: function(result) {
         jQuery('#submit').attr('disabled', false);
         if (result.success == true) {
             jQuery('#frmContactUs')[0].reset();
         }
         jQuery('#result_msg').html('<span class="' + result.success + '">' + result.data + '</span>');
     }
 });
});

         //end


document.addEventListener("DOMContentLoaded", function () {
    document.addEventListener("submitForm", function () {
        setTimeout(function () {
            let form = document.getElementById("frmContactUs");
            form.submit(); // This should work now that there's no conflict
        }, 2000); 
    });
});
//end new adding

                
         

<?php echo wp_kses_post($additional_info);
 ?>

       
            
                        
                    let demsocau = 0;





                    for (let i = 1; i <= quizData.questions.length; i++)
                    {
                        demsocau ++
                    }
                    console.log("Hiện tại đang có", demsocau, "/",quizData.number_questions,"câu được khởi tạo" );
                        


                    countdownValue = quizData.duration;


            console.log("first question", quizData.questions[1].question)


                        document.getElementById('change_appearance').addEventListener('click', openChangeDisplayPopup);
              
        let currentQuestionIndex = 0;
        // save date (ngày làm bài cho user)
        const currentDate = new Date();

                    // Get day, month, and year
        const day = currentDate.getDate();
        const month = currentDate.getMonth() + 1; // Adding 1 because getMonth() returns zero-based month index
        const year = currentDate.getFullYear();

                    // Display the date
        const dateElement = document.getElementById('date');
        const dateElement2 = document.getElementById('date-table-result');
        dateElement.innerHTML = `${day}/${month}/${year}`;

        dateElement2.innerHTML = `${day}/${month}/${year}`;

        function setTimeLimit(value) {
            countdownValue = parseInt(value);
            document.getElementById('countdown').innerHTML = "<image src='/wordpress/contents/themes/tutorstarter/system-test-toolkit/time-remain.png' height='20px'width='20x' class='center'>" + secondsToHMS(countdownValue);
            }

        let DoingTest = false;
        
            
// New adding

function formatAWS(str) {
                    if (/^<p>.*<\/p>$/.test(str)) {
                        var htmlString = str;
                        var tempDiv = document.createElement('div');
                        tempDiv.innerHTML = htmlString;
                        var innerElement = tempDiv.firstChild;
                        var innerHtml = innerElement.innerHTML;
                        return innerHtml;
                    }
                    return str;
                    }

        // Close the draft popup when the close button is clicked
        function closeChangeDisplayPopup() {
            document.getElementById('change_appearance_popup').style.display = 'none';
        }

        function openChangeDisplayPopup() {
            document.getElementById('change_appearance_popup').style.display = 'block';
        
        }



        document.getElementById('quick-view').addEventListener('click', openQuickViewPopup);

        // Close the draft popup when the close button is clicked
        function closeQuickViewPopup() {
            document.getElementById('quick-view_popup').style.display = 'none';
        }

        function openQuickViewPopup() {
            document.getElementById('quick-view_popup').style.display = 'block';
        
        }

                    
        document.getElementById('change_appearane_1').addEventListener('click', function() {
            var elements = document.querySelectorAll('.question-side, .answer-side, .quiz-section');
            elements.forEach(function(element) {
                element.classList.remove('question-side', 'answer-side', 'quiz-section');
            });
            // Save the state to localStorage
            localStorage.setItem('appearanceChanged', 'true');
        });  

        // css cho questions nếu muốn block/none


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


        function showQuestion(index) {
            const questions = document.getElementsByClassName("questions");

            // giao diện 2
            document.getElementById('change_appearane_2').addEventListener('click', function() {
                for (let i = 0; i < questions.length; i++) {
                    questions[i].style.display = "block";
                }
            });





            for (let i = 0; i < questions.length; i++) {
                questions[i].style.display = "none";
            }
            var current_module_element = document.getElementById("current_module");

            var current_module_text = current_module_element.innerText;
            current_module_text = `  ${quizData.questions[index].question_category}`;
            current_module_element.innerText = current_module_text;


            questions[index].style.display = "block";

            document.getElementById("prev-button").style.display = index === 0 ? "none" : "inline-block";
            document.getElementById("next-button").style.display = index === questions.length - 1 ? "none" : "inline-block";
        }


        /*
        for (let i = 0; i < quizData.questions.length; i++) {  
                    console.log("Current question: ",i)   
                    currentModule = quizData.questions[6].question_category;
                } */
        function ChangeQuestion(questionNumber)
                {
                
                    console.log("Test change question by clicking checkbox"+ questionNumber );

                    if (currentQuestionIndex < quizData.questions.length - 1) {
                
                showQuestion(questionNumber-1);    }
                currentQuestionIndex = questionNumber-1;
            }

        // Button functions for testing
        function blue_highlight(spanId) {
                    document.getElementById(spanId).style.backgroundColor = 'blue';
                }
                
                function green_highlight(spanId) {
                    document.getElementById(spanId).style.backgroundColor = 'green';
                }
            
        function startTest() {
                    document.getElementById("change_appearance").style.display = "block";
                    document.getElementById("start-test").style.display = 'none';
                    document.getElementById("basic-info").style.display = 'none';
                    document.getElementById("quiz-container").style.display = 'block';
                    document.getElementById("submit-button").style.display = 'block';
                    var explain_zone = document.getElementsByClassName("explain-zone");
                    for (var i = 0; i < explain_zone.length; i++)
                        explain_zone[i].style.display = 'none';
                    document.getElementById("clock-block").style.display = 'block';
                    const image = document.createElement("img").src = "note.";

                    document.getElementById('countdown').innerHTML = "<image src='/contents/themes/tutorstarter/system-test-toolkit/time-remain.png' height='20px'width='20x'  class='center'>" + secondsToHMS(countdownValue);
                        startCountdown();
                    
                    document.getElementById("navigation-buttons").style.display = 'flex';

                    showQuestion(currentQuestionIndex);

        }

            

                </script>

        <!--<script type="text/javascript" src="function/alert_leave_page.js"></script> -->



        <script type="text/javascript" src="/wordpress/contents/themes/tutorstarter/system-test-toolkit/function/translate.js"></script>
        <script type="text/javascript" src="/wordpress/contents/themes/tutorstarter/system-test-toolkit/function/zoom-text.js"></script>

        <script type="text/javascript" src="/wordpress/contents/themes/tutorstarter/system-test-toolkit/function/toggle-time-remaining-container.js"></script>
        <script type="text/javascript" src="/wordpress/contents/themes/tutorstarter/system-test-toolkit/function/submit_answer_2.js"></script>
        <script type="text/javascript" src="/wordpress/contents/themes/tutorstarter/system-test-toolkit/function/report-error.js"></script>
        <script type="text/javascript" src="/wordpress/contents/themes/tutorstarter/system-test-toolkit/function/note-sidebar.js"></script>


        <script type="text/javascript" src="/wordpress/contents/themes/tutorstarter/system-test-toolkit/function/main3.js"></script>
        <script type="text/javascript" src="/wordpress/contents/themes/tutorstarter/system-test-toolkit/function/highlight-text.js"></script>
        <script type="text/javascript" src="/wordpress/contents/themes/tutorstarter/system-test-toolkit/function/fullscreen.js"></script>
        <script type="text/javascript" src="/wordpress/contents/themes/tutorstarter/system-test-toolkit/function/format_time_2.js"></script>
        <script type="text/javascript" src="/wordpress/contents/themes/tutorstarter/system-test-toolkit/function/draft-popup.js"></script>
        <script type="text/javascript" src="/wordpress/contents/themes/tutorstarter/system-test-toolkit/function/color-background.js"></script>
        <script type="text/javascript" src="/wordpress/contents/themes/tutorstarter/system-test-toolkit/function/choose_time.js"></script>
        <script type="text/javascript" src="/wordpress/contents/themes/tutorstarter/system-test-toolkit/function/checkbox_remember_2.js"></script>
        <script type="text/javascript" src="/wordpress/contents/themes/tutorstarter/system-test-toolkit/function/begining_loading_popup.js"></script>
        <!-- <script type="text/javascript" src="/contents/Addon-Test-System/function/quick-view-answer_v2_0.js"></script> -->

        <script type="text/javascript" src="/wordpress/contents/themes/tutorstarter/system-test-toolkit/function/reload-test.js"></script>
        <script type ="text/javascript" src="/wordpress/contents/themes/tutorstarter/system-test-toolkit/function/change-mode.js"></script> 
        <!--
        <script type ="text/javascript" src="/contents/Addon-Test-System/function/disable-view-inspect_v2_0.js"></script> -->

        <script type="text/javascript" src="/wordpress/contents/themes/tutorstarter/system-test-toolkit/function/print-exam.js"></script>
        <script src="https://www.google.com/recaptcha/api.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://smtpjs.com/v3/smtp.js">
        </script>


            </body>
        </html>


    
    <?php
    get_footer();
} else {
    get_header();
    echo '<p>Please log in to submit your answer.</p>';
    get_footer();
}