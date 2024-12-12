/*const vocabList = [
    { vocab: "Faintly", explanation: "In a way that is not clear or strong.", vietnamese_meaning:"mở nhạt" },
    { vocab: "Persevere", explanation: "To persist in doing something despite difficulties.",vietnamese_meaning:"bảo toàn" },
    { vocab: "Eloquent", explanation: "Fluent or persuasive in speaking or writing." ,vietnamese_meaning:"có tài hùng biện"},
    { vocab: "abandon", explanation: "Give up something " ,vietnamese_meaning:"bỏ rơi"},
    { vocab: "antagonist", explanation: "one that contends with or opposes another " ,vietnamese_meaning:"chống đối"},

    { vocab: "baroque", explanation: "(used to describe European architecture, art and music of the 17th and early 18th centuries that has a grand and highly decorated style.", vietnamese_meaning:"cổ điển" },
    { vocab: "abuse", explanation: "(the use of something in a way that is wrong or harmful)",vietnamese_meaning:"lạm dụng" },
    { vocab: "congress", explanation: "a large formal meeting or series of meetings where representatives from different groups discuss ideas, make decisions, etc." ,vietnamese_meaning:"quốc hội"},
    { vocab: "contemplate", explanation: "(to think deeply about something for a long time)" ,vietnamese_meaning:"tận tâm"},
    { vocab: "contemporary", explanation: "belonging to the same time" ,vietnamese_meaning:"đồng thời"},

    
];*/
const quizType1Questions = vocabList.map(vocab => ({
    type: "quiz-type-1",
    vocab: vocab.vocab,
    vietnamese_meaning: vocab.vietnamese_meaning,
    explanation: vocab.explanation,
    example: vocab.example
}));

const quizType2Questions = vocabList
    .filter(vocab => vocabList.some(other => other.example.includes(vocab.vocab)))
    .map(vocab => ({
        type: "quiz-type-2",
        explanation: vocab.explanation,
        example: vocab.example.replace(new RegExp(`\\b${vocab.vocab}\\b`, "gi"), "______"),
        vocab: vocab.vocab
    }));

const allQuestions = [...quizType1Questions, ...quizType2Questions];
const totalQuestions = allQuestions.length;


let hintStep = 0; // Theo dõi gợi ý hiện tại

let currentIndex = 0;
const flashcard = document.getElementById("flashcard");
const vocabText = document.getElementById("vocabText");
const definitionText = document.getElementById("definitionText");
const explanationText = document.getElementById("explanationText");

const progress = document.getElementById("progress");
const audioButton = document.getElementById("audioButton");
const vocabInput = document.getElementById("vocabInput");
const checkButton = document.getElementById("check");
const resultMessage = document.getElementById("resultMessage");

const resultContainer = document.getElementById("resultContainer");
const correctCountElement = document.getElementById("correctCount");
const incorrectCountElement = document.getElementById("incorrectCount");
const skippedCountElement = document.getElementById("skippedCount");
const accuracyPercentageElement = document.getElementById("accuracyPercentage");
const completionTimeElement = document.getElementById("completionTime");
const statusElement = document.getElementById("status");
let correctAnswers = 0; // Số câu trả lời đúng

let correctCount = 0;
let incorrectCount = 0;
let skippedCount = 0;
let startTime = Date.now();
const questionStatus = document.getElementById("questionStatus");


const hintButton = document.getElementById("hintButton");
const hintMessage = document.getElementById("hintMessage");

hintButton.addEventListener("click", () => {
    const current = vocabList[currentIndex];
    if (hintStep === 0) {
        hintMessage.textContent = `Gợi ý 1: Từ này có ${current.vocab.length} chữ cái.`;
        hintButton.textContent = "Gợi ý thêm";
        hintStep++;
    } else if (hintStep === 1) {
        hintMessage.textContent += ` Gợi ý 2: Chữ cái đầu là "${current.vocab.charAt(0)}".`;
        hintStep++;
    } else if (hintStep === 2) {
        hintMessage.textContent += ` Gợi ý 3: Chữ cái cuối là "${current.vocab.charAt(current.vocab.length - 1)}".`;
        hintStep++;
    } else {
        hintMessage.textContent = "Bạn đã hết gợi ý cho câu này.";
    }
});



