<?php
/*
 * Template Name: Doing Template Speaking
 * Template Post Type: dictationexercise
 
 */

 //get_header(); // Gọi phần đầu trang (header.php)

if (is_user_logged_in()) {
    $post_id = get_the_ID();
    $user_id = get_current_user_id();
    $additional_info = get_post_meta($post_id, '_dictationexercise_additional_info', true);

   
$post_id = get_the_ID();
// Get the custom number field value
$custom_number = get_post_meta($post_id, '_dictationexercise_custom_number', true);

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

$sql = "SELECT type_test, testname, id_video, transcript FROM dictation_question WHERE id_test = ?";


$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $custom_number); // 'i' is used for integer
$stmt->execute();
$result = $stmt->get_result();


if ($row = $result->fetch_assoc()) {
    $id_video = $row['id_video'];
    $transcript = $row['transcript'];
    $type_test = $row['type_test'];
    $testname = $row['testname'];
} else {
    $transcript = "No content available.";
}

echo '
<script>
const rawTranscript = [
' . $transcript . '
];
console.log(rawTranscript);
</script>
';


// Đóng kết nối
$conn->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dictation Exercise</title>
    <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  </head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        #transcript {
            font-size: 18px;
            margin-top: 20px;
        }
        .controls {
            margin-top: 20px;
        }
        /* Import Google Font - Poppins */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
*{
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Poppins', sans-serif;
} */
body {
    
    top: 0px !important; 
    margin: 0;


 font-family: sf pro text, -apple-system, BlinkMacSystemFont, Roboto,
     segoe ui, Helvetica, Arial, sans-serif, apple color emoji,
     segoe ui emoji, segoe ui symbol;
     text-align: center;
     justify-content: center;
}


.container{
  width: 70%;
    height: 600px;
    display: contents;
    align-items: center;
}

#pronunciation-div, #translate-div, #speaking-check-div{
  display: none;
}
#tips, #refreshButton, #important-warning {
  display: inline-block;
  vertical-align: middle;
}

#warning{
  background-color: rgba(188, 204, 151, 0.726);
}

#refreshButton {
  cursor: pointer;
  margin-left: 10px; /* Optional: Add some spacing between the div and the button */
}



/* CSS */
.button-1 {
  appearance: none;
  background-color: #2ea44f;
  border: 1px solid rgba(27, 31, 35, .15);
  border-radius: 6px;
  box-shadow: rgba(27, 31, 35, .1) 0 1px 0;
  box-sizing: border-box;
  font-size: 16px;
  color: #fff;
  cursor: pointer;
  display: inline-block;
  font-family: -apple-system,system-ui,"Segoe UI",Helvetica,Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji";
  font-weight: 600;
  line-height: 20px;
  padding: 6px 16px;
  position: relative;
  text-align: center;
  text-decoration: none;
  user-select: none;
  -webkit-user-select: none;
  touch-action: manipulation;
  vertical-align: middle;
  white-space: nowrap;
}

.button-1:focus:not(:focus-visible):not(.focus-visible) {
  box-shadow: none;
  outline: none;
}

.button-1:hover {
  background-color: #2c974b;
}

.button-1:focus {
  box-shadow: rgba(46, 164, 79, .4) 0 0 0 3px;
  outline: none;
}

.button-1:disabled {
  background-color: #94d3a2;
  border-color: rgba(27, 31, 35, .1);
  color: rgba(255, 255, 255, .8);
  cursor: default;
}

.button-1:active {
  background-color: #298e46;
  box-shadow: rgba(20, 70, 32, .2) 0 1px 0 inset;
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


/* CSS */
.button-4 {
  appearance: none;
  background-color: #FAFBFC;
  border: 1px solid rgba(27, 31, 35, 0.15);
  border-radius: 6px;
  box-shadow: rgba(27, 31, 35, 0.04) 0 1px 0, rgba(255, 255, 255, 0.25) 0 1px 0 inset;
  box-sizing: border-box;
  color: #24292E;
  cursor: pointer;
  display: inline-block;
  font-family: -apple-system, system-ui, "Segoe UI", Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji";
  font-size: 14px;
  font-weight: 500;
  line-height: 20px;
  list-style: none;
  padding: 6px 16px;
  position: relative;
  transition: background-color 0.2s cubic-bezier(0.3, 0, 0.5, 1);
  user-select: none;
  -webkit-user-select: none;
  touch-action: manipulation;
  vertical-align: middle;
  white-space: nowrap;
  word-wrap: break-word;
}

.button-4:hover {
  background-color: #F3F4F6;
  text-decoration: none;
  transition-duration: 0.1s;
}

.button-4:disabled {
  background-color: #FAFBFC;
  border-color: rgba(27, 31, 35, 0.15);
  color: #959DA5;
  cursor: default;
}

.button-4:active {
  background-color: #EDEFF2;
  box-shadow: rgba(225, 228, 232, 0.2) 0 1px 0 inset;
  transition: none 0s;
}

.button-4:focus {
  outline: 1px transparent;
}

.button-4:before {
  display: none;
}

.button-4:-webkit-details-marker {
  display: none;
}

#content{
  height: 550px;
  border-radius: 4%;
  border: 1px solid #ddd;
  box-shadow: 0px 6px 8px rgba(0, 0, 0, 0.2);
  margin: auto;
  padding: 10px;
  position: relative;
  display: flex;
  flex-direction: row;
}

