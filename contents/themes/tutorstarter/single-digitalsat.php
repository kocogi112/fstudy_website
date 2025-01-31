

<?php
//$post_id = get_the_ID();
$user_id = get_current_user_id();
// Get the custom number field value
$custom_number =intval(get_query_var('id_test'));
//$commentcount = get_comments_number( $post->ID );
global $wpdb;
$id_test = $custom_number;
$site_url = get_site_url();

// Prepare the SQL statement
$test_info = $wpdb->get_row($wpdb->prepare(
    "SELECT testname, time, test_type, token_need, role_access, permissive_management, question_choose, tag, number_question, book 
    FROM digital_sat_test_list 
    WHERE id_test = %d", 
    $id_test
));

// Set testname and default the time to 40 minutes
$testname = $test_info ? $test_info->testname : '';
$time = $test_info ? $test_info->time : '';
$number_question = $test_info ? $test_info->number_question : '';
$book = $test_info ? $test_info->book : '';
$test_type = $test_info ? $test_info->test_type : '';

$token_need = $test_info ? $test_info->token_need : '';
$role_access = $test_info ? $test_info->role_access : '';
$permissive_management = $test_info ? $test_info->permissive_management : '';


// Add filter for document title
add_filter('document_title_parts', function ($title) use ($testname) {
    $title['title'] = $testname; // Use the $testname variable from the outer scope
    return $title;
});

get_header(); // Gọi phần đầu trang (header.php)




