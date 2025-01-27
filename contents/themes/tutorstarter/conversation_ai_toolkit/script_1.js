const API_KEY = "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";
const API_URL = "https://api.groq.com/openai/v1/chat/completions";
const recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
recognition.lang = 'en-US'; // Language for recognition
recognition.interimResults = true; // Display interim results
recognition.continuous = false; // Stop after one phrase

const micButton = document.getElementById("micButton");
const sendButton = document.getElementById("sendButton");
const userInput = document.getElementById("userInput");

let isListening = false;

// Start or stop recording
micButton.addEventListener("click", () => {
  if (isListening) {
    recognition.stop();
    micButton.classList.remove("btn-danger");
    micButton.classList.add("btn-secondary");
    micButton.textContent = "🎤";
    isListening = false;
  } else {
    recognition.start();
    micButton.classList.remove("btn-secondary");
    micButton.classList.add("btn-danger");
    micButton.textContent = "⏺️";
    isListening = true;
  }
});

// Real-time transcription
recognition.onresult = (event) => {
  const transcript = Array.from(event.results)
    .map((result) => result[0].transcript)
    .join("");
  userInput.value = transcript;
};

recognition.onend = () => {
  isListening = false;
  micButton.classList.remove("btn-danger");
  micButton.classList.add("btn-secondary");
  micButton.textContent = "🎤";
};

// Error handling
recognition.onerror = (event) => {
  console.error("Speech recognition error:", event.error);
  isListening = false;
  micButton.classList.remove("btn-danger");
  micButton.classList.add("btn-secondary");
  micButton.textContent = "🎤";
};




const chatBox = document.getElementById("chatBox");

const scenarioSelect = document.getElementById("scenarioSelect");
const modelSelect = document.getElementById("modelSelect");

let conversation = [
    {
      role: "system",
      content: "Hãy trả lời ngắn gọn trong một câu hoàn chỉnh, không vượt quá 50 từ. Nếu cần thêm chi tiết, bạn có thể hỏi lại người dùng. Bất kì câu hỏi không liên quan nào hãy return Not relevant",
    },
  ];
  


  function appendMessage(role, message) {
    const messageDiv = document.createElement("div");
    messageDiv.className = `chat-message ${role}`;
    const messageContent = document.createElement("div");
    messageContent.className = "message-content";
    messageContent.textContent = message;
  
    messageDiv.appendChild(messageContent);
    chatBox.appendChild(messageDiv);
    chatBox.scrollTop = chatBox.scrollHeight;
  }
  

  

function fetchGroqResponse(role, messages, model) {
  return fetch(API_URL, {
    method: "POST",
    headers: {
      "Authorization": `Bearer ${API_KEY}`,
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      model: model,
      messages: messages,
      max_tokens: 50,
      temperature: 0.6,
      top_p: 0.8,
    }),
  })
    .then((response) => response.json())
    .then((data) => data.choices[0].message.content)
    .catch((error) => `Error: ${error.message}`);
}

sendButton.addEventListener("click", async () => {
  const userMessage = userInput.value.trim();
  if (!userMessage) return;

  appendMessage("user", userMessage);
  conversation.push({ role: "user", content: userMessage });

  const role = scenarioSelect.options[scenarioSelect.selectedIndex].text;
  const model = modelSelect.value;

  const aiResponse = await fetchGroqResponse(role, conversation, model);
  appendMessage("assistant", aiResponse);

  conversation.push({ role: "assistant", content: aiResponse });
  userInput.value = "";
});
