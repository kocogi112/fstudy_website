jQuery(document).ready(function($) {
    $('#generate_questions_button').on('click', function() {
        // Clear previous textareas
        $('#question_container').empty();

        // Get the number of questions from the textarea
        var numberOfQuestions = parseInt($('#number_of_questions').val());

        // Check if it's a valid number
        if (isNaN(numberOfQuestions) || numberOfQuestions <= 0) {
            alert('Please enter a valid number of questions.');
            return;
        }

        // Loop to generate textareas and answer areas
        for (var i = 1; i <= numberOfQuestions; i++) {
            // Create a label and textarea for the question
            var questionLabel = $('<label>').text('Question ' + i + ':');
            var questionTextarea = $('<textarea>').attr({
                id: 'question_' + i,
                name: 'question_' + i,
                rows: 3,
                cols: 40
            });

            // Create a div for the answer area
            var answerDiv = $('<div>').attr({
                id: 'answer_area_' + i,
                class: 'answer-area'
            }).text('Answer area for Question ' + i);

            // Append the label, textarea, and answer area to the container
            $('#question_container').append(questionLabel, questionTextarea, answerDiv, '<br><br>');
        }
    });
});