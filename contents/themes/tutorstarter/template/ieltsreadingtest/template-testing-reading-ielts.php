<?php
/*
 * Template Name: Doing Template Reading Test
 * Template Post Type: ieltsreadingtest
 
 */

 //get_header(); // Gọi phần đầu trang (header.php)

if (is_user_logged_in()) {
    $post_id = get_the_ID();
    $user_id = get_current_user_id();
    $additional_info = get_post_meta($post_id, '_ieltsreadingtest_additional_info', true);

    $post_id = get_the_ID();
    // Lấy giá trị custom number từ custom field
    $custom_number = get_post_meta($post_id, '_ieltsreadingtest_custom_number', true);
    
    // Thông tin kết nối database
    $servername = "localhost";
    $username = "root";
    $password = ""; // No password by default
    $dbname = "wordpress";
    
    // Tạo kết nối
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Kiểm tra kết nối
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
// Truy vấn `question_choose` từ bảng `ielts_reading_test_list` theo `id_test`
$sql_test = "SELECT question_choose FROM ielts_reading_test_list WHERE id_test = ?";
$stmt_test = $conn->prepare($sql_test);

if ($stmt_test === false) {
    die('Lỗi MySQL prepare: ' . $conn->error);
}

$stmt_test->bind_param("i", $custom_number);
$stmt_test->execute();
$result_test = $stmt_test->get_result();

if ($result_test->num_rows > 0) {
    // Lấy các ID từ question_choose (ví dụ: "1001,2001,3001")
    $row_test = $result_test->fetch_assoc();
    $question_choose = $row_test['question_choose'];
    $id_parts = explode(",", $question_choose); // Chuyển thành mảng ID

    $part = []; // Mảng chứa dữ liệu của các phần

    // Lặp qua từng id_part và truy vấn bảng tương ứng
    foreach ($id_parts as $index => $id_part) {
        // Xác định bảng và câu lệnh SQL tương ứng dựa trên index của part
        switch ($index) {
            case 0:
                $sql_part = "SELECT part, duration, number_question_of_this_part, paragraph, group_question, category 
                             FROM ielts_reading_part_1_question WHERE id_part = ?";
                break;
            case 1:
                $sql_part = "SELECT part, duration, number_question_of_this_part, paragraph, group_question, category 
                             FROM ielts_reading_part_2_question WHERE id_part = ?";
                break;
            case 2:
                $sql_part = "SELECT part, duration, number_question_of_this_part, paragraph, group_question, category 
                             FROM ielts_reading_part_3_question WHERE id_part = ?";
                break;
            default:
                continue 2; // Nếu có nhiều hơn 3 phần, bỏ qua
        }

        // Chuẩn bị và thực thi câu lệnh SQL cho từng phần
        $stmt_part = $conn->prepare($sql_part);
        if ($stmt_part === false) {
            die('Lỗi MySQL prepare: ' . $conn->error);
        }

        $stmt_part->bind_param("i", $id_part);
        $stmt_part->execute();
        $result_part = $stmt_part->get_result();

        // Nếu có kết quả, thêm vào mảng part
        while ($row = $result_part->fetch_assoc()) {
            $entry = [
                'part_number' => $row['part'],
                'paragraph' => $row['paragraph'],
                'number_question_of_this_part' => $row['number_question_of_this_part'],
                'duration' => $row['duration'],
                'category' => $row['category'],
                'group_question' => $row['group_question']
            ];

            if (!empty($row['group_question'])) {
                $decoded = json_decode($row['group_question'], true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $entry['group_question'] = $decoded;
                } else {
                    // Log the error and set group_question to null
                    error_log('JSON decode error: ' . json_last_error_msg());
                    $entry['group_question'] = null;
                }
            } else {
                $entry['group_question'] = null;
            }
            

            // Thêm phần vào mảng part
            $part[] = $entry;
        }
    }

    // Xuất mảng quizData dưới dạng JavaScript
    echo '<script type="text/javascript">
    const quizData = {
        part: ' . json_encode($part, JSON_UNESCAPED_SLASHES) . '
    };

    console.log(quizData);
    </script>';
} else {
    echo '<script type="text/javascript">console.error("Không tìm thấy test với custom number: ' . $custom_number . '");</script>';
}

// Đóng kết nối
$conn->close();

?>
    



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ielts Reading Computer </title>
    <style>
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    font-size: 18px;

}


