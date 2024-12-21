let currentPartIndex = 0;

function loadPart(partIndex) {
    const part = quizData.part[partIndex];

    // Display the paragraph
    document.getElementById('paragraph-container').innerHTML = `<p>${part.paragraph}</p>`;

    // Display the question range
    const questionRange = getQuestionRange(partIndex);

    // Update the question range and add the timer
    const timerHtml = `<span id="timer" style="font-weight: bold></span>`;

    // Start the timer
    // Display the groups and questions
    const questionsContainer = document.getElementById('questions-container');
    questionsContainer.innerHTML = ''; // Clear previous content

    // Calculate the starting question number for this part
    let currentQuestionNumber = getStartingQuestionNumber(partIndex);

    part.group_question.forEach((group, groupIndex) => {
        const groupContainer = document.createElement('div');
        groupContainer.classList.add('group-container');
        groupContainer.innerHTML = `
            <h4>Question ${group.group}:</h4>
            <p>${group.question_group_content}</p>
        `;
    
        group.questions.forEach((question, questionIndex) => {
            const questionElement = document.createElement('div');
            questionElement.classList.add('question');
    
            // Multi-select questions
            if (group.type_group_question === "multi-select") {
                const answerChoiceCount = parseInt(question.number_answer_choice) || 1;
                const questionRange = `${currentQuestionNumber}-${currentQuestionNumber + answerChoiceCount - 1}`;
                const correctAnswers = question.answers
                    .filter(answer => answer[1] === true)
                    .map(answer => answer[0]);
    
                questionElement.innerHTML = `
                    <p id="question-id-${questionRange}"><b>${questionRange}.</b> ${question.question}</p>
                `;
    
                question.answers.forEach((answer, answerIndex) => {
                    const answerOption = document.createElement('div');
                    const inputId = `answer-input-${questionRange}-${answerIndex + 1}`;
    
                    answerOption.innerHTML = `
                        <label>
                            <input type="checkbox" id="${inputId}" name="question-${currentQuestionNumber}" value="${answer[0]}">
                            ${answer[0]}
                        </label>
                        <br> 
                        <p><b>Correct Answer:</b> ${correctAnswers.length > 0 ? correctAnswers.join(', ') : "Not available"}</p>

                    `;
    
                    const checkbox = answerOption.querySelector('input');
                    checkbox.addEventListener('change', (event) => {
                        saveUserAnswer(partIndex, groupIndex, questionIndex, answerIndex, event.target.checked);
                    });
    
                    if (isAnswerSelected(partIndex, groupIndex, questionIndex, answerIndex)) {
                        checkbox.checked = true;
                    }
    
                    questionElement.appendChild(answerOption);
                });
    
                currentQuestionNumber += answerChoiceCount;
            }
    
            // Completion questions
            if (group.type_group_question === "completion") {
                let questionContent = question.question;
                const correctAnswers = question.box_answers.map(box => box.answer || "Not available");
                const completionInputIds = [];
    
                question.box_answers.forEach((boxAnswer, boxIndex) => {
                    const completionNumber = currentQuestionNumber + boxIndex;
                    const inputElementHtml = `<input type="text" id="answer-input-${completionNumber}" class="answer-input" name="question-${completionNumber}" />`;
                    questionContent = questionContent.replace('<input>', inputElementHtml);
                    completionInputIds.push(`answer-input-${completionNumber}`);
                });
    
                questionElement.innerHTML = `
                    <p>${questionContent}</p>
                    <p><b class ="correct-ans">Correct Answer:</b> ${correctAnswers.join(', ')}</p>
                `;
    
    
                currentQuestionNumber += question.box_answers.length;
            }
    
            // Multiple-choice questions
            if (group.type_group_question === "multiple-choice") {
                const correctAnswer = question.answers.find(answer => answer[1] === true)?.[0] || "Not available";
    
                questionElement.innerHTML += `
                    <p id="question-id-${currentQuestionNumber}"><b>${currentQuestionNumber}.</b> ${question.question}</p>
                `;
    
                question.answers.forEach((answer, answerIndex) => {
                    const answerElement = document.createElement('label');
                    const inputId = `${currentQuestionNumber}-${answerIndex + 1}`;
    
                    answerElement.innerHTML = `
                        <input type="radio" name="question-${currentQuestionNumber}" value="${answer[0]}" id="answer-input-${inputId}">
                        ${answer[0]}
                    `;
    
                    answerElement.querySelector('input').addEventListener('change', (event) => {
                        saveUserAnswer(partIndex, groupIndex, questionIndex, answerIndex, event.target.value);
                    });
    
                    if (isAnswerSelected(partIndex, groupIndex, questionIndex, answerIndex)) {
                        answerElement.querySelector('input').checked = true;
                    }
    
                    questionElement.appendChild(answerElement);
                });
                questionElement.innerHTML += `
                <p><b class ="correct-ans">Correct Answer:</b> ${correctAnswer}</p>
            `;
                currentQuestionNumber++;
            }
    
            groupContainer.appendChild(questionElement);
        });
    
        questionsContainer.appendChild(groupContainer);
    });
    
    document.getElementById("content").style.display = "block";
}

