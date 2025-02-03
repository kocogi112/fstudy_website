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
  

  function main(){
    console.log("Passed Main");
    

    setTimeout(function(){
        console.log("Show Test!");
        document.getElementById("start_test").style.display="block";
        
        document.getElementById("welcome").style.display="block";

    }, 1000);
    
}

function prestartTest()
{
    if(premium_test == "False"){
        console.log("Cho phép làm bài")
    }
    else{
    console.log(premium_test);
    console.log(token_need);
    console.log(change_content);
    console.log(time_left);
    // Giảm time_left tại frontend
    time_left--;
    console.log("Updated time_left:", time_left);

    // Gửi request AJAX đến admin-ajax.php
    jQuery.ajax({
        url: `${siteUrl}/wp-admin/admin-ajax.php`,
        type: "POST",
        data: {
            action: "update_time_left",
           // username: change_content,
            time_left: time_left,
            id_test: id_test,
            table_test: 'conversation_with_ai_list',

        },
        success: function (response) {
            console.log("Server response:", response);
        },
        error: function (error) {
            console.error("Error updating time_left:", error);
        }
    });
}
    startTest();
}


function startTest()
{
    
document.getElementById("test-prepare").style.display = "none";
document.getElementById("test_screen").style.display = "block";
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
