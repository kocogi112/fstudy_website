<?php
get_header(); // Gọi phần đầu trang (header.php)
$post_id = get_the_ID();
$user_id = get_current_user_id();
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

// Prepare the SQL query to fetch question_content and stt where id_test matches the custom_number
$sql = "SELECT stt, question_content, topic, speaking_part, sample  FROM ielts_speaking_part_1_question WHERE id_test = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $custom_number); // 'i' is used for integer
$stmt->execute();
$result = $stmt->get_result();

// Prepare the SQL query to fetch question_content and stt where id_test matches the custom_number
$sql2 = "SELECT question_content, topic, speaking_part, sample  FROM ielts_speaking_part_2_question WHERE id_test = ?";
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("i", $custom_number); // 'i' is used for integer
$stmt2->execute();
$result2 = $stmt2->get_result();


// Prepare the SQL query to fetch question_content and stt where id_test matches the custom_number
$sql3 = "SELECT stt, question_content, topic, speaking_part, sample  FROM ielts_speaking_part_3_question WHERE id_test = ?";
$stmt3 = $conn->prepare($sql3);
$stmt3->bind_param("i", $custom_number); // 'i' is used for integer
$stmt3->execute();
$result3 = $stmt3->get_result();

// Check if there are any results and display them
if ($result->num_rows > 0) {
    echo "<h3>Ielts Speaking Part 1 Questions:</h3>";
    echo "<p>Speaking Part 1</p>";
    echo "<table border='1'>
            <tr>
                <th>STT</th>
                <th>Question Content</th>
                <th>Title</th>
                <th>Sample</th>
                <th>Speaking part</th>

            </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . htmlspecialchars($row['stt']) . "</td>
                <td>" . htmlspecialchars($row['question_content']) . "</td>
                <td>" . htmlspecialchars($row['topic']) . "</td>
                <td>" . htmlspecialchars($row['sample']) . "</td>
                <td>" . htmlspecialchars($row['speaking_part']) . "</td>

              </tr>";
    }
    echo "</table>";
    
}

else if ($result2->num_rows > 0) {
    echo "<h3>Ielts Speaking Part 2 Questions:</h3>";
    echo "<p>Speaking Part 2</p>";
    echo "<table border='1'>
            <tr>
                <th>Question Content</th>
                <th>Title</th>
                <th>Sample</th>
                <th>Speaking part</th>

            </tr>";
    while ($row = $result2->fetch_assoc()) {
        echo "<tr>
                <td>" . htmlspecialchars($row['question_content']) . "</td>
                <td>" . htmlspecialchars($row['topic']) . "</td>
                <td>" . htmlspecialchars($row['sample']) . "</td>
                <td>" . htmlspecialchars($row['speaking_part']) . "</td>

              </tr>";
    }
    echo "</table>";
    
}
else if ($result3->num_rows > 0) {
    echo "<h3>Ielts Speaking Part 3 Questions:</h3>";
    echo "<p>Speaking Part 3</p>";
    echo "<table border='1'>
            <tr>
                <th>STT</th>
                <th>Question Content</th>
                <th>Title</th>
                <th>Sample</th>
                <th>Speaking part</th>

            </tr>";
    while ($row = $result3->fetch_assoc()) {
        echo "<tr>
                <td>" . htmlspecialchars($row['stt']) . "</td>
                <td>" . htmlspecialchars($row['question_content']) . "</td>
                <td>" . htmlspecialchars($row['topic']) . "</td>
                <td>" . htmlspecialchars($row['sample']) . "</td>
                <td>" . htmlspecialchars($row['speaking_part']) . "</td>

              </tr>";
    }
    echo "</table>";
    
}
 else {
    echo "No questions found for this test.";
}


// Close the database connection
$conn->close();

if (have_posts()) :
    while (have_posts()) : the_post(); ?>
        <h1><?php the_title(); ?></h1>
        <div><?php the_content(); ?></div>
        <a href="testing-speaking">Start do test</a>
    <?php
    endwhile;
endif;

get_footer(); // Gọi phần cuối trang (footer.php)
?>
