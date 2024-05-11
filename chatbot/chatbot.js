const { GoogleAuth } = require('google-auth-library');
const { v1 } = require('@google-cloud/aiplatform');
const axios = require('axios');

// Substitua pela sua API Key
require('dotenv').config();
const API_KEY = process.env.API_KEY;

// Crie a instância do Google Auth
const auth = new GoogleAuth({
  scopes: ['https://www.googleapis.com/auth/cloud-platform'],
});

// Crie o cliente para o Google Generative AI
const client = new v1.PredictionServiceClient({
  apiEndpoint: 'us-central1-aiplatform.googleapis.com', 
  credentials: auth.getApplicationDefault(), 
});

// Função para extrair dados do script PHP
async function extractDataFromPHP() {
  try {
    const response = await axios.get('buscardbchat.php');
    const data = response.data;
    return data;
  } catch (error) {
    console.error('Erro ao consultar o script PHP:', error);
    throw error; 
  }
}

// Configurações do modelo Gemini
const generationConfig = {
  temperature: 1,
  topP: 0.95,
  topK: 0,
  maxOutputTokens: 8192,
};

const safetySettings = [
  { category: 'HARM_CATEGORY_HARASSMENT', threshold: 'BLOCK_MEDIUM_AND_ABOVE' },
  { category: 'HARM_CATEGORY_HATE_SPEECH', threshold: 'BLOCK_MEDIUM_AND_ABOVE' },
  { category: 'HARM_CATEGORY_SEXUALLY_EXPLICIT', threshold: 'BLOCK_MEDIUM_AND_ABOVE' },
  { category: 'HARM_CATEGORY_DANGEROUS_CONTENT', threshold: 'BLOCK_MEDIUM_AND_ABOVE' },
];

// Função para enviar uma mensagem ao Gemini e obter a resposta
async function sendMessage(messages) {
  const request = {
    endpoint: `projects/${process.env.GOOGLE_CLOUD_PROJECT}/locations/us-central1/publishers/google/models/gemini-1.5-pro-latest`,
    parameters: {
      temperature: generationConfig.temperature,
      topP: generationConfig.topP,
      topK: generationConfig.topK,
      maxOutputTokens: generationConfig.maxOutputTokens,
    },
    instance: {
      context: {
        messages: messages.map(msg => ({
          author: msg.role,
          content: msg.parts.join('\n'),
        })),
      },
    },
  };

  const [response] = await client.predict(request);
  const message = response.predictions[0].text;
  return message;
}

// Função principal
async function main() {
  try {
    let conversationHistory = [
      {
        role: 'user',
        parts: await extractDataFromPHP(), 
      },
      {
        role: 'user',
        parts: ["Você é um gestor de uma plataforma de abrigos de animais da enchente de 2024 no rio grande do sul. O seu Trabalho é ajudar as pessoas que acessarem o site a definir qual abrigo elas devem fornecer a sua ajuda, com base na cidade em que elas moram, na disponibilidade de recurso que elas tem e nos abrigos que apresentam maior necessidade. \nA tabela enviada contém os dados dos abrigos registrados na plataforma. Comece a conversa Perguntando o nome a cidade de quem está entrando no chat. Pergunte o que a pessoa está disposta a oferecer e com base na resposta dela, analise a tabela para definir qual abrigo ela deve ajudar primeiro.\nFaça uma pergunta de cada vez.\nVocê também pode se sentir livre para usar emojis que representem a causa animal (cães, gatos, etc)"]
      },
      {
        role: 'model',
        parts: ["Olá! 👋 Sou um guia para te ajudar a encontrar o abrigo ideal para você oferecer sua ajuda 🙏. Para começarmos, me diga: qual o seu nome e a cidade onde você mora? 🐶🐱"]
      },
    ];

    console.log(await sendMessage(conversationHistory));

    // Loop para continuar a conversa
    while (true) {
      const userInput = prompt("YOUR_USER_INPUT: ");
      conversationHistory.push({ role: 'user', parts: [userInput] });

      const response = await sendMessage(conversationHistory);
      console.log(response);
    }

  } catch (error) {
    console.error('Ocorreu um erro:', error);
  }
}

main();