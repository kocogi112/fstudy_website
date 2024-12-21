// Function to calculate the starting question number for a part
function getStartingQuestionNumber(partIndex, quizData) {
    let startQuestion = 1; // Start with question 1
    for (let i = 0; i < partIndex; i++) {
        startQuestion += parseInt(quizData.part[i].number_question_of_this_part);
    }
    return startQuestion;
}

// Function to calculate question range for a part
function getQuestionRange(partIndex, quizData) {
    const startQuestion = getStartingQuestionNumber(partIndex, quizData);
    const endQuestion = startQuestion + parseInt(quizData.part[partIndex].number_question_of_this_part) - 1;
    return `${startQuestion} - ${endQuestion}`;
}

// Function to log user answers dynamically for a specific row
function logUserAnswers(partIndex, quizData, rowId) {
    let userAnswerdiv = document.getElementById(`useranswerdiv_${rowId}`); // Target div by row ID
    
    const part = quizData.part[partIndex];
    let totalQuestions = 0; // Total question count

    // Initialize the current question number for this part
    let currentQuestionNumber = getStartingQuestionNumber(partIndex, quizData);

    part.group_question.forEach((group, groupIndex) => {
        group.questions.forEach((question, questionIndex) => {
            let questionNumber;
            let correctAnswer;

            // Handle multi-select questions
            if (group.type_group_question === "multi-select") {
                const answerChoiceCount = parseInt(question.number_answer_choice) || 1;
                questionNumber = `${currentQuestionNumber}-${currentQuestionNumber + answerChoiceCount - 1}`;
              
                const correctAnswers = [];

                question.answers.forEach((answer, answerIndex) => {
                    if (answer[1] === true) {
                        correctAnswers.push(answer[0]);
                    }
                });

                correctAnswer = correctAnswers.length > 0 ? correctAnswers.join(', ') : "Not available";

                userAnswerdiv.innerHTML += `Question ${questionNumber}: Correct Answer: ${correctAnswer}<br>`;

                currentQuestionNumber += answerChoiceCount; // Increment the question number
                totalQuestions += answerChoiceCount; // Count total number of questions
            }

            // Handle completion questions
            else if (group.type_group_question === "completion") {
                question.box_answers.forEach((boxAnswer, boxIndex) => {
                    const completionNumber = currentQuestionNumber + boxIndex;

                    correctAnswer = boxAnswer.answer ? boxAnswer.answer : "Not available";

                    userAnswerdiv.innerHTML += `Question ${completionNumber}: Correct Answer: ${correctAnswer}<br>`;
                });

                currentQuestionNumber += question.box_answers.length; // Increment by the number of boxes
                totalQuestions += question.box_answers.length; // Count total number of completion questions
            }

            // Handle multiple-choice questions
            else if (group.type_group_question === "multiple-choice") {
                questionNumber = `${currentQuestionNumber}`;

                const correctAnswerIndex = question.answers.findIndex(answer => answer[1] === true);
                correctAnswer = correctAnswerIndex !== -1 ? question.answers[correctAnswerIndex][0] : "Not available";

                userAnswerdiv.innerHTML += `Question ${questionNumber}: Correct Answer: ${correctAnswer}<br>`;

                currentQuestionNumber++; // Increment the question number for the next question
                totalQuestions++; // Count as a single question
            }
        });
    });
}