.left-side, .right-side {
  width: 100%;
  padding: 10px;
  overflow: auto; /* Add this to make sides scrollable independently */
}

.left-side {
  overflow: auto; /* If you don't want the left side to scroll */
}

.right-side {
  overflow: auto; /* Allow right side to scroll */
}

#before-content {
   
    border: #f0e6e6;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
   height: 40px;
    background-color: #dfd7d7;

    margin: auto;
    padding: 10px;
    

}


.bf-content-setting-class{
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.left-group, .right-group {
  display: flex;
  align-items: center;
}
.question-number {
  margin: 0 10px;
}
.tooltip {
  position: relative;
  display: inline-block;
  cursor: pointer; /* Optional: Changes cursor to pointer to indicate it's interactive */
}

.tooltip .tooltiptext {
  visibility: hidden;
  width: 160px; /* Increased width to better fit the content */
  background-color: #555;
  color: #fff;
  text-align: center;
  border-radius: 6px;
  padding: 5px 0;
  position: absolute;
  z-index: 1;
  bottom: 125%; /* Positioning above the tooltip parent */
  left: 50%;
  margin-left: -80px; /* Centering the tooltip */
  opacity: 0;
  transition: opacity 0.3s;
}

.tooltip .tooltiptext::after {
  content: "";
  position: absolute;
  top: 100%;
  left: 50%;
  margin-left: -5px;
  border-width: 5px;
  border-style: solid;
  border-color: #555 transparent transparent transparent;
}

.tooltip:hover .tooltiptext {
  visibility: visible;
  opacity: 1;
}

.far {
  margin-right: 5px;
  color: #00F;
  padding: 7px;
}

/* The actual popup */
.popup .popuptext {
  width: 160px;
  background-color: #555;
  color: #fff;
  text-align: center;
  border-radius: 6px;
  padding: 8px 0;
  position: absolute;
  z-index: 1;
  bottom: 125%;
  left: 50%;
  margin-left: -80px;
}

/* Popup arrow */
.popup .popuptext::after {
  content: "";
  position: absolute;
  top: 100%;
  left: 50%;
  margin-left: -5px;
  border-width: 5px;
  border-style: solid;
  border-color: #555 transparent transparent transparent;
}

/* Toggle this class - hide and show the popup */
.popup .show {
  visibility: visible;
  -webkit-animation: fadeIn 1s;
  animation: fadeIn 1s;
}

/* Add animation (fade in the popup) */
@-webkit-keyframes fadeIn {
  from {opacity: 0;} 
  to {opacity: 1;}
}

@keyframes fadeIn {
  from {opacity: 0;}
  to {opacity:1 ;}
}

@media (max-width: 768px) {
    #content {
        flex-direction: column;
    }
    .left-side .right-side {
        width: 100%;
    }
}



#main-paragraph {
    font-size: 1.2em;
    margin-bottom: 20px;
}

#navigation-buttons {
    margin-top: 20px;
}

.input-ans {
    height: 50px;
    width: 100%;
    border-radius: 8px;
    background: transparent;
    border-bottom: 1px solid #000000;
    padding: 2px 5px;

}


button {
    padding: 10px 20px;
    margin: 0 10px;
    font-size: 1em;
    cursor: pointer;
}