// Function to calculate the starting question number for a part
function getStartingQuestionNumber(partIndex) {
    let startQuestion = 1; // Start with question 1
    for (let i = 0; i < partIndex; i++) {
        startQuestion += parseInt(quizData.part[i].number_question_of_this_part);
    }
    return startQuestion;
}

// Function to calculate question range for a part
function getQuestionRange(partIndex) {
    const startQuestion = getStartingQuestionNumber(partIndex);
    const endQuestion = startQuestion + parseInt(quizData.part[partIndex].number_question_of_this_part) - 1;
    return `${startQuestion} - ${endQuestion}`;
}


// Navigation buttons
document.getElementById('prev-btn').addEventListener('click', () => {
    if (currentPartIndex > 0) {
        currentPartIndex--;
        loadPart(currentPartIndex);
    }
});

document.getElementById('next-btn').addEventListener('click', () => {
    if (currentPartIndex < quizData.part.length - 1) {
        currentPartIndex++;
        loadPart(currentPartIndex);
    }
});
// Load the part buttons dynamically
const partNavigation = document.getElementById('part-navigation');
quizData.part.forEach((part, index) => {
    const button = document.createElement('button');
    button.innerText = `Part ${part.part_number}`;
    button.id = 'part-navigation-button';
    button.addEventListener('click', () => {
        currentPartIndex = index;
        loadPart(currentPartIndex);
    });
    partNavigation.appendChild(button);
});


let userAnswers = {}; // Object to store user answers

// Save the user's answer selection for multiple-choice and multi-select
function saveUserAnswer(partIndex, groupIndex, questionIndex, answerIndex, value) {
    // Ensure we save the most recent answer chosen by the user
    if (!userAnswers[partIndex]) {
        userAnswers[partIndex] = [];
    }
    if (!userAnswers[partIndex][groupIndex]) {
        userAnswers[partIndex][groupIndex] = [];
    }
    if (!userAnswers[partIndex][groupIndex][questionIndex]) {
        userAnswers[partIndex][groupIndex][questionIndex] = [];
    }

    // Save the answer, ensuring only the selected one is marked as true
    userAnswers[partIndex][groupIndex][questionIndex] = answerIndex;
    console.log('Answer saved:', userAnswers);

    // For multi-select, toggle answer in array; for single-select, save the answer directly
    if (Array.isArray(userAnswers[partIndex][groupIndex][questionIndex])) {
        const index = userAnswers[partIndex][groupIndex][questionIndex].indexOf(answerIndex);
        if (value) {
            if (index === -1) {
                userAnswers[partIndex][groupIndex][questionIndex].push(answerIndex);
            }
        } else {
            if (index !== -1) {
                userAnswers[partIndex][groupIndex][questionIndex].splice(index, 1);
            }
        }
    } else {
        userAnswers[partIndex][groupIndex][questionIndex] = answerIndex;
    }
}

// Save the user's answer for completion questions
function saveCompletionAnswer(partIndex, groupIndex, questionIndex, boxIndex, value) {
    if (!userAnswers[partIndex]) {
        userAnswers[partIndex] = {};
    }
    if (!userAnswers[partIndex][groupIndex]) {
        userAnswers[partIndex][groupIndex] = {};
    }
    if (!userAnswers[partIndex][groupIndex][questionIndex]) {
        userAnswers[partIndex][groupIndex][questionIndex] = {};
    }

    userAnswers[partIndex][groupIndex][questionIndex][boxIndex] = value; // Store the answer value
}

// Check if an answer is already selected
function isAnswerSelected(partIndex, groupIndex, questionIndex, answerIndex) {
    const selected = userAnswers?.[partIndex]?.[groupIndex]?.[questionIndex];
    return userAnswers[partIndex]?.[groupIndex]?.[questionIndex] === answerIndex;

}
let timerInterval; // Declare timer interval globally

