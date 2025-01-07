
let currentIndex = 0;
const flashcard = document.getElementById("flashcard");
const vocabText = document.getElementById("vocabText");
const definitionText = document.getElementById("definitionText");
const explanationText = document.getElementById("explanationText");

const progress = document.getElementById("progress");
const audioButton = document.getElementById("audioButton");

function updateFlashcard() {
    flashcard.classList.remove("flipped"); // Reset to vocab side

    setTimeout(function() {
        
    const current = vocabList[currentIndex];
    vocabText.innerHTML = current.vocab;
    definitionText.innerHTML = current.vietnamese_meaning;
    explanationText.innerHTML = current.explanation;
    progress.innerHTML = `${currentIndex + 1} / ${vocabList.length}`;
        }, 500); 
        


}

flashcard.addEventListener("click", () => {
    flashcard.classList.toggle("flipped");
});

document.getElementById("prev").addEventListener("click", () => {
    if (currentIndex > 0) {
        currentIndex--;
        updateFlashcard();
    }
});

document.getElementById("next").addEventListener("click", () => {
    if (currentIndex < vocabList.length - 1) {
        currentIndex++;
        updateFlashcard();
    }
});

document.getElementById("fullscreen").addEventListener("click", () => {
    if (!document.fullscreenElement) {
        document.documentElement.requestFullscreen();
    } else {
        document.exitFullscreen();
    }
});

document.getElementById("settings").addEventListener("click", () => {
    alert("Settings feature coming soon!");
});

// Text-to-Speech functionality
audioButton.addEventListener("click", (event) => {
    event.stopPropagation(); // Prevent flipping the card
    const utterance = new SpeechSynthesisUtterance(vocabList[currentIndex].vocab);
    speechSynthesis.speak(utterance);
});

// Initialize the first flashcard
updateFlashcard();

// Reference to the table body
const vocabTableBody = document.querySelector("#vocabTable tbody");

// Function to populate the vocab table
function populateVocabTable() {
    vocabList.forEach((word, index) => {
        const row = document.createElement("tr");

        // Số thứ tự
        const indexCell = document.createElement("td");
        indexCell.textContent = index + 1;

        // Vocab (double-click to play audio)
        const vocabCell = document.createElement("td");
        vocabCell.textContent = word.vocab;
        vocabCell.addEventListener("dblclick", () => {
            const utterance = new SpeechSynthesisUtterance(word.vocab);
            speechSynthesis.speak(utterance);
        });

        // Vietnamese Meaning
        const meaningCell = document.createElement("td");
        meaningCell.textContent = word.vietnamese_meaning;

        // Explanation
        const explanationCell = document.createElement("td");
        explanationCell.textContent = word.explanation;

        const exampleCell = document.createElement("td");
        exampleCell.textContent = word.example;

        // Phát âm (check confidence level)
        const checkCell = document.createElement("td");
        const checkButton = document.createElement("button");
        checkButton.textContent = "Kiểm tra";
        checkButton.addEventListener("click", () => {
            startSpeechRecognition(word.vocab, checkCell);
        });
        checkCell.appendChild(checkButton);

        row.appendChild(indexCell);
        row.appendChild(vocabCell);
        row.appendChild(meaningCell);
        row.appendChild(explanationCell);
        row.appendChild(exampleCell);
        row.appendChild(checkCell);
        
        vocabTableBody.appendChild(row);
    });
}

// Speech Recognition Logic
function startSpeechRecognition(targetWord, cell) {
    const recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
    recognition.lang = "en-US";

    recognition.onresult = (event) => {
        const transcript = event.results[0][0].transcript.toLowerCase();
        const confidence = Math.round(event.results[0][0].confidence * 100);

        if (transcript === targetWord.toLowerCase() && confidence > 70) {
            cell.textContent = `Bạn phát âm đúng! (Confidence: ${confidence}%)`;
        } else {
            cell.textContent = `Phát âm chưa đúng (Confidence: ${confidence}%)`;
        }
    };

    recognition.onerror = () => {
        cell.textContent = "Lỗi khi nhận diện giọng nói!";
    };

    recognition.start();
}

// Initialize the vocab table
populateVocabTable();