.content-left {
    width: 50%;
    padding: 20px;
    border-right: 1px solid #ccc;
}

.content-right {
    width: 50%;
    padding: 20px;
}

.question {
    margin-bottom: 20px;
}

.pagination-container {
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 20px 0;
}

.pagination-container button {
    margin: 0 10px;
    padding: 10px 20px;
    cursor: pointer;
}

#questions-container {
    padding: 20px;
}

#questions-container label {
    display: block;
    margin: 20px 0;
}

.answer-input {
    width: 250px;
    padding: 8px;
    margin: 5px 0;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

/* Fixed header for question range */
.fixed-above {
    background-color: #f8f9fa; /* Màu nền cho header */
    padding: 10px 20px; /* Khoảng cách bên trong */
    display: flex;
    justify-content: space-between; /* Căn giữa cho các phần tử */
    align-items: center; /* Căn giữa theo chiều dọc */
    position: fixed; /* Để header luôn ở phía trên */
    width: 100%; /* Chiều rộng đầy đủ */
    top: 0; /* Đặt vị trí ở trên cùng */
    z-index: 1000; /* Đảm bảo header nằm trên các phần tử khác */
}
.header-content {
    display: flex;
    flex-direction: column; /* Đặt hướng dọc để timer nằm bên dưới ID */
    align-items: flex-start; /* Căn trái cho cả hai phần tử */
}

.test-taker-id {
    font-weight: bold;
}


.question-range {
    background-color: #e9ecef; /* Màu xám cho phần câu hỏi */
    padding: 14px; /* Khoảng cách bên trong */
    margin-top: 30px; /* Tăng giá trị để tránh che khuất bởi header */
    width: 97%; /* Không chiếm chiều rộng đầy đủ */
    margin: 0 auto; /* Căn giữa */
}


#content {
    display: none;
    padding-top: 70px; /* Thêm padding trên cùng để tránh che khuất bởi header */
    width: 100%;
}
#header{
    height:40px
}



/* Container that includes the quiz content */
.quiz-container {
   /* margin-top: 60px; /* Adjust to fit below the fixed header */
    margin-bottom: 30px; /* Adjust to fit above the fixed footer */
    margin-top: 10px; /* Adjust to fit above the fixed footer */

    display: flex;
    height: calc(100vh - 210px); /* Make sure the container fits within the viewport */
    overflow: hidden; /* Prevent content from overflowing the container */
    width: 100%;
}

/* Left side scrollable content (paragraphs) */
.content-left {
    width: 50%;
    padding: 20px;
    overflow-y: auto; /* Make it scrollable */
    border-right: 1px solid #ccc;
}

/* Right side scrollable content (questions) */
.content-right {
    width: 50%;
    padding: 20px;
    overflow-y: auto; /* Make it scrollable */
}

/* Paragraph container styling (optional, adjust based on content) */
#paragraph-container p {
    margin-bottom: 20px;
}

/* Questions container styling (optional, adjust based on content) */
#questions-container .question {
    margin-bottom: 25px;
}

#question-nav {
  display: inline-block;
  margin: 0 auto;
}

#question-nav span {
  cursor: pointer;
  padding: 10px;
  margin-right: 5px;
  background-color: #fff;
  border: 1px solid #ccc;
  display: inline-block;
  text-align: center;
}