let startTime; // Biến để lưu thời gian bắt đầu quiz

function startTimer(duration) {
    clearInterval(timerInterval); // Clear any existing timer
    startTime = Date.now(); // Lưu lại thời gian bắt đầu
    let timer = duration, minutes, seconds;
    const timerDisplay = document.getElementById('timer');

    timerInterval = setInterval(function () {
        minutes = parseInt(timer / 60, 10);
        seconds = parseInt(timer % 60, 10);

        seconds = seconds < 10 ? "0" + seconds : seconds;
        timerDisplay.textContent = minutes + ":" + seconds;

        if (--timer < 0) {
            clearInterval(timerInterval);
            if (!isSubmitted) {
                isSubmitted = true;
                for (let i = 0; i < quizData.part.length; i++) {
                    logUserAnswers(i); // Log answers for all parts
                }
                fulltestResult();
                ResultInput();
            }
        }
    }, 1000);
}


// Khởi tạo các biến toàn cục để theo dõi tổng số câu đúng, sai và tổng số câu hỏi cho cả bài kiểm tra
let totalCorrectAnswers = 0;
let totalIncorrectAnswers = 0;
let totalQuestionsCount = 0;
function logUserAnswers(partIndex) {
 
    const part = quizData.part[partIndex];
    
    // Initialize variables for correct and incorrect answers
    let correctCount = 0;
    let incorrectCount = 0;
    let totalQuestions = 0; // Total question count

    // Initialize the current question number for this part
    let currentQuestionNumber = getStartingQuestionNumber(partIndex);

    part.group_question.forEach((group, groupIndex) => {
        group.questions.forEach((question, questionIndex) => {
            let questionNumber;
            let userAnswer;
            let correctAnswer; // Variable for correct answer

            // Handle multi-select questions
            if (group.type_group_question === "multi-select") {
                const answerChoiceCount = parseInt(question.number_answer_choice) || 1;
                questionNumber = `${currentQuestionNumber}-${currentQuestionNumber + answerChoiceCount - 1}`;
                const selectedAnswers = [];
                const correctAnswers = [];

                question.answers.forEach((answer, answerIndex) => {
                    // Check if this answer is selected by the user
                    if (isAnswerSelected(partIndex, groupIndex, questionIndex, answerIndex)) {
                        selectedAnswers.push(answer[0]);
                    }
                    // Check if this answer is marked as correct
                    if (answer[1] === true) {
                        correctAnswers.push(answer[0]);
                    }
                });

                userAnswer = selectedAnswers.length > 0 ? selectedAnswers.join(', ') : "Not answered";
                correctAnswer = correctAnswers.length > 0 ? correctAnswers.join(', ') : "Not available";

                // Compare userAnswer and correctAnswer, case-insensitive
                if (userAnswer.toLowerCase() === correctAnswer.toLowerCase()) {
                    correctCount += answerChoiceCount; // Count as multiple questions
                } else {
                    incorrectCount += answerChoiceCount;
                }

                console.log(`Question ${questionNumber}: User Answer: ${userAnswer}, Correct Answer: ${correctAnswer}`);
                userAnswerdiv.innerHTML += `Question ${questionNumber}: User Answer: ${userAnswer}, Correct Answer: ${correctAnswer}<br>`;

                currentQuestionNumber += answerChoiceCount; // Increment the question number
                totalQuestions += answerChoiceCount; // Count total number of questions
            }

            // Handle completion questions
            else if (group.type_group_question === "completion") {
                question.box_answers.forEach((boxAnswer, boxIndex) => {
                    const completionNumber = currentQuestionNumber + boxIndex; // This matches how loadPart handles completion inputs
                    const savedAnswer = userAnswers?.[partIndex]?.[groupIndex]?.[questionIndex]?.[boxIndex];

                    userAnswer = savedAnswer ? savedAnswer : "Not answered";
                    correctAnswer = boxAnswer.answer ? boxAnswer.answer : "Not available"; // Assume correct answer is in `boxAnswer.answer`

                    // Compare userAnswer and correctAnswer, case-insensitive
                    if (userAnswer.toLowerCase() === correctAnswer.toLowerCase()) {
                        correctCount++;
                    } else {
                        incorrectCount++;
                    }

                    console.log(`Question ${completionNumber}: User Answer: ${userAnswer}, Correct Answer: ${correctAnswer}`);
                    userAnswerdiv.innerHTML += `Question ${completionNumber}: User Answer: ${userAnswer}, Correct Answer: ${correctAnswer}<br>`;
                });

                currentQuestionNumber += question.box_answers.length; // Increment by the number of boxes
                totalQuestions += question.box_answers.length; // Count total number of completion questions
            }

            // Handle multiple-choice questions
            else if (group.type_group_question === "multiple-choice") {
                questionNumber = `${currentQuestionNumber}`;
                const savedAnswerIndex = question.answers.findIndex((answer, answerIndex) => isAnswerSelected(partIndex, groupIndex, questionIndex, answerIndex));
                userAnswer = savedAnswerIndex !== -1 ? question.answers[savedAnswerIndex][0] : "Not answered";
                
                const correctAnswerIndex = question.answers.findIndex(answer => answer[1] === true); // Find the correct answer
                correctAnswer = correctAnswerIndex !== -1 ? question.answers[correctAnswerIndex][0] : "Not available";

                // Compare userAnswer and correctAnswer, case-insensitive
                if (userAnswer.toLowerCase() === correctAnswer.toLowerCase()) {
                    correctCount++;
                } else {
                    incorrectCount++;
                }

                console.log(`Question ${questionNumber}: User Answer: ${userAnswer}, Correct Answer: ${correctAnswer}`);
                userAnswerdiv.innerHTML += `Question ${questionNumber}: User Answer: ${userAnswer}, Correct Answer: ${correctAnswer}<br>`;

                currentQuestionNumber++; // Increment the question number for the next question
                totalQuestions++; // Count as a single question
            }
        });
    });
     // Cộng dồn kết quả cho cả bài kiểm tra
     totalCorrectAnswers += correctCount;
     totalIncorrectAnswers += incorrectCount;
     totalQuestionsCount += totalQuestions;

    // Log the number of correct and incorrect answers
    console.log(`Total correct answers: ${correctCount}`);
    console.log(`Total incorrect answers: ${incorrectCount}`);
    console.log(`Total questions: ${totalQuestions}`);

    userAnswerdiv.innerHTML += `<br>Total correct answers: ${correctCount}<br>`;
    userAnswerdiv.innerHTML += `Total incorrect answers: ${incorrectCount}<br>`;
    userAnswerdiv.innerHTML += `Total questions: ${totalQuestions}<br>`;

    console.log(`Overall Total correct answers: ${totalCorrectAnswers}`);
    console.log(`Overall Total incorrect answers: ${totalIncorrectAnswers}`);
    console.log(`Overall Total questions: ${totalQuestionsCount}`);

    
}

