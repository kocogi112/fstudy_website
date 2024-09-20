window.myApp = window.myApp || {};
window.myApp.sumCounts = window.myApp.sumCounts || {};
// List of linking words to highlight
const linkingWords =["additionally", "also","moreover","furthermore","further",
    "then","besides",  "indeed", "regarding","whereas","conversely","in comparison",
    "by contrast","another view is", "alternatively","although","otherwise","instead","therefore",
    "accordingly","as a result of", "the consequence is","resulting from","consequently",
    "it can be seen","thus","hence","for this reason","owing to x","this sugguests that", 
    "it folloes that","otherwise","in that case","as a result of", "for instance","for example",
    "especially","particularly","notably","mainly", "eventually","overall", "in conclusion","and","or","so","but"];

let fluency_and_coherence_part_1;
let fluency_and_coherence_part_1_1 = 0; // cho word count nếu đủ
let fluency_and_coherence_part_1_2 = 0; // cho linking words, conjunction
let fluency_and_coherence_part_1_3 = 0;
let fluency_and_coherence_part_1_4 = 0;
let fluency_and_coherence_part_1_5 = 0;

    function Check_Fluency_And_Coherence_Part_1() {
        quizData.questions.forEach((question, i) => {
            let answer = answers['answer' + (i + 1)] || "";
    
            let wordCount = answer.split(/\s+/).length;
            let linkingWordsInfo = countLinkingWords(answer);
            let fluency_and_coherence_part_1_comment = '';
            if (wordCount < 5) {
                fluency_and_coherence_part_1_1 = 0;
                fluency_and_coherence_part_1_comment += 'Not enough length. '
            } else if (wordCount >= 5 && wordCount <= 7) {
                fluency_and_coherence_part_1_1 = 1;
                fluency_and_coherence_part_1_comment += 'You should speak more. '
            } else if (wordCount > 7) {
                fluency_and_coherence_part_1_1 = 2;
                fluency_and_coherence_part_1_comment += 'The length is perfect. '
            }


            if (linkingWordsInfo.totalLinkingWords == 0){
                fluency_and_coherence_part_1_2 = 0;
                fluency_and_coherence_part_1_comment += 'Need to use linking word. '
            }
            else if(linkingWordsInfo.totalLinkingWords == 1){
                fluency_and_coherence_part_1_2 = 0.75;
                fluency_and_coherence_part_1_comment += 'Try to use more linking word. '

            }
            else if(linkingWordsInfo.totalLinkingWords >= 2 && linkingWordsInfo.totalLinkingWords <= 4){
                fluency_and_coherence_part_1_2 = 1.5;
                fluency_and_coherence_part_1_comment += 'Normally, 5 linking words is enough. '

            }
            else if(linkingWordsInfo.totalLinkingWords == 5){
                fluency_and_coherence_part_1_2 = 2;
                fluency_and_coherence_part_1_comment += 'Perfect linking word length. '

            }
            else{
                fluency_and_coherence_part_1_2 = 1;
                fluency_and_coherence_part_1_comment += 'You should decrease linking words use !. '

            }


            
            fluency_and_coherence_part_1 = fluency_and_coherence_part_1_1 + fluency_and_coherence_part_1_2 + fluency_and_coherence_part_1_3 + fluency_and_coherence_part_1_4;
            
            fluency_and_coherence_part_1_comment += 
            `Comment: Number of linking words: ${linkingWordsInfo.totalLinkingWords}, unique: ${ linkingWordsInfo.uniqueLinkingWordsCount}. Score Detail: Length Point: ${fluency_and_coherence_part_1_1}.  Linking words point: ${fluency_and_coherence_part_1_2}. Final Score for this criteria: ${fluency_and_coherence_part_1}`
            
            
            
            window.myApp.sumCounts[i] = {
                //fluency_and_coherence_part_1_1,
                //totalLinkingWords: linkingWordsInfo.totalLinkingWords,
                //uniqueLinkingWordsCount: linkingWordsInfo.uniqueLinkingWordsCount,
                fluency_and_coherence_part_1_comment,
            };
    
            localStorage.setItem(`fluency_and_coherence_part_1_question_${i + 1}`, JSON.stringify(window.myApp.sumCounts[i]));
        });
    }
    
    function countLinkingWords(answer) {
        const words = answer.split(/\s+/);
        const linkingWordsCount = {};
        let totalLinkingWords = 0;
    
        words.forEach(word => {
            const lowerWord = word.toLowerCase();
            if (linkingWords.includes(lowerWord)) {
                if (!linkingWordsCount[lowerWord]) {
                    linkingWordsCount[lowerWord] = 0;
                }
                linkingWordsCount[lowerWord]++;
                totalLinkingWords++;
            }
        });
    
        const uniqueLinkingWordsCount = Object.keys(linkingWordsCount).length;
    
        return {
            totalLinkingWords,
            uniqueLinkingWordsCount
        };
    }