#question-nav span:hover {
  background-color: #ddd;
}
/* HTML: <div class="loader"></div> */
.loader {
  width: 70px;
  aspect-ratio: 1;
  border-radius: 50%;
  border: 8px solid #514b82;
  animation: l20-1 0.8s infinite linear alternate, l20-2 1.6s infinite linear;
}

#test-prepare {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    position: fixed; /* Giữ loader cố định giữa màn hình */
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%); /* Căn giữa theo cả chiều ngang và dọc */
    height: 200px;
    z-index: 1001; /* Đảm bảo loader ở trên các phần tử khác */
}


@keyframes l20-1{
   0%    {clip-path: polygon(50% 50%,0       0,  50%   0%,  50%    0%, 50%    0%, 50%    0%, 50%    0% )}
   12.5% {clip-path: polygon(50% 50%,0       0,  50%   0%,  100%   0%, 100%   0%, 100%   0%, 100%   0% )}
   25%   {clip-path: polygon(50% 50%,0       0,  50%   0%,  100%   0%, 100% 100%, 100% 100%, 100% 100% )}
   50%   {clip-path: polygon(50% 50%,0       0,  50%   0%,  100%   0%, 100% 100%, 50%  100%, 0%   100% )}
   62.5% {clip-path: polygon(50% 50%,100%    0, 100%   0%,  100%   0%, 100% 100%, 50%  100%, 0%   100% )}
   75%   {clip-path: polygon(50% 50%,100% 100%, 100% 100%,  100% 100%, 100% 100%, 50%  100%, 0%   100% )}
   100%  {clip-path: polygon(50% 50%,50%  100%,  50% 100%,   50% 100%,  50% 100%, 50%  100%, 0%   100% )}
}
@keyframes l20-2{ 
  0%    {transform:scaleY(1)  rotate(0deg)}
  49.99%{transform:scaleY(1)  rotate(135deg)}
  50%   {transform:scaleY(-1) rotate(0deg)}
  100%  {transform:scaleY(-1) rotate(-135deg)}
}
/* Fixed bottom navigation for questions */
.fixed-bottom {
    position: fixed;
    bottom: 0;
    width: 100%;
    background-color: #f1f1f1;
    border-top: 1px solid #ccc;
    text-align: center;
    z-index: 1000;
}

#part-navigation {
    display: flex;
    justify-content: space-between; /* Ensure even spacing */
    width: 100%;
    align-items: center; /* Align buttons vertically */
}

#part-navigation-button, #submit-btn {
    flex-grow: 1; /* Share remaining width equally for parts */
    padding: 10px;
    margin: 5px;
    background-color: #f1f1f1;
    border: 1px solid #ccc;
    cursor: pointer;
    border-radius: 5px;
    text-align: center;
    height: 40px; /* Fixed height for consistency */
    display: flex;
    justify-content: center;
    align-items: center;
}

#submit-btn {
    width: 150px; /* Fixed width for Submit button */
    flex-grow: 0; /* Prevent it from expanding */
}


#part-navigation button.active {
    background-color: #0073e6;
    color: white;
    border: 1px solid #0073e6;
}

    </style>
</head>
<script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>