let ieltsBandScore;
function fulltestResult(){
    // In kết quả cuối cùng vào HTML nếu cần
    const userAnswerdiv = document.getElementById('useranswerdiv');
    userAnswerdiv.innerHTML += `<br><strong>Overall Total correct answers: ${totalCorrectAnswers}</strong><br>`;
    userAnswerdiv.innerHTML += `<strong>Overall Total incorrect answers: ${totalIncorrectAnswers}</strong><br>`;
    userAnswerdiv.innerHTML += `<strong>Overall Total questions: ${totalQuestionsCount}</strong><br>`;

    if(totalCorrectAnswers  == 3){
        ieltsBandScore = 2;
    }
    else if(totalCorrectAnswers  >= 4 && totalCorrectAnswers  <= 5){
        ieltsBandScore = 2.5;
    }
    else if(totalCorrectAnswers  >= 6 && totalCorrectAnswers  <= 7){
        ieltsBandScore = 3.0;
    }
    else if(totalCorrectAnswers  >= 8 && totalCorrectAnswers  <= 9){
        ieltsBandScore = 3.5;
    }
    else if(totalCorrectAnswers  >= 10 && totalCorrectAnswers  <= 12){
        ieltsBandScore = 4.0;
    }
    else if(totalCorrectAnswers  >= 13 && totalCorrectAnswers  <= 14){
        ieltsBandScore = 4.5;
    }
    else if(totalCorrectAnswers  >= 15 && totalCorrectAnswers  <= 18){
        ieltsBandScore = 5.0;
    }
    else if(totalCorrectAnswers  >= 19 && totalCorrectAnswers  <= 22){
        ieltsBandScore = 5.5;
    }
    else if(totalCorrectAnswers  >= 23 && totalCorrectAnswers  <= 26){
        ieltsBandScore = 6.0;
    }
    else if(totalCorrectAnswers  >= 29 && totalCorrectAnswers  <= 29){
        ieltsBandScore = 6.5;
    }
    else if(totalCorrectAnswers  >= 30 && totalCorrectAnswers  <= 32){
        ieltsBandScore = 7.0;
    }
    else if(totalCorrectAnswers  >= 33 && totalCorrectAnswers  <= 34){
        ieltsBandScore = 7.5;
    }
    else if(totalCorrectAnswers  >= 35 && totalCorrectAnswers  <= 36){
        ieltsBandScore = 8.0;
    }
    else if(totalCorrectAnswers  >= 37 && totalCorrectAnswers  <= 38){
        ieltsBandScore = 8.5;
    }
    else if(totalCorrectAnswers  >= 39 && totalCorrectAnswers  <= 40){
        ieltsBandScore = 9.0;
    }
    else{
        ieltsBandScore = 0;
    }
    userAnswerdiv.innerHTML += `<h3>Overall band: ${ieltsBandScore}</h3>`

}
// Khai báo biến lưu trữ đáp án đúng
let correctAnswersData = {};

