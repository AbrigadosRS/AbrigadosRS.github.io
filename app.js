const express = require('express');
const bodyParser = require('body-parser');
const { GoogleAuth } = require('google-auth-library');
const { v1 } = require('@google-cloud/aiplatform');
const axios = require('axios');
require('dotenv').config();

// *** CARREGANDO VARIÁVEIS DE AMBIENTE ***

// *** CONFIGURAÇÃO ***
const API_KEY = process.env.API_KEY; // Substitua 'process.env.API_KEY' pelo seu API Key.
const PORT = process.env.PORT || 3000; // Defina a porta que o Plesk permite para o Node.js

// *** GOOGLE AUTH E GEMINI ***
const auth = new GoogleAuth({
  scopes: ['https://www.googleapis.com/auth/cloud-platform'],
});

const client = new v1.PredictionServiceClient({
  apiEndpoint: 'us-central1-aiplatform.googleapis.com',
  credentials: auth.getApplicationDefault(),
});

// *** FUNÇÕES ***
async function extractDataFromPHP() { 
  try {
    const response = await axios.get(`/site/chatbot/buscardbchat.php`);
    return response.data;
  } catch (error) {
    console.error('Erro ao consultar o script PHP:', error);
    throw error;
  }
}

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

  try {
    const [response] = await client.predict(request);
    const message = response.predictions[0].text;
    return message;
  } catch (error) {
    console.error('Erro ao chamar o Gemini:', error);
    throw error;
  }
}

// *** SERVIDOR EXPRESS ***
const app = express();
app.use(bodyParser.json());

let conversationHistory = [];

app.post('/site/chatbot', async (req, res) => {
  try {
    const userMessage = req.body.message;
    conversationHistory.push({ role: 'user', parts: [userMessage] });

    if (conversationHistory.length === 1) {
      // Primeira mensagem: extrair dados do PHP e adicionar saudação inicial
      //conversationHistory[0].parts = await extractDataFromPHP();
      conversationHistory.push({ 
        role: 'user', 
        parts: ["Você é um gestor de uma plataforma de abrigos de animais da enchente de 2024 no rio grande do sul. O seu Trabalho é ajudar as pessoas que acessarem o site a definir qual abrigo elas devem fornecer a sua ajuda, com base na cidade em que elas moram, na disponibilidade de recurso que elas tem e nos abrigos que apresentam maior necessidade. \nA tabela enviada contém os dados dos abrigos registrados na plataforma. Comece a conversa Perguntando o nome a cidade de quem está entrando no chat. Pergunte o que a pessoa está disposta a oferecer e com base na resposta dela, analise a tabela para definir qual abrigo ela deve ajudar primeiro.\nFaça uma pergunta de cada vez.\nVocê também pode se sentir livre para usar emojis que representem a causa animal (cães, gatos, etc)"] 
      });
      conversationHistory.push({ 
        role: 'model', 
        parts: ["Olá! 👋 Sou um guia para te ajudar a encontrar o abrigo ideal para você oferecer sua ajuda 🙏. Para começarmos, me diga: qual o seu nome e a cidade onde você mora? 🐶🐱"] 
      }); 
    }

    const response = await sendMessage(conversationHistory);
    res.send(response);
  } catch (error) {
    console.error('Erro na rota /site/chatbot:', error);
    res.status(500).send('Erro interno do servidor');
  }
});

app.listen(PORT, () => {
  console.log(`Servidor rodando na porta ${PORT}`);
});