<body onload="main()">
    <div id = "test-prepare">
        <div class="loader"></div>
        <h3>Your test will begin shortly</h3>
        <div style="display: none;" id="date" style="visibility:hidden;"></div>
        <div style="display: none;" id="title" style="visibility:hidden;"><?php the_title(); ?></div>
        <div  style="display: none;"  id="id_test"  style="visibility:hidden;"><?php echo esc_html($custom_number);?></div>

    </div>


    <div id="header" class="fixed-above">
        <div class="header-content">
            
            <div class="test-taker-id">Test taker ID</div>
            <span id="timer" class="timer" style="font-weight: bold"></span>
        </div>
    </div>
    
    
    <div id="content" style="display: none;">
            <div id="question-range-of-part" class="question-range"></div>
    
        <div class="quiz-container">
            

            <div class="content-left">

                <div id="paragraph-container">
                    <!-- Paragraph will be loaded dynamically -->
                </div>
            </div>
            <div class="content-right">
                <div id="questions-container">
                    <!-- Questions will be loaded dynamically -->
                </div>

             <div class="pagination-container">
                    <button id="prev-btn" style="display: none;" >Previous</button>
                    
                    <button id="next-btn" style="display: none;" >Next</button>
                    <h5  id="time-result"></h5>

                    <h5 id ="useranswerdiv"></h5>
                     <!-- giấu form send kết quả bài thi -->


    
  
     <span id="message" style="display:none" ></span>
     <form id="saveReadingResult" >
                <div class="card">
                    <div class="card-header">Form lưu kết quả</div>
                    <div class="card-body" >
        
                <div class = "form-group" >
                    <input   type="text" id="overallband" name="overallband" placeholder="Kết quả"  class="form-control form_data" />
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
                    <input type="text"  id="useranswer" name="useranswer" placeholder="User Answer"  class="form-control form_data" />
                    <span id="useranswer_error" class="text-danger"></span>
            </div>
            
            <div class = "form-group"   >
                    <input type="text"  id="correct_percentage" name="correct_percentage" placeholder="Correct percentage"  class="form-control form_data" />
                    <span id="correctanswer_error" class="text-danger"></span>  
                </div>
          

            <div class = "form-group"   >
                    <input type="text"  id="total_question_number" name="total_question_number" placeholder="Total Number"  class="form-control form_data" />
                    <span id="total_question_number_error" class="text-danger"></span>  
                </div>
           

            <div class = "form-group"   >
                    <input type="text"  id="correct_number" name="correct_number" placeholder="Correct Number"  class="form-control form_data" />
                    <span id="correctanswer_error" class="text-danger"></span>  
                </div>
            
            <div class = "form-group"   >
                    <input type="text"  id="incorrect_number" name="incorrect_number" placeholder="Incorrect Number"  class="form-control form_data" />
                    <span id="incorrect_number_error" class="text-danger"></span>  
                </div>
        

            <div class = "form-group"   >
                    <input type="text"  id="skip_number" name="skip_number" placeholder="Skip Number"  class="form-control form_data" />
                    <span id="skip_number_error" class="text-danger"></span>  
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
                <div id="results-container"></div>
            </div>       

        </div>
        <div id="question-nav-container" class="fixed-bottom">
            <!-- <span id="part-label"></span>
            <div id="question-nav"></div> -->
            <div id="part-navigation">
            </div>

        </div>
    </div>

    <script src="/wordpress/contents/themes/tutorstarter/ielts-reading-tookit/script.js"></script>
</body>
<script>
    // function save data qua ajax
jQuery('#saveReadingResult').submit(function(event) {
    event.preventDefault(); // Prevent the default form submission
    
     var link = "<?php echo admin_url('admin-ajax.php'); ?>";
    
     var form = jQuery('#saveReadingResult').serialize();
     var formData = new FormData();
     formData.append('action', 'save_user_result_ielts_reading');
     formData.append('save_user_result_ielts_reading', form);
    
     jQuery.ajax({
         url: link,
         data: formData,
         processData: false,
         contentType: false,
         type: 'post',
         success: function(result) {
             jQuery('#submit').attr('disabled', false);
             if (result.success == true) {
                 jQuery('#saveReadingResult')[0].reset();
             }
             jQuery('#result_msg').html('<span class="' + result.success + '">' + result.data + '</span>');
         }
     });
    });
    
             //end
    

document.addEventListener("DOMContentLoaded", function () {
    document.addEventListener("submitForm", function () {
        setTimeout(function () {
            let form = document.getElementById("saveReadingResult");
            form.submit(); // This should work now that there's no conflict
        }, 2000); 
    });
});


//end new adding

</script>
</html>

<?php
} else {
    get_header();
    echo '<p>Please log in start reading test.</p>';
    get_footer();
}