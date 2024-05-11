const express = require("express");
const { GoogleGenerativeAI } = require("@google/generative-ai");

const app = express();
const port = 3000;

// Inicializa o cliente Gemini
const genAI = new GoogleGenerativeAI("AIzaSyAADxHFaangSczoSim6KiVuFOsPytB-JV8");
const model = genAI.getGenerativeModel({ model: "gemini-1.0-pro" });

// Middleware para analisar solicitações JSON
app.use(express.json());

// Cria uma rota de API para processar solicitações do usuário
app.post("/chat", async (req, res) => {
  const { message } = req.body;

  // Gera uma resposta usando o modelo Gemini
  const generation = await model.generate({
    generationConfig,
    safetySettings,
    history: [
      {
        role: "user",
        parts: [{ text: message }],
      },
      {
        role: "model",
        parts: [],
      },
    ],
  });

  const response = generation.response.text();

  // Envia a resposta ao usuário
  res.json({ response });
});

// Define a rota raiz para servir o arquivo HTML
app.get("/", (req, res) => {
  res.sendFile(__dirname + "/chatbot.html");
});

app.listen(port, () => {
  console.log(`Servidor em execução na porta ${port}`);
});