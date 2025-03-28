<?php
/*
 * Template Name: Doing Template Shadowing
 * Template Post Type: shadowing
 
 */


if (is_user_logged_in()) {
    $post_id = get_the_ID();
    $user_id = get_current_user_id();
    $current_user = wp_get_current_user();
    $current_username = $current_user->user_login;
    $username = $current_username;


$custom_number = intval(get_query_var('id_test'));
//$custom_number = get_post_meta($post_id, '_shadowing_custom_number', true);
// Database credentials (update with your own database details)
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

$sql_test = "SELECT * FROM shadowing_question WHERE id_test = ?";


$stmt_test = $conn->prepare($sql_test);
$stmt_test->bind_param("i", $custom_number); // 'i' is used for integer
$stmt_test->execute();
$result_test = $stmt_test->get_result();




// Query to fetch token details for the current username
$sql_user = "SELECT token, token_use_history 
         FROM user_token 
         WHERE username = ?";
$site_url = get_site_url();

echo '
<script>

var siteUrl = "' . $site_url .'";
var id_test = "' . $id_test . '";
</script>
';



if ($result_test->num_rows > 0) {
    // Lấy các ID từ question_choose (ví dụ: "1001,2001,3001")
    $data = $result_test ->fetch_assoc();
    $testname = $data['testname'];
    $token_need = $data['token_need'];
    $time_allow = $data['time_allow'];
    $permissive_management = $data['permissive_management'];

    $id_video = $data['id_video'];
    $transcript = $data['transcript'];
    $type_test = $data['type_test'];



    add_filter('document_title_parts', function ($title) use ($testname) {
        $title['title'] = $testname; // Use the $testname variable from the outer scope
        return $title;
    });
    get_header();
    $stmt_user = $conn->prepare($sql_user);
    if (!$stmt_user) {
        die("Error preparing statement 2: " . $conn->error);
    }

    $stmt_user->bind_param("s", $current_username);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();

    if ($result_user->num_rows > 0) {
        $token_data = $result_user->fetch_assoc();
        $token = $token_data['token'];
        $token_use_history = $token_data['token_use_history'];

        echo "<script>console.log('Token: $token, Token Use History: $token_use_history, Mày tên: $current_username');</script>";
       

    } else {
        echo "Lỗi đề thi";
        
    }


      
            $permissiveManagement = json_decode($permissive_management, true);
            
            // Chuyển mảng PHP thành JSON string để có thể in trong console.log
            echo "<script> 
                    console.log('$permissive_management');
                </script>";
            
            
            $foundUser = null;
            if (!empty($permissiveManagement)) {
                foreach ($permissiveManagement as $entry) {
                    if ($entry['username'] === $current_username) {
                        $foundUser = $entry;
                        break;
                    }
                }
            }
        
            $premium_test = "False"; // Default value
            if ($foundUser != null && $foundUser['time_left'] > 0 || $token_need == 0) {
                if ($token_need > 0) {
                    $premium_test = "True";
                }
            
            
                echo '<script>
                let premium_test = "' . $premium_test . '";
                let token_need = "' . $token_need . '";
                let change_content = "' . $testname . '";
                let time_left = "' . (isset($foundUser['time_left']) ? $foundUser['time_left'] : 10) . '";
            </script>';
            






echo '
<script>
const rawTranscript = [' . $transcript . '];


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
    <title>Shadowing Exercise</title>
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


.container1{
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

#content1
{
  height: 100%;
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
  display:none;
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
    #content1 {
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
  .text-input {
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
.textarea{
  
  display: block;
    width: 100%;
    font-weight: 400;
    appearance: none;
    border-radius: var(--bs-border-radius);
    transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
    padding: 10px !important;
    margin-bottom: 4px !important;
    resize: none;
    z-index: 1;
    position: relative;
    font-family: sans-serif;
    font-size: 100%;
    line-height: 1.15;
    margin: 0;
}
  .controls, li, .icons, .icons i{
    display: flex;
    align-items: center;
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

.bf-content-setting-class{
  display: flex;
  align-items: center;
}
.opt-player{
  display: flex;
  align-items: center;
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

<!-- HTML !-->

/* CSS */
.button-11 {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 6px 14px;
  font-family: -apple-system, BlinkMacSystemFont, 'Roboto', sans-serif;
  border-radius: 6px;
  border: none;

  color: #fff;
  background: linear-gradient(180deg,rgb(143, 150, 160) 0%, #367AF6 100%);
   background-origin: border-box;
  box-shadow: 0px 0.5px 1.5px rgba(54, 122, 246, 0.25), inset 0px 0.8px 0px -0.25px rgba(255, 255, 255, 0.2);
  user-select: none;
  -webkit-user-select: none;
  touch-action: manipulation;
}

.button-11:focus {
  box-shadow: inset 0px 0.8px 0px -0.25px rgba(255, 255, 255, 0.2), 0px 0.5px 1.5px rgba(54, 122, 246, 0.25), 0px 0px 0px 3.5px rgba(58, 108, 217, 0.5);
  outline: 0;
}



.button-11 {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 6px 14px;
  font-family: -apple-system, BlinkMacSystemFont, 'Roboto', sans-serif;
  border-radius: 6px;
  border: none;

  color: #fff;
  background: gray;
   background-origin: border-box;
  box-shadow: 0px 0.5px 1.5px rgba(54, 122, 246, 0.25), inset 0px 0.8px 0px -0.25px rgba(255, 255, 255, 0.2);
  user-select: none;
  -webkit-user-select: none;
  touch-action: manipulation;
}

.button-11:focus {
  box-shadow: inset 0px 0.8px 0px -0.25px rgba(255, 255, 255, 0.2), 0px 0.5px 1.5px rgba(54, 122, 246, 0.25), 0px 0px 0px 3.5px rgba(58, 108, 217, 0.5);
  outline: 0;
}


.player{
  height: 400px;
  width: 100%;
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
/* HTML: <div class="loader"></div> */
.loader {
  width: 70px;
  aspect-ratio: 1;
  border-radius: 50%;
  border: 8px solid #514b82;
  animation: l20-1 0.8s infinite linear alternate, l20-2 1.6s infinite linear;
}

.start_test {
  appearance: none;
  background-color: #2ea44f;
  border: 1px solid rgba(27, 31, 35, .15);
  border-radius: 6px;
  box-shadow: rgba(27, 31, 35, .1) 0 1px 0;
  box-sizing: border-box;
  color: #fff;
  cursor: pointer;
  display: inline-block;
  font-family: -apple-system,system-ui,"Segoe UI",Helvetica,Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji";
  font-size: 20px;
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

.start_test:focus:not(:focus-visible):not(.focus-visible) {
  box-shadow: none;
  outline: none;
}
.icon {
    width: 20px;
    height: 20px;
    vertical-align: middle;
}

.start_test:hover {
  background-color: #2c974b;
}

.start_test:focus {
  box-shadow: rgba(46, 164, 79, .4) 0 0 0 3px;
  outline: none;
}

.start_test:disabled {
  background-color: #94d3a2;
  border-color: rgba(27, 31, 35, .1);
  color: rgba(255, 255, 255, .8);
  cursor: default;
}

.start_test:active {
  background-color: #298e46;
  box-shadow: rgba(20, 70, 32, .2) 0 1px 0 inset;
}
</style>
</head>
<body onload = "main()">


 

<div class="container1">
   
    <div id = "test-prepare">
        <div class="loader"></div>
        <h3>Your test will begin shortly</h3>
        <div id = "checkpoint" class = "checkpoint">
                <?php
                    if($premium_test == "True"){
                        echo "<script >console.log('Thông báo. Bạn còn {$foundUser['time_left']} lượt làm bài. success ');</script>";
                        echo " <p style = 'color:green'> Bạn còn {$foundUser['time_left']} lượt làm bài này <svg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 24 24' fill='none' stroke='#7ed321' stroke-width='2' stroke-linecap='round' stroke-linejoin='arcs'><path d='M22 11.08V12a10 10 0 1 1-5.93-9.14'></path><polyline points='22 4 12 14.01 9 11.01'></polyline></svg> </p> ";
                        echo "<script>console.log('This is premium test');</script>";
                    }
                    else{
                        echo "<script>console.log('This is free test');</script>"; 
                    }
                        ?>
        </div>    
        <div id = "quick-instruction">
            <i>Quick Instruction:<br>
            - If you find any errors from test (image,display,text,...), please let us know by clicking icon <i class="fa-solid fa-bug"></i><br> 
            - Incon <i class="fa-solid fa-circle-info"></i> will give you a guide tour, in which you can understand the structure of test, include test's type, formation and how to answer questions<br>
            - All these two icons are at the right-above side of test.
        </i>

        </div>
        <div style="display: none;" id="date" style="visibility:hidden;"></div>
        <div style="display: none;" id="title-test"><?php echo esc_html($testname);?></div>
        <div  style="display: none;"  id="id_test"  style="visibility:hidden;"><?php echo esc_html($custom_number);?></div>
        <button  style="display: none;" class ="start_test" id="start_test"  onclick = "pregetStart()">Start test</button>
        <i id = "welcome" style = "display:none">Click Start Test button to start the test now. Good luck</i>


    </div>




    <div id="content1">
        <div id = 'left-side' class="left-side">


        <div id = "checkpoint" class = "checkpoint">
                <?php
                    if($premium_test == "True"){
                        echo "<script >console.log('Thông báo. Bạn còn {$foundUser['time_left']} lượt làm bài. success ');</script>";
                        echo " <p style = 'color:green'> Bạn còn {$foundUser['time_left']} lượt làm bài này <svg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 24 24' fill='none' stroke='#7ed321' stroke-width='2' stroke-linecap='round' stroke-linejoin='arcs'><path d='M22 11.08V12a10 10 0 1 1-5.93-9.14'></path><polyline points='22 4 12 14.01 9 11.01'></polyline></svg> </p> ";
                        echo "<script>console.log('This is premium test');</script>";
                    }
                    else{
                        echo "<script>console.log('This is free test');</script>"; 
                    }
                        ?>
        </div>    
       

    <div id ="start-dictation" style="display: none;">
        <div id="video-container">
            <div id="player" class = "player"></div>
        </div>

      <div class ="opt-player">
        <select name="video-width" id="video-width">
          <option value="small">Video Size: Small</option>
          <option value="normal" selected>Video Size: Normal</option>
          <option value="large">Video Size: Large</option>
          <option value="extra-large">Video Size: Extra Large</option>
        </select>
        <br>
        <button id="toggle-video-btn">Hide Video</button>
    </div>
      <div id="transcript"></div>


        
    </div>
</div>
<div id = "right-side" class = "right-side" >


<div class = "bf-content-setting-class" >
        <svg id="previous"  xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
        </svg>
        <div class="question-number" id="question-number"></div>
        <svg id="next" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/>
        </svg>
</div>


      <button id="listenAgain" class="button-4" role="button"><i class="fa-solid fa-play"></i> Listen Again</button><br>
      <textarea class = "textarea" type="text" id="userInput" style = "display:none" placeholder="Enter transcript text here"></textarea>

      
    <div class="controls">
      <button id="checkAnswer" class="button-10" role="button" style = "display:none" >Check Answer</button>
      <button id="listenAgain" class="button-11" role="button"  style = "display:none" >Listen Again</button>
  </div>

  <div class="record-controls">
    <button id="startRecord"><svg  class="icon" version="1.1" id="Uploaded to svgrepo.com" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 32 32" xml:space="preserve" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <style type="text/css"> .isometric_zeven{fill:#FF7344;} .isometric_tien{fill:#7BD6C4;} .isometric_elf{fill:#72C0AB;} .isometric_twaalf{fill:#569080;} .isometric_dertien{fill:#225B49;} .st0{fill:#F28103;} .st1{fill:#BE1E2D;} .st2{fill:#F05A28;} .st3{fill:#F29227;} .st4{fill:#F8F7C5;} .st5{fill:#F5DF89;} .st6{fill:#AD9A74;} .st7{fill:none;} .st8{fill:#F2D76C;} .st9{fill:#72C0AB;} .st10{fill:#7BD6C4;} .st11{fill:#569080;} </style> <g> <path class="isometric_dertien" d="M21.496,11.298l-3.174-3.174l-2.161-2.161c-1.562-1.562-4.095-1.562-5.657,0 c-1.562,1.562-1.562,4.095,0,5.657l2.179,2.179l3.156,3.156c1.562,1.562,4.095,1.562,5.657,0 C23.058,15.392,23.058,12.86,21.496,11.298z"></path> <path class="isometric_tien" d="M18.987,20.926c-2.306-0.004-3.994,1.11-3.987,2.302c0.016,3.027,8.016,3.036,8,0.014 C22.993,22.053,21.307,20.931,18.987,20.926z"></path> <path class="isometric_twaalf" d="M20.83,11.727c-0.29-0.186-0.84-0.471-0.991-0.555c-0.703-0.391-2.563,0.833-2.563,2.75 c0,0.469,0.137,0.801,0.374,0.968c0.168,0.118,0.771,0.425,0.995,0.558c0.263,0.216,0.664,0.21,1.15-0.07 c0.933-0.539,1.513-1.616,1.513-2.607C21.308,12.221,21.131,11.862,20.83,11.727z"></path> <path class="isometric_elf" d="M18.308,14.503c0-1.019,0.63-2.098,1.513-2.608c0.883-0.51,1.487-0.143,1.487,0.876 c0,0.991-0.58,2.068-1.513,2.607C18.913,15.887,18.308,15.494,18.308,14.503z M19.044,10.439c-0.085,0.323-0.218,0.648-0.387,0.965 c0.451-0.29,0.906-0.386,1.183-0.232c0.109,0.061,0.43,0.228,0.708,0.386c0.101-0.624-0.016-1.131-0.286-1.496l-1.436-1.436 C19.139,9.052,19.246,9.668,19.044,10.439z M17.65,14.891c-0.237-0.167-0.374-0.499-0.374-0.968c0-0.316,0.051-0.612,0.138-0.886 c-0.707,0.688-1.554,1.225-2.396,1.455c-0.775,0.212-1.4,0.117-1.833-0.19l1.436,1.436c0.432,0.307,1.058,0.402,1.833,0.19 c0.546-0.149,1.089-0.436,1.597-0.805C17.88,15.03,17.724,14.942,17.65,14.891z"></path> <path class="st11" d="M19.011,22.448v-0.056C19.01,22.4,19,22.407,19,22.415C19,22.426,19.009,22.437,19.011,22.448z"></path> <path class="isometric_twaalf" d="M23,23.243v1.388h-0.011c0.171,3.089-8.085,3.106-7.98,0H15v-1.403 C15.016,26.257,23.016,26.263,23,23.243z M18.048,22.241v0.509c-0.038,1.128,2.962,1.121,2.9,0l0.004-0.504 C20.958,23.343,18.054,23.34,18.048,22.241z M21.896,14.185l-1.913-1.111c-0.299,0.044-0.62,0.49-0.525,0.852l1.434,0.833 c-0.005,1,0.235,4.641-2.121,4.641c-1.682,0-3.461-1.943-4.45-3.963c-1.066-1.066-1.293-1.293-1.706-1.706 c0.981,3.574,3.638,6.67,6.156,6.67c0.082,0,0.162-0.003,0.24-0.009v2c0,0.448,1.044,0.403,0.988,0.003v-2.241 C22.06,19.206,21.888,15.78,21.896,14.185z"></path> <path class="isometric_zeven" d="M16.44,23.728c-0.28-0.28-0.195-0.591,0.19-0.694c0.385-0.103,0.925,0.04,1.205,0.321 c0.28,0.28,0.195,0.591-0.19,0.694C17.259,24.152,16.72,24.008,16.44,23.728z"></path> </g> </g></svg>Start Record</button>
    <button id="stopRecord" disabled> <svg  class="icon" viewBox="0 0 1024 1024" class="icon" version="1.1" xmlns="http://www.w3.org/2000/svg" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M512 1024C229.7 1024 0 794.3 0 512S229.7 0 512 0s512 229.7 512 512-229.7 512-512 512z m0-938.7C276.7 85.3 85.3 276.7 85.3 512S276.7 938.7 512 938.7 938.7 747.3 938.7 512 747.3 85.3 512 85.3z" fill="#3688FF"></path><path d="M640 682.7H384c-23.6 0-42.7-19.1-42.7-42.7V384c0-23.6 19.1-42.7 42.7-42.7h256c23.6 0 42.7 19.1 42.7 42.7v256c0 23.6-19.1 42.7-42.7 42.7z m-213.3-85.4h170.7V426.7H426.7v170.6z" fill="#5F6379"></path></g></svg>Stop Record</button>
  </div>
  <div id="recordedText"></div>
  <div id="confidentialLevel"></div>
  <audio id="audioPlayback" controls style="display: none;"></audio>





  
    <button id="hintButton"  style = "display:none" >Show Hint</button>
    <div id="hint"  style = "display:none" ></div>


</div>

</div>

<!--<div id ="ads" style="display:  flex;justify-content: center; align-items: center;">
    DEV TAG: Powered by Nguyen Minh Long
</div> -->
</div>


   
    

    <script>
      function main(){
    console.log("Passed Main");
    
    
    setTimeout(function(){
        console.log("Show Test!");
        document.getElementById("start_test").style.display="block";
        
        document.getElementById("welcome").style.display="block";

    }, 1000);
    
}

function pregetStart()
{
    if(premium_test == "False"){
        console.log("Cho phép làm bài")
    }
    else{
    console.log(premium_test);
    console.log(token_need);
    console.log(change_content);
    console.log(time_left);
    // Giảm time_left tại frontend
    time_left--;
    console.log("Updated time_left:", time_left);

    // Gửi request AJAX đến admin-ajax.php
    jQuery.ajax({
        url: `${siteUrl}/wp-admin/admin-ajax.php`,
        type: "POST",
        data: {
            action: "update_time_left",
           // username: change_content,
            time_left: time_left,
            id_test: id_test,
            table_test: 'shadowing_question',

        },
        success: function (response) {
            console.log("Server response:", response);
        },
        error: function (error) {
            console.error("Error updating time_left:", error);
        }
    });
}
getStart();
}
function pregetStart()
{
    if(premium_test == "False"){
        console.log("Cho phép làm bài")
    }
    else{
    console.log(premium_test);
    console.log(token_need);
    console.log(change_content);
    console.log(time_left);
    // Giảm time_left tại frontend
    time_left--;
    console.log("Updated time_left:", time_left);

    // Gửi request AJAX đến admin-ajax.php
    jQuery.ajax({
        url: `${siteUrl}/wp-admin/admin-ajax.php`,
        type: "POST",
        data: {
            action: "update_time_left",
           // username: change_content,
            time_left: time_left,
            id_test: id_test,
            table_test: 'shadowing_question',

        },
        success: function (response) {
            console.log("Server response:", response);
        },
        error: function (error) {
            console.error("Error updating time_left:", error);
        }
    });
}
getStart();
}

function getStart() {    
  document.getElementById("test-prepare").style.display = "none";
 
    document.getElementById("left-side").style.display = "block";
    document.getElementById("right-side").style.display = "block";

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

    let recognition;
let isRecording = false;
let mediaRecorder;
let audioChunks = [];
let audioBlob;

// Kiểm tra nếu trình duyệt hỗ trợ MediaRecorder
if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
    const audioContext = new (window.AudioContext || window.webkitAudioContext)();

    let mediaRecorder;
    let isRecording = false;
    let recognition;
    let audioChunks = [];
    let transcript = []; // Add transcript array for storing recognized text

    // Bắt đầu ghi âm
    document.getElementById('startRecord').addEventListener('click', async () => {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
            mediaRecorder = new MediaRecorder(stream);

            audioChunks = [];
            mediaRecorder.ondataavailable = (event) => {
                audioChunks.push(event.data);
            };

            mediaRecorder.onstop = () => {
                const audioBlob = new Blob(audioChunks, { type: 'audio/webm' });
                const audioURL = URL.createObjectURL(audioBlob);

                // Hiển thị đoạn ghi âm
                const audioElement = document.getElementById('audioPlayback');
                audioElement.src = audioURL;
                audioElement.style.display = 'block';
            };

            mediaRecorder.start();
            isRecording = true;

            document.getElementById('startRecord').disabled = true;
            document.getElementById('stopRecord').disabled = false;

            // Kết hợp với Web Speech API
            if ('webkitSpeechRecognition' in window) {
                recognition = new webkitSpeechRecognition();
                recognition.continuous = true; // Đảm bảo nhận diện liên tục
                recognition.lang = 'en-US';
                recognition.interimResults = true; // Cho phép nhận diện tạm thời

                recognition.onresult = (event) => {
                    const spokenText = event.results[event.resultIndex][0].transcript.trim();
                    document.getElementById('recordedText').innerText = `You said: ${spokenText}`;

                    // Lưu trữ văn bản nhận diện vào transcript để xử lý sau
                    transcript.push(spokenText);

                    const currentItem = transcript[currentTranscriptIndex];
                    const sanitizedSpokenText = sanitizeInput(spokenText);
                    const sanitizedCorrectText = sanitizeInput(currentItem.text);

                    if (sanitizedSpokenText === sanitizedCorrectText) {
                        document.getElementById('confidentialLevel').innerText = "You are right!";
                    } else {
                        document.getElementById('confidentialLevel').innerText = "Try again.";
                    }
                };

                recognition.start(); // Bắt đầu nhận diện giọng nói liên tục
            }
        } catch (error) {
            console.error('Error accessing microphone', error);
        }
    });

    // Dừng ghi âm
    document.getElementById('stopRecord').addEventListener('click', () => {
        if (mediaRecorder && isRecording) {
            mediaRecorder.stop();
            isRecording = false;

            document.getElementById('startRecord').disabled = false;
            document.getElementById('stopRecord').disabled = true;

            if (recognition) {
                recognition.stop(); // Dừng nhận diện khi nhấn "Stop Record"
            }
        }
    });
} else {
    console.error("MediaRecorder not supported on this browser");
}


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
              videoId: '<?php echo esc_html($id_video);?>', // Thay bằng ID video mong muốn
              playerVars: { 
                  enablejsapi: 1, 
                  cc_load_policy: 0 // Tắt phụ đề mặc định
              },
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
    // Kiểm tra nếu input không phải là undefined hoặc null
    if (input === undefined || input === null) {
        return ''; // Trả về một chuỗi trống nếu input không hợp lệ
    }
    // Thực hiện việc loại bỏ ký tự đặc biệt và chuyển đổi thành chữ thường
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


}
else{
    get_header();
    if (!$foundUser) {
        echo "
        <div class='checkout-modal-overlay'>
            <div class='checkout-modal'>
                <h3>Bạn chưa mua đề thi này</h3>";     
        } 

    else if ($foundUser['time_left'] <= 0) {
        echo "
        <div class='checkout-modal-overlay'>
            <div class='checkout-modal'>
                <h3> Bạn đã từng mua test này nhưng số lượt làm test này đã hết rồi, vui lòng mua thêm token<i class='fa-solid fa-face-sad-tear'></i></h3>";
    }

    echo"
            <p> Bạn đang có: $token token</p>
            <p> Để làm test này bạn cần $token_need token. Bạn sẽ được làm test này $time_allow lần </p>
            <p class = 'info-buy'>Bạn có muốn mua $time_allow lượt làm test này với $token_need không ?</button>
                <div class='button-group'>
                    <button class='process-token' onclick='preProcessToken()'>Mua ngay</button>
                    <button style = 'display:none' class='close-modal'>Hủy</button>
                </div>  
            </div>
        </div>
        
        <script>
    
    function preProcessToken() {
        if ($token < $token_need) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                html: 'Bạn không đủ token để mua test này',
                footer: `<a href='${site_url}/dashboard/buy_token/'>Nạp token vào tài khoản ngay</a>`
            });
        } else {
            console.log(`Allow to next step`);
            jQuery.ajax({
                url: `${site_url}/wp-admin/admin-ajax.php`,
                type: 'POST',
                data: {
                    action: 'update_buy_test',
                    type_transaction: 'paid',
                    table: 'shadowing_question',
                    change_token: '$token_need',
                    payment_gate: 'token',
                    title: 'Buy test $testname with $id_test (Shadowing Test) with $token_need token (Buy $time_allow time do this test)',
                    id_test: id_test
                },
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Mua test thành công!',
                        text: 'Trang sẽ được làm mới sau 2 giây.',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true,
                        willClose: () => location.reload()
                    });
                },
                error: function (error) {
                    console.error('Error updating time_left:', error);
                }
            });
        }
    }
        </script>
        <style>
.checkout-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
}

.checkout-modal {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    width: 400px;
    text-align: center;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.checkout-modal h3 {
    font-size: 18px;
    color: #333;
}

.checkout-modal p {
    margin: 10px 0;
    color: #555;
}

.checkout-modal .button-group {
    margin-top: 20px;
}

.process-token {
    background-color: #007bff;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    margin-right: 10px;
    font-size: 14px;
}

.process-token:hover {
    background-color: #0056b3;
}

.close-modal {
    background-color: #ccc;
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 14px;
}

.close-modal:hover {
    background-color: #aaa;
}
</style>

<script>
    document.querySelector('.close-modal')?.addEventListener('click', function() {
        document.querySelector('.checkout-modal-overlay').style.display = 'none';
    });
</script>
        ";
        } 
    }
    
 else {
        get_header();
            echo "<p>Không tìm thấy đề thi.</p>";
            exit();
    }

} else {
    get_header();
    echo "<p>Please log in to submit your answer.</p>";

}