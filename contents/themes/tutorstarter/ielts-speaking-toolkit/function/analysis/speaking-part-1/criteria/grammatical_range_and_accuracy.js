window.myApp = window.myApp || {};
window.myApp.sumGrammaticalRangeAndAccuracy1 = window.myApp.sumGrammaticalRangeAndAccuracy1 || {};


async function Check_Grammatical_Range_And_Accuracy_Part_1(){
    console.log("Start Check Grammatical Range and Accuracy");

    let grammatical_range_and_accuracy_part_1 = 0;
    let grammatical_range_and_accuracy_part_1_comment ='';
    let grammar_error = 0;

    for(let i = 0; i < quizData.questions.length; i++){
        let answer = answers['answer' + (i + 1)] || "";
        let question = quizData.questions[i].question;

        try {
            const response = await fetch('http://127.0.0.1:5000/check_answer', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ question: question, answer: answer })
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            //console.log('Similarity Result:', data.result);
            console.log('Grammar and Spelling Errors:', data.grammar_result);
            grammar_error++;

        } catch (error) {
            console.error('Error during fetch:', error);
        }

        grammatical_range_and_accuracy_part_1_comment +=`Grammar error: ${grammar_error}`

        window.myApp.sumGrammaticalRangeAndAccuracy1[i] = {
            grammatical_range_and_accuracy_part_1_comment,
        };

        sessionStorage.setItem(`grammatical_range_and_accuracy_part_1_question${i}`, JSON.stringify(window.myApp.sumGrammaticalRangeAndAccuracy1[i]));
    }
    console.log("End Check Grammatical Range and Accuracy");
}
