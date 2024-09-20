window.myApp = window.myApp || {};
window.myApp.sumLexicalResouce1 = window.myApp.sumLexicalResouce1 || {};


async function Check_Lexical_Resource_Part_1(){
    console.log("Start Check Lexical Resource")

    let lexical_resource_part_1_comment = '';
    for(let i = 0;i < quizData.questions.length; i++){

        let answer = answers['answer' + (i + 1)] || "";

        let question = quizData.questions[i].question;
            
        try {
            const response = await fetch('http://127.0.0.1:5000/check_answer', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ question, answer }),
           
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            console.log(data.result)

            lexical_resource_part_1_comment = `Question: ${question}. Answer: ${answer}. Relevant to main idea: ${data.result}`

           
    


        } catch (error) {
            console.error('Error during fetch:', error);
        }









        window.myApp.sumLexicalResouce1[i] = {
            //fluency_and_coherence_part_1_1,
            //totalLinkingWords: linkingWordsInfo.totalLinkingWords,
            //uniqueLinkingWordsCount: linkingWordsInfo.uniqueLinkingWordsCount,
            lexical_resource_part_1_comment,

        };

        sessionStorage.setItem(`lexical_resouce_part_1_for_question${i}`, JSON.stringify(window.myApp.sumLexicalResouce1[i]));

        

    }
    console.log("End Check Lexical Resource")

}