function submitButton() {
    submitTest = true;
    console.log(submitTest)
    CheckSubmit()
    if (isCountdownRunning) {
        clearInterval(countdownInterval);
        isCountdownRunning = false;
    }
    ChangeQuestion(1);
    document.getElementById("submit-button").style.display = 'none';
    var explain_zone = document.getElementsByClassName("explain-zone")

    var timeUsed = quizData.duration - countdownValue;
    var formattedTimeUsed = formatTime(timeUsed);

    for (var i = 0; i < explain_zone.length; i++)
        explain_zone[i].style.display = 'block';

    var percent = 0;
    var number_of_correct_answer = 0;
    var percentage_1_sentence = 100 / quizData.number_questions;

    document.getElementById("quiz-container").style.pointerEvents = "none";
    let correctAnswerDiv = document.getElementById("correctanswerdiv");



    // Loop through each question
    for (var z = 1; z <= quizData.number_questions; z++) {
        var id_temp = '[id^="question-' + z + '-"]';
        var elements = document.querySelectorAll(id_temp);
    
        // Ensure elements[0] is defined before accessing its properties
        if (elements.length > 0 && elements[0].type === 'text') {
            var userAnswer = document.getElementById('question-' + z + '-input').value.trim();
            var correctAnswer = quizData.questions[z - 1].answer.map(a => a.toLowerCase()); // Convert all correct answers to lowercase
            console.log(`Question ${z} (Correct Answer) : ${correctAnswer.join(', ')}`);
            correctAnswerDiv.innerHTML += `Question ${z} (Correct Answer - Input) : ${correctAnswer.join(', ')}`;
            
            if (correctAnswer.includes(userAnswer.toLowerCase())) {
                percent += percentage_1_sentence;
                number_of_correct_answer++;
                document.getElementById('question-' + z + '-input').classList.add('true');
            } else {
                document.getElementById('question-' + z + '-input').classList.add('false');
                var explanationZone = document.querySelector('#question-' + z + '-input').closest('.questions').querySelector('.explain-zone');
                explanationZone.innerHTML = '<p><b>Correct Answer: </b>' + correctAnswer + '</p>' + explanationZone.innerHTML;
            }
        } else {

            // Handle multiple-choice questions
             if (elements.length > 0 && elements[0].type === 'radio') {
                var correctOptions = [];
                var isHaveCheck = false;

                for (var i = 0; i < elements.length; i++) {
                    if (elements[i].parentElement.classList.contains('true')) {
                        correctOptions.push(elements[i].parentElement.textContent.trim().charAt(0)); // Get option letter (A, B, C, D)
                    }

                    if (elements[i].checked) {
                        isHaveCheck = true;
                        if (elements[i].parentElement.classList.contains('true')) {
                            elements[i].parentElement.classList.remove('neutral');
                            percent += percentage_1_sentence;
                            number_of_correct_answer++;
                        } else {
                            elements[i].parentElement.classList.remove('neutral');
                        }
                    }
                }

                console.log(`Question ${z}: Correct Answer(s) - ${correctOptions.join(', ')}`);
                correctAnswerDiv.innerHTML += `Question ${z} (Correct Answer - Multiple choice) : ${correctOptions.join(', ')}`;

                if (!isHaveCheck) {
                    for (var i = 0; i < elements.length; i++)
                        elements[i].parentElement.classList.remove('neutral');
                }

            } 
            
            
            else if (elements.length > 0 && elements[0].type === 'checkbox') {
              // Handle multi-select (checkbox) questions
          var correctOptions = [];
          var selectedCorrectly = true;

          for (var i = 0; i < elements.length; i++) {
              var label = String.fromCharCode(65 + i); // Convert index to alphabetical label (A, B, C, ...)

              if (elements[i].parentElement.classList.contains('true')) {
                  correctOptions.push(label);
              }

              if (elements[i].checked && !elements[i].parentElement.classList.contains('true')) {
                  selectedCorrectly = false; // User selected an incorrect answer
              } else if (!elements[i].checked && elements[i].parentElement.classList.contains('true')) {
                  selectedCorrectly = false; // User missed selecting a correct answer
              }
          }

          if (selectedCorrectly) {
              percent += percentage_1_sentence;
              number_of_correct_answer++;
          }

          console.log(`Question ${z}: Correct Answer(s) - ${correctOptions.join(', ')}`);
          correctAnswerDiv.innerHTML += `Question ${z} (Correct Answer - multiselect) : ${correctOptions.join(', ')}`;

          // Find the correct .explain-zone element and update it
          var explanationZone = document.querySelector('#question-' + z + '-input')?.closest('.questions')?.querySelector('.explain-zone');
          if (explanationZone) {
              explanationZone.innerHTML = '<p><b>Correct Answer: </b>' + correctOptions.join(', ') + '</p>' + explanationZone.innerHTML;
          }

          for (var i = 0; i < elements.length; i++) {
              elements[i].parentElement.classList.remove('neutral');
          }


            }
        }
    }

    let test_type_sat = document.getElementById("test_type").innerText;
    percent = formatNumber(percent);

    let full_test_res = (number_of_correct_answer/quizData.number_questions)*1600;
    console.log(`Test type hiện tai: ${test_type_sat}`);
    string_final = "Your score: " + percent + "%. ";
    string_final_out_of_10 = `${number_of_correct_answer}/${quizData.number_questions}`;

    if(test_type_sat == "Practice"){
        string_final =  percent + "%. ";
    }
    else if(test_type_sat == "Full Test"){
        string_final = `${full_test_res}`;

    }
    time_do_full_test = formattedTimeUsed;

    

    document.getElementById("final-result").innerHTML = string_final;



    document.getElementById("final-review-result").innerHTML = string_final_out_of_10;
    document.getElementById("final-review-result-table").innerHTML = string_final_out_of_10;
    document.getElementById("final-result-table").innerHTML = string_final;

    document.getElementById("time-result").innerHTML = time_do_full_test;
    document.getElementById("time-result-table").innerHTML = time_do_full_test;
    document.getElementById("header-table-test-result").style.display = 'block';

    document.getElementById('final-result').scrollIntoView({
        behavior: 'smooth'
});
}



 function formatNumber(number)
  {
            let roundedNumber = parseFloat(number).toFixed(2);
            let resultString = roundedNumber.toString();
            resultString = resultString.replace(/\.?0+$/, '');
            return resultString;
 }

          



 function ResultInput() {
    // Copy the content to the form fields
    var contentToCopy1 = document.getElementById("final-result").textContent;
    var contentToCopy2 = document.getElementById("date").textContent;
    var contentToCopy3 = document.getElementById("time-result").textContent;
    var contentToCopy4 = document.getElementById("title").textContent;
    var contentToCopy6 = document.getElementById("id_test").textContent;
    var contentToCopy7 = document.getElementById("correctanswerdiv").textContent;
    var contentToCopy8 = document.getElementById("useranswerdiv").textContent;


    document.getElementById("resulttest").value = contentToCopy1;
    document.getElementById("dateform").value = contentToCopy2;
    document.getElementById("timedotest").value = contentToCopy3;
    document.getElementById("testname").value = contentToCopy4;
    document.getElementById("idtest").value = contentToCopy6;
    document.getElementById("correctanswer").value = contentToCopy7;
    document.getElementById("useranswer").value = contentToCopy8;

    
    // Add a delay before submitting the form
setTimeout(function() {
// Automatically submit the form
jQuery('#frmContactUs').submit();
}, 5000); // 5000 milliseconds = 5 seconds
}
          
          