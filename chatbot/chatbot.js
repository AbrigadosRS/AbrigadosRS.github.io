const userInput = document.getElementById('user-input');
const sendButton = document.getElementById('send-button');
const chatMessages = document.getElementById('chat-messages');

sendButton.addEventListener('click', async () => {
  const message = userInput.value;
  userInput.value = '';

  // Adicionar mensagem do usuário ao chat
  addMessage('user', message);

  try {
    // Enviar mensagem para o servidor Node.js via fetch
    const response = await fetch('/api/chat', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ message })
    });

    const data = await response.json();

    // Adicionar resposta do bot ao chat
    addMessage('bot', data);
  } catch (error) {
    console.error('Erro ao enviar mensagem:', error);
  }
});

function addMessage(sender, message) {
  const messageElement = document.createElement('div');
  messageElement.classList.add(sender);
  messageElement.textContent = message;
  chatMessages.appendChild(messageElement);
}