/* Header */
.header {
    overflow: hidden;
    background-color: #f1f1f1;
    padding: 5px 10px;
  }
  
  .header a {
    float: left;
    color: black;
    text-align: center;
    padding: 12px;
    text-decoration: none;
    font-size: 18px; 
    line-height: 25px;
    border-radius: 4px;
  }
  
  .header a.logo {
    font-size: 25px;
    font-weight: bold;
  }
  
  .header a:hover {
    background-color: #ddd;
    color: black;
  }
  
  .header a.active {
    background-color: dodgerblue;
    color: white;
  }
  
  .header-right {
    float: right;
  }


  @media screen and (max-width: 500px) {
    .header a {
      float: none;
      /*display: block; */
      text-align: left;
    }
    
    .header-right {
      float: none;
    }
  }
  

  /* Translation api */

  .wrapper{
    border-radius: 5px;
    border: 1px solid #ccc;
  }
  .wrapper .text-input{
    display: flex;
    border-bottom: 1px solid #ccc;
  }
  .text-input .to-text{
    border-radius: 0px;
    border-left: 1px solid #ccc;
  }
  .text-input textarea{
    height: 100px;
    width: 100%;
    border: none;
    outline: none;
    resize: none;
    background: none;
    font-size: 18px;
    padding: 10px 15px;
    border-radius: 5px;
  }

  .controls, li, .icons, .icons i{
    display: flex;
    align-items: center;
    justify-content: space-between;
  }
  .controls{
    list-style: none;
    padding: 12px 15px;
  }
  .controls .row .icons{
    width: 38%;
  }
  .controls .row .icons i{
    width: 50px;
    color: #adadad;
    font-size: 14px;
    cursor: pointer;
    transition: transform 0.2s ease;
    justify-content: center;
  }
  .controls .row.from .icons{
    padding-right: 15px;
    border-right: 1px solid #ccc;
  }
  .controls .row.to .icons{
    padding-left: 15px;
    border-left: 1px solid #ccc;
  }
  .controls .row select{
    color: #333;
    border: none;
    outline: none;
    font-size: 18px;
    background: none;
    padding-left: 5px;
  }
  .text-input textarea::-webkit-scrollbar{
    width: 4px;
  }
  .controls .row select::-webkit-scrollbar{
    width: 8px;
  }
  .text-input textarea::-webkit-scrollbar-track,
  .controls .row select::-webkit-scrollbar-track{
    background: #fff;
  }
  .text-input textarea::-webkit-scrollbar-thumb{
    background: #ddd;
    border-radius: 8px;
  }
  .controls .row select::-webkit-scrollbar-thumb{
    background: #999;
    border-radius: 8px;
    border-right: 2px solid #ffffff;
  }
  .controls .exchange{
    color: #adadad;
    cursor: pointer;
    font-size: 16px;
    transition: transform 0.2s ease;
  }
  .controls i:active{
    transform: scale(0.9);
  }
  #pronunciation-zone, #translate-sentence, #speaking-check {
    border: 1px solid #ccc;
    padding: 10px;
    margin: 10px 0;
    border-radius: 5px;
    
  }
  @media (max-width: 660px){
    .container{
      padding: 20px;
    }
    .wrapper .text-input{
      flex-direction: column;
    }
    .text-input .to-text{
      border-left: 0px;
      border-top: 1px solid #ccc;
    }
    .text-input textarea{
      height: 200px;
    }
    .controls .row .icons{
      display: none;
    }
    .container button{
      padding: 13px;
      font-size: 16px;
    }
    .controls .row select{
      font-size: 16px;
    }
    .controls .exchange{
      font-size: 14px;
    }
  }



  #settingsButton {
    /*padding: 10px 20px;
    /*background-color: #007BFF; 
    color: white;*/
    right: 10;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}
/* Style for the popup */
#settingsPopup {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: white;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
    border-radius: 10px;
    padding: 20px;
    width: 300px;
    z-index: 1000;
}
/* Style for the close button */
.closeButton {
    position: absolute;
    top: 10px;
    right: 10px;
    background: none;
    border: none;
    font-size: 20px;
    cursor: pointer;
}
/* Style for the backdrop */
#backdrop {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 999;
}


/*      Change scrollbar style   */

#right-side::-webkit-scrollbar {
  width: 13px;
}

#right-side::-webkit-scrollbar-track {
  border-radius: 8px;
  background-color: #e7e7e7;
  border: 1px solid #cacaca;
}

#right-side::-webkit-scrollbar-thumb {
  border-radius: 8px;
border: 3px solid transparent;
 background-clip: content-box;
  background-color: #685757;
}

#left-side::-webkit-scrollbar {
  width: 13px;
}

#left-side::-webkit-scrollbar-track {
  border-radius: 8px;
  background-color: #e7e7e7;
  border: 1px solid #cacaca;
}

#left-side::-webkit-scrollbar-thumb {
  border-radius: 8px;
border: 3px solid transparent;
 background-clip: content-box;
  background-color: #685757;
}
    </style>
