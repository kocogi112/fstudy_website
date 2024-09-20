

async function GetSummaryPart1(i) {
    console.log(`Số câu hỏi: ${number_of_question}, Số câu part 1 ${part1Count}, Số câu part 2: ${part2Count}, Số câu part 3: ${part3Count}`)
    console.log("Start Summary - Show Result");
    document.getElementById("speaking-part").style.display = "none";

    let resultColumn = document.getElementById('resultColumn');
    
    let answer = answers['answer' + (i + 1)] || "";
    let current_question = quizData.questions[i].question;
    let counter = counters['counter' + (i + 1)] || "";

    let reanswer = reanswers['reanswer' + (i + 1)] || "";
    let result = '';
    
    let Timeused = counter;
    let wordCount = answer.split(/\s+/).length;
    let averageSpeakRate = wordCount / Timeused;
    
    let lexical_resource_comment_part1 =``;
    let grammatical_range_and_accuracy_comment_part1 = ``;
    let fluency_and_coherence_comment_part1 = ``;
    let pronunciaton_comment_part1 = ``;

    let lexical_resource_comment_part2 =``;
    let grammatical_range_and_accuracy_comment_part2 = ``;
    let fluency_and_coherence_comment_part2 = ``;
    let pronunciaton_comment_part2 = ``;

    let lexical_resource_comment_part3 =``;
    let grammatical_range_and_accuracy_comment_part3 = ``;
    let fluency_and_coherence_comment_part3 = ``;
    let pronunciaton_comment_part3 = ``;


    // Initialize highlightedAnswer outside the try block
    let highlightedAnswer = answer;

    try {
        const response = await fetch('https://fstudy-speaking-ielts-check.onrender.com/check-speaking', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ question: current_question, answer: answer, part: quizData.questions[i].part,sample: quizData.questions[i].sample, averageSpeakRate:averageSpeakRate, 
                time:Timeused, wordCount:  wordCount, number_of_question:number_of_question, number_of_part_1: part1Count, number_of_part_2: part2Count, number_of_part_3: part3Count }),
        });

        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        const data = await response.json();
        /*console.log('Result:', data.result);
        console.log('Synonym Groups:', data.synonym_groups);
        console.log('Grammar Result:', data.grammar_result);
        console.log('Grammar Result GPT V2:', data.grammar_result_gpt);*/
        console.log(data)
        fluency_and_coherence_comment_part1 = data.checkFluencyAndCoherenceSend.checkFluencyAndCoherenceCommentPart1;
        lexical_resource_comment_part1 = data.checkLexicalResourceSend.checkLexicalResourceCommentPart1;
        fluency_and_coherence_comment_part2 = data.checkFluencyAndCoherenceSend.checkFluencyAndCoherenceCommentPart2;
        lexical_resource_comment_part2 = data.checkLexicalResourceSend.checkLexicalResourceCommentPart2;
        fluency_and_coherence_comment_part3 = data.checkFluencyAndCoherenceSend.checkFluencyAndCoherenceCommentPart3;
        lexical_resource_comment_part3 = data.checkLexicalResourceSend.checkLexicalResourceCommentPart3;

        grammatical_range_and_accuracy_comment_part1 = data.checkGrammarticalRangeAndAccuracySend.checkGrammarticalRangeAndAccuracyCommentPart1;
        grammatical_range_and_accuracy_comment_part2 = data.checkGrammarticalRangeAndAccuracySend.checkGrammarticalRangeAndAccuracyCommentPart2;
        grammatical_range_and_accuracy_comment_part3 = data.checkGrammarticalRangeAndAccuracySend.checkGrammarticalRangeAndAccuracyCommentPart3;





        fluency_and_coherence_all_point_part1 += data.checkFluencyAndCoherenceSend.checkFluencyAndCoherencePointPart1;
        lexical_resource_all_point_part1 += data.checkLexicalResourceSend.checkLexicalResourcePointPart1;
        fluency_and_coherence_all_point_part2 += data.checkFluencyAndCoherenceSend.checkFluencyAndCoherencePointPart2;
        lexical_resource_all_point_part2 += data.checkLexicalResourceSend.checkLexicalResourcePointPart2;
        fluency_and_coherence_all_point_part3 += data.checkFluencyAndCoherenceSend.checkFluencyAndCoherencePointPart3;
        lexical_resource_all_point_part3 += data.checkLexicalResourceSend.checkLexicalResourcePointPart3;
        
        grammatical_range_and_accuracy_all_point_part1 += data. checkGrammarticalRangeAndAccuracySend.checkGrammarticalRangeAndAccuracyPointPart1;
        grammatical_range_and_accuracy_all_point_part2 += data. checkGrammarticalRangeAndAccuracySend.checkGrammarticalRangeAndAccuracyPointPart2;
        grammatical_range_and_accuracy_all_point_part3 += data. checkGrammarticalRangeAndAccuracySend.checkGrammarticalRangeAndAccuracyPointPart3;

        //pronunciation_all_point += 

        /*if (data.checkGrammarSpelling && data.checkGrammarSpelling.suggestions) {
            // Iterate through each suggestion in the suggestions array
            data.checkGrammarSpelling.suggestions.forEach(suggestion => {
            console.log('Message:', suggestion.message); // Display message
            console.log('Wrong Word:', suggestion.wrongWord); // Display wrong word
        
            if (suggestion.replacements && suggestion.replacements.length > 0) {
                console.log('Replacements:', suggestion.replacements); // Display replacements if any
            } else {
                console.log('No replacements available for this suggestion.');
            }
            });
        } else {
            console.log('Suggestions not found in data.');
        }*/
        // Highlight grammar errors in red
        data.checkGrammarSpelling.suggestions.forEach(error => {
            highlightedAnswer = highlightedAnswer.replace(
                error.wrongWord,
                `<span style="color:red">${error.wrongWord}</span>`
            );
        });

      /*  data.grammar_result.grammar_errors.forEach(error => {
            highlightedAnswer = highlightedAnswer.replace(
                error.error,
                `<span style="color:red">${error.error}</span>`
            );
        });*/

        // Highlight synonyms in blue
       /* data.synonym_groups.forEach(group => {
            group.forEach(word => {
                const regex = new RegExp(`\\b${word}\\b`, 'gi');
                highlightedAnswer = highlightedAnswer.replace(
                    regex,
                    `<span style="color:blue">${word}</span>`
                );
            });
        });*/
        getOverallBand();
        addSampletoTab();

    } catch (error) {
        console.error('There was a problem with the fetch operation:', error);
    }

    

    
   




    resultColumn.innerHTML += `<p style = "color:red"><strong>Question ${i + 1}  (Part: ${quizData.questions[i].part}):</strong> ${current_question}</p>`;
    resultColumn.innerHTML += `<p><strong>Your Answer:</strong> ${highlightedAnswer}</p>`;

    if(part == 1){
        resultColumn.innerHTML += `<p><strong>Fluency and Coherence:</strong> ${fluency_and_coherence_comment_part1} </p>`;
        resultColumn.innerHTML += `<p><strong>Lexical Resource:</strong> ${lexical_resource_comment_part1}  </p>`;
        resultColumn.innerHTML += `<p><strong>Grammatical range and accuracy:</strong> ${grammatical_range_and_accuracy_comment_part1}</p>`;
        resultColumn.innerHTML += `<p><strong>Pronounciation: </strong> ${pronunciaton_comment_part1} </p>`;
    }
    else if(part == 2){
        resultColumn.innerHTML += `<p><strong>Fluency and Coherence (part 2):</strong> ${fluency_and_coherence_comment_part2} </p>`;
        resultColumn.innerHTML += `<p><strong>Lexical Resource (part 2):</strong> ${lexical_resource_comment_part2}  </p>`;
        resultColumn.innerHTML += `<p><strong>Grammatical range and accuracy (part 2):</strong> ${grammatical_range_and_accuracy_comment_part2}</p>`;
        resultColumn.innerHTML += `<p><strong>Pronounciation (part 2): </strong> ${pronunciaton_comment_part2} </p>`;
    }
    else if(part == 3){
        resultColumn.innerHTML += `<p><strong>Fluency and Coherence (part 3):</strong> ${fluency_and_coherence_comment_part3} </p>`;
        resultColumn.innerHTML += `<p><strong>Lexical Resource (part 3):</strong> ${lexical_resource_comment_part3}  </p>`;
        resultColumn.innerHTML += `<p><strong>Grammatical range and accuracy (part 3):</strong> ${grammatical_range_and_accuracy_comment_part3}</p>`;
        resultColumn.innerHTML += `<p><strong>Pronounciation (part 2): </strong> ${pronunciaton_comment_part3} </p>`;
    }

    //resultColumn.innerHTML += `<p><strong>Result:</strong> ${result}</p>`;
    resultColumn.innerHTML += `<p><strong>Time used:</strong> ${Timeused} second and <strong>Word Count: </strong>${wordCount}. Average Rate: ${averageSpeakRate} word per second</p>`;

    console.log("Done Summary - Show Result - DONE ALL");
}