function consoleAns(partIndex) {
    const part = quizData.part[partIndex];
    let currentQuestionNumber = getStartingQuestionNumber(partIndex);

    part.group_question.forEach((group, groupIndex) => {
        group.questions.forEach((question, questionIndex) => {
            let questionNumber;
            let correctAnswer; // Biến lưu đáp án đúng

            // Xử lý câu hỏi nhiều lựa chọn đúng (multi-select)
            if (group.type_group_question === "multi-select") {
                const answerChoiceCount = parseInt(question.number_answer_choice) || 1;
                questionNumber = `${currentQuestionNumber}-${currentQuestionNumber + answerChoiceCount - 1}`;
                const correctAnswers = [];

                question.answers.forEach((answer) => {
                    if (answer[1] === true) {
                        correctAnswers.push(answer[0]);
                    }
                });

                correctAnswer = correctAnswers.length > 0 ? correctAnswers.join(', ') : "Not available";
                console.log(`Question: ${questionNumber}, Part: ${partIndex + 1}, Correct Answer: ${correctAnswer}`);

                // Lưu đáp án đúng
                correctAnswersData[questionNumber] = {
                    part: partIndex + 1,
                    correctAnswer: correctAnswer
                };

                currentQuestionNumber += answerChoiceCount; // Tăng số thứ tự câu hỏi
            }

            // Xử lý câu hỏi hoàn thành (completion)
            else if (group.type_group_question === "completion") {
                question.box_answers.forEach((boxAnswer, boxIndex) => {
                    const completionNumber = currentQuestionNumber + boxIndex; // Số thứ tự cho từng ô trống
                    correctAnswer = boxAnswer.answer ? boxAnswer.answer : "Not available";

                    console.log(`Question: ${completionNumber}, Part: ${partIndex + 1}, Correct Answer: ${correctAnswer}`);

                    // Lưu đáp án đúng
                    correctAnswersData[completionNumber] = {
                        part: partIndex + 1,
                        correctAnswer: correctAnswer
                    };
                });

                currentQuestionNumber += question.box_answers.length; // Tăng số thứ tự theo số ô trống
            }

            // Xử lý câu hỏi trắc nghiệm (multiple-choice)
            else if (group.type_group_question === "multiple-choice") {
                questionNumber = `${currentQuestionNumber}`;
                const correctAnswerIndex = question.answers.findIndex(answer => answer[1] === true); // Tìm đáp án đúng
                correctAnswer = correctAnswerIndex !== -1 ? question.answers[correctAnswerIndex][0] : "Not available";

                console.log(`Question: ${questionNumber}, Part: ${partIndex + 1}, Correct Answer: ${correctAnswer}`);

                // Lưu đáp án đúng
                correctAnswersData[questionNumber] = {
                    part: partIndex + 1,
                    correctAnswer: correctAnswer
                };

                currentQuestionNumber++; // Tăng số thứ tự câu hỏi
            }
        });
    });
}

function updateCorrectAnswers() {
    Object.keys(correctAnswersData).forEach(questionNumber => {
        const correctAnswer = correctAnswersData[questionNumber].correctAnswer;
        const correctAnswerCell = document.getElementById(`correct-answer-${questionNumber}`);
        if (correctAnswerCell) {
            correctAnswerCell.textContent = correctAnswer;
        }
    });
}

function main(){
    console.log("Passed Main");
    
    for(let i = 0; i < quizData.part.length; i ++){
        consoleAns(i);
        console.log(quizData.part[1].duration);
    }
    updateCorrectAnswers();
    loadPart(currentPartIndex);
 
}

