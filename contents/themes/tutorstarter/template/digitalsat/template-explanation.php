<?php
/*
 * Template Name: Explanation Template
 * Template Post Type: digitalsat
 */
get_header(); // Gọi phần đầu trang (header.php)


    if (!defined('ABSPATH')) {
        exit; // Exit if accessed directly.
    }

    remove_filter('the_content', 'wptexturize');
    remove_filter('the_title', 'wptexturize');
    remove_filter('comment_text', 'wptexturize');

if (is_user_logged_in()) {
    $post_id = get_the_ID();
    $user_id = get_current_user_id();
    $additional_info = get_post_meta($post_id, '_digitalsat_additional_info', true);

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['doing_text'])) {
        $textarea_content = sanitize_textarea_field($_POST['doing_text']);
        update_user_meta($user_id, "digitalsat_{$post_id}_textarea", $textarea_content);

        wp_safe_redirect(get_permalink($post_id) . 'result/');
        exit;
    }
$post_id = get_the_ID();

// Get the custom number field value
//$custom_number = get_post_meta($post_id, '_digitalsat_custom_number', true);
$custom_number =intval(get_query_var('id_test'));
    ?>

<!DOCTYPE html>
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz</title>
    <style>
        .question {
            font-weight: bold;
            margin-top: 20px;
        }
        .answer-box {
            margin-left: 20px;
            margin-top: 10px;
        }
        .correct-answer {
            font-weight: bold;
            color: green;
        }
        .explanation {
            margin-top: 10px;
            font-style: italic;
            color: gray;
        }
        .image-container img {
            max-width: 100%;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div id="quiz-container"></div>

        <script>
           

let pre_id_test_ = `<?php echo esc_html($custom_number);?>`;
console.log(`${pre_id_test_}`)


                
         

<?php echo wp_kses_post($additional_info);
?>

function renderQuiz() {
        const container = document.getElementById('quiz-container');
        let quizHtml = `<h1>${quizData.title}</h1>`;

        quizData.questions.forEach((question, index) => {
            let correctAnswer = '';

            if (question.type === 'multiple-choice' || question.type === 'multi-select') {
                const correctAnswerIndex = question.answer.findIndex(ans => ans[1] === "true");
                if (correctAnswerIndex !== -1) {
                    correctAnswer = `Correct Answer: ${question.answer[correctAnswerIndex][0][0]}`; // Extract 'A', 'B', etc.
                }
            } else if (question.type === 'completion') {
                correctAnswer = `Correct Answer: ${question.answer.join(', ')}`; // For completion-type, show all possible correct answers
            }

            quizHtml += `
                <div class="question">
                    <p>Question ${index + 1}: ${question.question}</p>
                    ${question.image ? `<div class="image-container"><img src="${question.image}" alt="Question Image"></div>` : ''}
                    <p class="answer-box">${question.type === 'completion' ? 'Answer Box: ' + question.answer_box.map(ans => ans[0]).join(', ') : 'Answer: ' + question.answer.map(ans => ans[0]).join(', ')}</p>
                    <p class="correct-answer">${correctAnswer}</p>
                    <div class="explanation">Explanation: ${question.explanation}</div>
                </div>
            `;
        });

        container.innerHTML = quizHtml;
    }

    renderQuiz();

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