<?php
/*
 * Template Name: Doing Template Speaking
 * Template Post Type: ieltsreadingtest
 
 */

 //get_header(); // Gọi phần đầu trang (header.php)

if (is_user_logged_in()) {
    $post_id = get_the_ID();
    $user_id = get_current_user_id();
    $additional_info = get_post_meta($post_id, '_ieltsreadingtest_additional_info', true);

   
$post_id = get_the_ID();
// Get the custom number field value
$custom_number = get_post_meta($post_id, '_ieltsreadingtest_custom_number', true);

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
/*
$sql = "SELECT part, duration, number_question_of_this_part, paragraph, group_question, category FROM ielts_reading_part_1_question WHERE id_test = ?";
$sql2 = "SELECT part, duration, number_question_of_this_part, paragraph, group_question, category FROM ielts_reading_part_2_question WHERE id_test = ?";
$sql3 = "SELECT part, duration, number_question_of_this_part, paragraph, group_question, category FROM ielts_reading_part_3_question WHERE id_test = ?";


$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $custom_number); // 'i' is used for integer
$stmt->execute();
$result = $stmt->get_result();

$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("i", $custom_number); // 'i' is used for integer
$stmt2->execute();
$result2 = $stmt2->get_result();

$stmt3 = $conn->prepare($sql3);
$stmt3->bind_param("i", $custom_number); // 'i' is used for integer
$stmt3->execute();
$result3 = $stmt3->get_result();


$part = []; // Initialize the array for storing questions

while ($row = $result->fetch_assoc()) {
    // Create an associative array for each row
    $entry = [
        'part_number' => $row['part'],
        'paragraph' => $row['paragraph'],
        'number_question_of_this_part' => $row['number_question_of_this_part'],
        'duration' => $row['duration'],
    ];

    // Kiểm tra nếu group_question là chuỗi JSON hợp lệ, chuyển thành mảng hoặc đối tượng PHP
    if (!empty($row['group_question'])) {
        $entry['group_question'] = json_decode($row['group_question'], true); // Chuyển từ JSON string thành array
    } else {
        $entry['group_question'] = null; // Đặt là null nếu không có dữ liệu
    }

    // Add the entry to the $part array
    $part[] = $entry;
}




while ($row = $result2->fetch_assoc()) {
    // Create an associative array for each row
    $entry = [
        'part_number' => $row['part'],
        'paragraph' => $row['paragraph'],
        'number_question_of_this_part' => $row['number_question_of_this_part'],
        'duration' => $row['duration'],
    ];

    // Kiểm tra nếu group_question là chuỗi JSON hợp lệ, chuyển thành mảng hoặc đối tượng PHP
    if (!empty($row['group_question'])) {
        $entry['group_question'] = json_decode($row['group_question'], true); // Chuyển từ JSON string thành array
    } else {
        $entry['group_question'] = null; // Đặt là null nếu không có dữ liệu
    }

    // Add the entry to the $part array
    $part[] = $entry;
}




while ($row = $result3->fetch_assoc()) {
    // Create an associative array for each row
    $entry = [
        'part_number' => $row['part'],
        'paragraph' => $row['paragraph'],
        'number_question_of_this_part' => $row['number_question_of_this_part'],
        'duration' => $row['duration'],
    ];

    // Kiểm tra nếu group_question là chuỗi JSON hợp lệ, chuyển thành mảng hoặc đối tượng PHP
    if (!empty($row['group_question'])) {
        $entry['group_question'] = json_decode($row['group_question'], true); // Chuyển từ JSON string thành array
    } else {
        $entry['group_question'] = null; // Đặt là null nếu không có dữ liệu
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

*/

// Đóng kết nối
$conn->close();

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Science Speech 1</title>
    <link rel="stylesheet" href="\wordpress\contents\themes\tutorstarter\dictation-exercise-toolkit\style.css">
    <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  </head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    
<body>
    <div class="header">
        <a class="logo">Onluyen247.net</a>
        <div class="header-right">
          <a  href="#home">Home</a>
          
        </div>
      </div>

      <div class="container">
    <div id = "before-content">
        <div class = "bf-content-setting-class" id = "bf-content-setting">
            

        </div>
    </div>
    <div id="content">
        <div id = 'left-side' class="left-side">
        <div id ="intro" style="text-align: center;">
            <h3>Dictation 1: 10 things you didn't know about orgasm</h3>
            <p>This speech/ conversation has been collected from TED TALK</p>
            <p id = 'number-sentences'>Number of sentence: </p>
            <p>Guidelines: </p>
            <button onclick ="getStart()" class="button-1">Start Now</button>
        </div>
        

    <div id ="start-dictation" style="display: none;">
        <p id="main-paragraph">Alright. I'm going to show you a couple of images from a very diverting paper in The Journal of Ultrasound in Medicine. I'm going to go way out on a limb and say that it is the most diverting paper ever published in The Journal of Ultrasound in Medicine. The title is "Observations of In-Utero Masturbation."Okay. Now on the left you can see the hand that's the big arrow and the penis on the right. The hand hovering. And over here we have, in the words of radiologist Israel Meisner, "The hand grasping the penis in a fashion resembling masturbation movements." Bear in mind this was an ultrasound, so it would have been moving images.Orgasm is a reflex of the autonomic nervous system. Now, this is the part of the nervous system that deals with the things that we don't consciously control, like digestion, heart rate and sexual arousal. And the orgasm reflex can be triggered by a surprisingly broad range of input. Genital stimulation. Duh. But also, Kinsey interviewed a woman who could be brought to orgasm by having someone stroke her eyebrow. People with spinal cord injuries, like paraplegias, quadriplegias, will often develop a very, very sensitive area right above the level of their injury, wherever that is. There is such a thing as a knee orgasm in the literature.I think the most curious one that I came across was a case report of a woman who had an orgasm every time she brushed her teeth.Something in the complex sensory-motor action of brushing her teeth was triggering orgasm. And she went to a neurologist, who was fascinated. He checked to see if it was something in the toothpaste, but no -- it happened with any brand. They stimulated her gums with a toothpick, to see if that was doing it. No. It was the whole, you know, motion. And the amazing thing to me is that you would think this woman would have excellent oral hygiene.Sadly -- this is what it said in the journal paper -- "She believed that she was possessed by demons and switched to mouthwash for her oral care." It's so sad.When I was working on the book, I interviewed a woman who can think herself to orgasm. She was part of a study at Rutgers University. You've got to love that. Rutgers. So I interviewed her in Oakland, in a sushi restaurant. And I said, "So, could you do it right here?" And she said, "Yeah, but you know I'd rather finish my meal if you don't mind."But afterwards, she was kind enough to demonstrate on a bench outside. It was remarkable. It took about one minute. And I said to her, "Are you just doing this all the time?"She said, "No. Honestly, when I get home, I'm usually too tired." She said that the last time she had done it was on the Disneyland tram.
</p>
        <div id="navigation-buttons">
            <button id="previous" onclick="navigate('prev')">Previous</button>
            <button id="next" onclick="navigate('next')">Next</button>
        </div>
    </div>
</div>
<div id = "right-side" class = "right-side" >

    <div id ="about-speech">
         </div>
</div>

</div>

<div id ="ads" style="display:  flex;justify-content: center; align-items: center;">
    DEV TAG: Powered by Nguyen Minh Long
</div>
</div>
<script src="\wordpress\contents\themes\tutorstarter\dictation-exercise-toolkit\js/get-user-country-api.js"></script>
<script src="\wordpress\contents\themes\tutorstarter\dictation-exercise-toolkit\js/countries.js"></script>
<script src="\wordpress\contents\themes\tutorstarter\dictation-exercise-toolkit\js/script-translate.js"></script>    
<script src="\wordpress\contents\themes\tutorstarter\dictation-exercise-toolkit\script.js"></script>
  
    
</body>
</html>


<?php
} else {
    get_header();
    echo '<p>Please log in start reading test.</p>';
    get_footer();
}