if (is_user_logged_in()) {
    global $wpdb;

    // Get current user's username
    $current_user = wp_get_current_user();
    $current_username = $current_user->user_login;

    // Get results for the current user and specific idtest (custom_number)
    $results_query = $wpdb->prepare("
        SELECT * FROM save_user_result_digital_sat 
        WHERE username = %s 
        AND idtest = %d
        ORDER BY dateform DESC",
        $current_username,
        $custom_number
    );
    $results = $wpdb->get_results($results_query); ?>
     <style>
            * {
            box-sizing: border-box;
            }

            /* Create two equal columns that floats next to each other */
            .column {
            float: left;
            width: 50%;
            padding: 10px;
            }

            /* Clear floats after the columns */
            .row:after {
            content: "";
            display: table;
            clear: both;
            }

            /* Additional styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        .container-info-test {
            max-width: 100%;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 24px;
            color: #333;
        }

        .check-icon {
            color: green;
        }

        .test-info, .results, .practice-options {
            margin-bottom: 20px;
        }

        .note {
            color: red;
            font-weight: bold;
        }
        html {
            scroll-behavior: smooth;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .options-header {
            display: flex;
            margin-bottom: 20px;
            font-size: 15px;
        }

        .option {
            cursor: pointer;
            color: #007bff;
            text-decoration: underline;
            padding: 5px 10px;
        }

        .option:hover {
            text-decoration: none;
        }

        .active {
            background-color: #007bff; /* Highlight color */
            color: white; /* Text color for active state */
            border-radius: 5px;
        }

        .checkbox-group {
            margin-bottom: 20px;
        }
        .checkbox-group label {
            display: block;
            margin: 5px 0;
        }
        .btn-submit {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
        }

        .btn-submit:hover {
            background-color: #0056b3;
        }
        .h2-test{
            font-weight: bold;
            font-size: 25px;
        }
        .alert{
            margin-bottom: 0;
            position: relative;
            padding: .75rem 1.25rem;
            border: 1px solid transparent;
            border-radius: .35rem;
        }
        .alert-success{
            color: 1f5e39;
            background-color: #d8f0e2;
            border-color: #c8ead6;
        }
    </style>


   


           <div class="container-info-test">
           <h1><?php echo esc_html($testname); ?> <span class="check-icon">✔️</span></h1>
           
        <div class="test-info">
            <p><strong>Thời gian làm bài:</strong> <?php echo esc_html($time); ?> phút |  <?php echo esc_html($number_question); ?> câu </p>
            <p><strong>Resource:</strong> <?php echo esc_html($book); ?>  </p>
            <p><strong>Loại đề:</strong> <?php echo esc_html($test_type); ?>  </p>

            <p>203832 người đã luyện tập đề thi này</p>
            <p class="note">Chú ý: Đối với Loại đề Practice - Digital SAT, hệ thống sẽ hiển thị kết quả làm bài là số đáp án đúng trên tổng số câu (VD: 20/23) Để hiển thị kết quả như 1 bài test thật (Thang 1600), vui lòng chọn Loại đề Full Test. Tổng hợp đề Full Test tại đây</p>
        </div>


        <?php
       
        if ($results) {
            // Start the results table before the loop
            ?>
            <div class="results">
                <p class="h2-test">Kết quả làm bài của bạn:</p>
                <table class="table table-striped" style="border: 1px solid #ddd; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th>Ngày làm</th>
                            <th>Đề thi</th>
                            <th>Kết quả</th>
                            <th>Thời gian làm bài</th>
                            <th>Chi tiết bài làm</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($results as $result) {
                        // Display each result as a new row in the same table
                        ?>
                        <tr>
                            <td><?php echo esc_html($result->dateform); ?></td>
                            <td><?php echo esc_html($result->testname); ?></td>
                            <td><?php echo esc_html($result->resulttest); ?></td>
                            <td><?php echo esc_html($result->timedotest); ?></td>
                            <td>
                             

                                <a href="<?php echo $site_url?>/digitalsat/result/<?php echo esc_html($result->testsavenumber); ?>">
                                    Xem bài làm
                                </a>
                            </td>

                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            <?php
        } else {
            echo '<p>Bạn chưa làm test này.</p>';
        }
        ?>
        
            


        <div class="practice-options">
    <p class="options-header">
        <span class="option active" id="full-test">Làm full test</span>  
        <span class="option" id="practice">Luyện tập</span> 
        <span class="option" id="discussion">Thảo luận</span>
    </p>

    <div id="tips-container">
        <div id="full-test-content">
            <div class="alert alert-success" role="alert">
                <h4 class="alert-heading">Pro tips:</h4> <hr>
                <p>Sẵn sàng để bắt đầu làm full test? Để đạt được kết quả tốt nhất, bạn cần dành ra 40 phút cho bài test này.</p>
            </div><br>
            <a id="start-test-btn"  class="btn-submit" href="<?php echo $site_url?>/digitalsat/<?php echo $custom_number?>/start/">Bắt đầu bài thi</a>
        </div>
        <div id="practice-content" style="display: none;">
            <div class="alert alert-success" role="alert">
                <h4 class="alert-heading">Pro tips:</h4> <hr>
                <p>Hình thức luyện tập từng phần và chọn mức thời gian phù hợp sẽ giúp bạn tập trung vào giải đúng các câu hỏi thay vì phải chịu áp lực hoàn thành bài thi.</p>    
            </div><br>

            <p class="h2-test">Giới hạn thời gian (Để trống để làm bài không giới hạn):</p>
            <form id="practice-form" action="<?php echo $site_url?>/digitalsat/<?php echo $custom_number?>/start/" method="get">
                <label style="font-size: 18px;" for="timer"></label>

                <select id="timer" name="option">
                    <option value="1000000">Unlimited time</option>
                    <option value="60">1 minutes</option>
                    <option value="1800">30 minutes</option>
                    <option value="2700">45 minutes</option>
                    <option value="3600">60 minutes</option>
                    <option value="4500">75 minutes</option>
                    <option value="5400">90 minutes</option>
                    <option value="6300">105 minutes</option>
                    <option value="7200">120 minutes</option>
                    <option value="9000">150 minutes</option>
                    <option value="10800">180 minutes</option>
                </select><br><br>      
                <button class="btn-submit" type="submit" value="Start test">Luyện tập</button>
            </form>
        </div>
    </div>
</div>



            
<?php if ( comments_open() || get_comments_number() ) :
    comments_template();
endif; ?>
            

<script>
 document.getElementById('practice').addEventListener('click', function() {
    // Show practice content
    document.getElementById('practice-content').style.display = 'block';
    document.getElementById('full-test-content').style.display = 'none';

    // Set active state
    setActiveOption('practice');
});

document.getElementById('full-test').addEventListener('click', function() {
    // Show full test content
    document.getElementById('practice-content').style.display = 'none';
    document.getElementById('full-test-content').style.display = 'block';

    // Set active state
    setActiveOption('full-test');
});

// Event listener for the discussion tab to redirect to #comment
document.getElementById('discussion').addEventListener('click', function() {
    window.location.href = '#comment';  // Redirect to #comment
});

function setActiveOption(optionId) {
    const options = document.querySelectorAll('.option');
    options.forEach(option => {
        if (option.id === optionId) {
            option.classList.add('active');
        } else {
            option.classList.remove('active');
        }
    });
}

// Initial state: Show full test content and highlight the full test button
document.getElementById('full-test-content').style.display = 'block';
document.getElementById('practice-content').style.display = 'none';
setActiveOption('full-test');


// Get the elements
const discussionTab = document.getElementById('discussion');

// Event listener for the discussion tab to redirect to #comment
discussionTab.addEventListener('click', function() {
    window.location.href = '#comment';  // Redirect to #comment
});


function setActiveOption(optionId) {
    const options = document.querySelectorAll('.option');
    options.forEach(option => {
        if (option.id === optionId) {
            option.classList.add('active');
        } else {
            option.classList.remove('active');
        }
    });
}

function resetActiveOptions() {
    const options = document.querySelectorAll('.option');
    options.forEach(option => {
        option.classList.remove('active');
    });
}

// Initial state: Show practice content and highlight the practice button
setActiveOption('full-test');


</script>



    <?php
        

    
} else {
    echo '<p>Vui lòng đăng nhập để làm test.</p>';
}



get_footer(); // Gọi phần cuối trang (footer.php)
?>