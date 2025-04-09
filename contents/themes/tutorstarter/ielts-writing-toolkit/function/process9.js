let essayResponses = {}; // Lưu tất cả final_analysis theo ID câu hỏi
let essayAnswers = {};   // Lưu answer của user
let scoreOverallAndBand = {}; // Lưu điểm số tổng kết
async function processEssay(i) {
    let userEssay = document.getElementById(`question-${i}-input`).value;

    let countText = document.getElementById(`word-count-${i}`).textContent;
    let counts = countText.match(/\d+/g);
    let wordCount = counts ? parseInt(counts[0]) : 0;
    let sentenceCount = counts ? parseInt(counts[1]) : 0;
    let paragraphCount = counts ? parseInt(counts[2]) : 0;

    let sampleEssay = quizData.questions[i].sample_essay.replace(/<br>/g, '\n');
    let currentPart = quizData.questions[i].part;
    let currentQuestion = quizData.questions[i].question;
    let currentIDQuestion = quizData.questions[i].id_question;

    console.log(`Processing essay for Part: ${currentPart}`);

    const dataAns = {
        id_question: currentIDQuestion,
        part: currentPart,
        question: currentQuestion,
        wordCount: wordCount,
        sentenceCount: sentenceCount,
        paragraphCount: paragraphCount,
        answer: userEssay
    };

    try {
        let response = await fetch(`${siteUrl}/api/public/test/v1/ielts/writing/`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                question: currentQuestion,
                answer: userEssay,
                part: currentPart,
                sample: sampleEssay,
                idquestion: currentIDQuestion,
                type: quizData.questions[i].question_type,
                data: dataAns
            })
        });

        let data = await response.json();
        console.log(`Part ${currentPart} response:`, data);

        essayResponses[currentPart] = data;
        document.getElementById("user_band_score_and_suggestion").value = JSON.stringify(essayResponses, null, 2);

        if (data.answer) {
            essayAnswers[currentPart] = {
                answer: data.answer,
                dataAns: dataAns
            };
            console.log("Updated answers by part:", essayAnswers);
            document.getElementById("user_essay").value = JSON.stringify(essayAnswers);
        }

        // ✅ Chỉ gọi final result khi đã có đủ kết quả cho tất cả câu hỏi
        if (Object.keys(essayResponses).length === quizData.questions.length) {
            await processFinalResult();
        }

    } catch (error) {
        console.error('Error:', error);
    }
}
async function processFinalResult() {
    try {
        let responsefinal = await fetch(`${siteUrl}/api/public/test/v1/ielts/final_writing/`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ results: essayResponses })
        });

        finalResult = await responsefinal.json();
        console.log('Final result:', finalResult);

        if (finalResult) {
            document.getElementById("band-score-expand-form").value = JSON.stringify(finalResult, null, 2);

            if (finalResult.bands?.overallBand !== undefined) {
                document.getElementById("band-score-form").value = finalResult.bands.overallBand;
            }
        }

        ResultInput();

    } catch (error) {
        console.error(`Error getting final result:`, error);
    }
}


// Hàm làm tròn số về .0 hoặc .5
function roundToNearestHalf(score) {
    const floor = Math.floor(score); // Phần nguyên
    const decimal = score - floor; // Phần thập phân

    if (decimal < 0.25) {
        return floor; // Làm tròn xuống .0
    } else if (decimal >= 0.25 && decimal < 0.75) {
        return floor + 0.5; // Làm tròn lên .5
    } else {
        return floor + 1; // Làm tròn lên .0 của số nguyên tiếp theo
    }
}