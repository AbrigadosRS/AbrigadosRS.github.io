document.addEventListener('DOMContentLoaded', function() {
  const chatBox = document.getElementById('chat-box');
  const userInput = document.getElementById('user-input');
  const sendBtn = document.getElementById('send-btn');

  sendBtn.addEventListener('click', function() {
    const userMessage = userInput.value.trim();
    if (userMessage === '') return;

    appendMessage('user', userMessage);
    userInput.value = '';

    sendMessage(userMessage);
  });

  function appendMessage(role, message) {
    const messageDiv = document.createElement('div');
    messageDiv.classList.add('message', role);
    messageDiv.innerText = message;
    chatBox.appendChild(messageDiv);
    chatBox.scrollTop = chatBox.scrollHeight;
  }

  async function sendMessage(message) {
    try {
      const response = await fetch('../../chatbot', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ message })
      });
      const data = await response.json();
      appendMessage('model', data);
    } catch (error) {
      console.error('Erro ao enviar mensagem:', error);
      appendMessage('error', 'Erro ao enviar mensagem');
    }
  }
});