function updateProgressBar() {
    const progressPercentage = (correctAnswers / totalQuestions) * 100; // Tính phần trăm
    const progressBar = document.getElementById("progressBar");
    progressBar.style.width = `${progressPercentage}%`; // Cập nhật chiều rộng progress bar
}
function updateFlashcard() {
    if (currentIndex >= allQuestions.length) {
        showResults();
        return;
    }
    const current = allQuestions[currentIndex];
    explanationText.textContent = `Explanation: ${current.explanation}`;
    questionStatus.textContent = `Câu số ${currentIndex + 1}/${totalQuestions} (${current.type})`;

    if (current.type === "quiz-type-1") {
        definitionText.textContent = `Vietnamese Meaning: ${current.vietnamese_meaning}`;
        exampleText.textContent = ``;
        vocabInput.style.display = "block";
    } else if (current.type === "quiz-type-2") {
        definitionText.textContent = ""; // Không hiển thị nghĩa tiếng Việt
        exampleText.textContent = `Fill in the blank: ${current.example}`;
        vocabInput.style.display = "block";
    }

    vocabInput.value = "";
    resultMessage.textContent = "";
    hintStep = 0;
    hintMessage.textContent = "";
    hintButton.textContent = "Gợi ý";
}

checkButton.addEventListener("click", () => {
    const current = allQuestions[currentIndex];
    if (vocabInput.value.trim().toLowerCase() === current.vocab.toLowerCase()) {
        resultMessage.textContent = "Đúng!";
        correctCount++;
        correctAnswers++;
        updateProgressBar();
        currentIndex++;
        updateFlashcard();
    } else {
        resultMessage.textContent = "Chưa đúng!";
        incorrectCount++;
    }
});


document.getElementById("nextButton").addEventListener("click", () => {
    skippedCount++;
    currentIndex++;
    updateFlashcard();
});

function showResults() {
    const endTime = Date.now();
    const totalTime = Math.floor((endTime - startTime) / 1000);
    const accuracy = Math.floor((correctCount / vocabList.length) * 100);

    resultContainer.style.display = "block";
    document.querySelector(".flashcard-container").style.display = "none";

    correctCountElement.textContent = correctCount;
    incorrectCountElement.textContent = incorrectCount;
    skippedCountElement.textContent = skippedCount;
    accuracyPercentageElement.textContent = accuracy;
    completionTimeElement.textContent = totalTime;
    statusElement.textContent = accuracy > 80 ? "Đạt" : "Không đạt";

    // Tạo div chứa tabs
    const resultTabsContainer = document.createElement("div");
    resultTabsContainer.innerHTML = `
        <div class="tabs">
            <button id="correctTab" class="active">Trả lời đúng</button>
            <button id="incorrectTab">Trả lời sai / Chưa trả lời</button>
        </div>
        <div id="correctAnswers" class="tab-content active">
            <table>
                <thead>
                    <tr>
                        <th>Từ vựng</th>
                        <th>Giải thích</th>
                    </tr>
                </thead>
                <tbody>
                    ${vocabList
                        .filter((item) => item.isCorrect === true)  // Chỉ lọc câu trả lời đúng
                        .map(
                            (item) => `
                        <tr>
                            <td>${item.vocab}</td>
                            <td>${item.explanation}</td>
                        </tr>
                    `
                        )
                        .join("")}
                </tbody>
            </table>
        </div>
        <div id="incorrectAnswers" class="tab-content">
            <table>
                <thead>
                    <tr>
                        <th>Từ vựng</th>
                        <th>Giải thích</th>
                    </tr>
                </thead>
                <tbody>
                    ${vocabList
                        .filter((item) => item.isCorrect !== true)  // Chỉ lọc câu trả lời sai/chưa trả lời
                        .map(
                            (item) => `
                        <tr>
                            <td>${item.vocab}</td>
                            <td>${item.explanation}</td>
                        </tr>
                    `
                        )
                        .join("")}
                </tbody>
            </table>
        </div>
    `;

    // Thêm tabs vào `resultContainer`
    resultContainer.appendChild(resultTabsContainer);

    // Xử lý sự kiện tab
    document.getElementById("correctTab").addEventListener("click", () => {
        document.getElementById("correctAnswers").classList.add("active");
        document.getElementById("incorrectAnswers").classList.remove("active");
        document.getElementById("correctTab").classList.add("active");
        document.getElementById("incorrectTab").classList.remove("active");
    });

    document.getElementById("incorrectTab").addEventListener("click", () => {
        document.getElementById("incorrectAnswers").classList.add("active");
        document.getElementById("correctAnswers").classList.remove("active");
        document.getElementById("incorrectTab").classList.add("active");
        document.getElementById("correctTab").classList.remove("active");
    });
}

// Initialize the first flashcard
updateFlashcard();