</head>
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
        <div id="video-container">
            <div id="player"></div>
        </div>
        <div id="transcript"></div>


        
    </div>
</div>
<div id = "right-side" class = "right-side" >
    <div id="question-number">2/20</div>


    <div class="controls">
      <button id="previous">Previous</button>
      <input type="text" id="userInput" placeholder="Enter transcript text here">
      <button id="checkAnswer">Check Answer</button>
      <button id="next">Next</button>
      <button id="listenAgain">Listen Again</button>
  </div>
  
    <button id="hintButton">Show Hint</button>
    <div id="hint"></div>

</div>

</div>

<div id ="ads" style="display:  flex;justify-content: center; align-items: center;">
    DEV TAG: Powered by Nguyen Minh Long
</div>
</div>


   
    

    <script>

function getStart() {     
    document.getElementById("intro").style.display = "none";
    document.getElementById("start-dictation").style.display = "block";
    navigateTranscript(0);
}
document.getElementById('listenAgain').addEventListener('click', () => {
    const currentItem = transcript[currentTranscriptIndex];
    if (currentItem) {
        clearTimeout(timeoutId); // Xóa bất kỳ timeout nào trước đó
        player.seekTo(currentItem.start, true); // Bắt đầu lại từ thời điểm bắt đầu
        player.playVideo();
        const duration = currentItem.duration * 1000;
        timeoutId = setTimeout(() => player.pauseVideo(), duration); // Tạm dừng sau khoảng thời gian của đoạn
    }
});


        // JSON transcript data (replaced with the correct format)

        // vào https://www.browserling.com/tools/text-replace đổi � thành \'
     



        // Function to generate 'end' based on start + duration
        const transcript = rawTranscript.map(item => {
            return { 
                ...item, 
                end: item.start + item.duration 
            };
        });
        const totalQuestions = transcript.length;
    let currentQuestion = 1;

    // Update the question number display
    function updateQuestionNumber() {
        document.getElementById('question-number').textContent = `${currentQuestion}/${totalQuestions}`;
    }

    function showHint() {
        const currentItem = transcript[currentTranscriptIndex];
        const text = currentItem.text;
        let hint = text.split(' ').map(word => {
            return word.split('').map((char, index) => {
                return index === 0 ? char : '_';
            }).join('');
        }).join('  ');

        document.getElementById('hint').textContent = hint;
    }

    document.getElementById('hintButton').addEventListener('click', showHint);


        let player;
        let currentTranscriptIndex = 0;
        let timeoutId;

        const tag = document.createElement('script');
        tag.src = "https://www.youtube.com/iframe_api";
        const firstScriptTag = document.getElementsByTagName('script')[0];
        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

        function onYouTubeIframeAPIReady() {
            player = new YT.Player('player', {
                height: '315',
                width: '560',
                videoId: '<?php echo esc_html($id_video);?>', // Replace with the desired video ID
                playerVars: { enablejsapi: 1 },
                events: {
                    onReady: onPlayerReady
                }
            });
        }



        function updateTranscript() {
        const currentItem = transcript[currentTranscriptIndex];
        if (currentItem) {
            clearTimeout(timeoutId);
            document.getElementById('transcript').innerText = currentItem.text;
            player.seekTo(currentItem.start, true);
            player.playVideo();
            const duration = currentItem.duration * 1000;
            timeoutId = setTimeout(() => player.pauseVideo(), duration);
            updateQuestionNumber();
        }
    }

        function navigateTranscript(direction) {
        currentQuestion += direction;
        if (currentQuestion < 1) currentQuestion = 1;
        if (currentQuestion > totalQuestions) currentQuestion = totalQuestions;
        currentTranscriptIndex = currentQuestion - 1;
        updateTranscript();
    }

    document.getElementById('previous').addEventListener('click', () => navigateTranscript(-1));
    document.getElementById('next').addEventListener('click', () => navigateTranscript(1));
    document.getElementById('checkAnswer').addEventListener('click', checkAnswer);

        function sanitizeInput(input) {
            return input.replace(/[^a-zA-Z0-9\s]/g, "").toLowerCase();
        }
        function onPlayerReady() {
        updateTranscript();
    }
        function checkAnswer() {
            const userInput = document.getElementById('userInput').value;
            const currentItem = transcript[currentTranscriptIndex];
            if (sanitizeInput(userInput) === sanitizeInput(currentItem.text)) {
                alert("Correct!");
            } else {
                alert("Incorrect. Try again.");
            }
        }
    </script>
</body>
</html>



<?php
} else {
    get_header();
    echo '<p>Please log in start reading test.</p>';
    get_footer();
}