// node --version # Should be >= 18
// npm install @google/generative-ai

const {
  GoogleGenerativeAI,
  HarmCategory,
  HarmBlockThreshold,
} = require("@google/generative-ai");
const fs = require("fs");

const MODEL_NAME = "gemini-1.0-pro";
const API_KEY = "AIzaSyAADxHFaangSczoSim6KiVuFOsPytB-JV8";

async function runChat() {
  const genAI = new GoogleGenerativeAI(API_KEY);
  const model = genAI.getGenerativeModel({ model: MODEL_NAME });

  const generationConfig = {
    temperature: 0.9,
    topK: 0,
    topP: 1,
    maxOutputTokens: 2048,
  };

  const safetySettings = [
    {
      category: HarmCategory.HARM_CATEGORY_HARASSMENT,
      threshold: HarmBlockThreshold.BLOCK_MEDIUM_AND_ABOVE,
    },
    {
      category: HarmCategory.HARM_CATEGORY_HATE_SPEECH,
      threshold: HarmBlockThreshold.BLOCK_MEDIUM_AND_ABOVE,
    },
    {
      category: HarmCategory.HARM_CATEGORY_SEXUALLY_EXPLICIT,
      threshold: HarmBlockThreshold.BLOCK_MEDIUM_AND_ABOVE,
    },
    {
      category: HarmCategory.HARM_CATEGORY_DANGEROUS_CONTENT,
      threshold: HarmBlockThreshold.BLOCK_MEDIUM_AND_ABOVE,
    },
  ];

  if (!fs.existsSync("image0.")) {
    throw new Error("Could not find images in current directory.");
  }

  const chat = model.startChat({
    generationConfig,
    safetySettings,
    history: [
      {
        role: "user",
        parts: [{ text: undefined}],
      },
      {
        role: "user",
        parts: [{ text: "Você é um gestor de uma plataforma de abrigos de animais da enchente de 2024 no rio grande do sul. O seu Trabalho é ajudar as pessoas que acessarem o site a definir qual abrigo elas devem fornecer a sua ajuda, com base na cidade em que elas moram, na disponibilidade de recurso que elas tem e nos abrigos que apresentam maior necessidade. \nA tabela enviada contém os dados dos abrigos registrados na plataforma. Comece a conversa Perguntando o nome a cidade de quem está entrando no chat. Pergunte o que a pessoa está disposta a oferecer e com base na resposta dela, analise a tabela para definir qual abrigo ela deve ajudar primeiro"}],
      },
      {
        role: "model",
        parts: [{ text: "Olá! Seja bem-vindo à nossa plataforma de apoio aos abrigos de animais afetados pela enchente de 2024 no Rio Grande do Sul. Para que possamos te ajudar a direcionar sua ajuda da melhor forma possível, me diga: qual o seu nome e em qual cidade você reside? 😊 \n\nEm seguida, conte-me um pouco sobre o tipo de ajuda que você gostaria de oferecer: você prefere ajudar com trabalho voluntário, doações de materiais ou recursos financeiros? 😄"}],
      },
      {
        role: "user",
        parts: [{ text: "Meu nome é Thiago e Sou de porto Alegre. Possuo 10kg de ração para doação"}],
      },
      {
        role: "model",
        parts: [{ text: "Olá Thiago, que bacana da sua parte ajudar os animais com essa doação de ração! 😊 Em Porto Alegre, temos alguns abrigos que necessitam urgentemente de alimento:\n\n* **Abrigo A:** Possui 13 vagas livres e precisa de ração. \n* **Abrigo C:**  Possui 17 vagas livres e também precisa de ração, além de medicamentos.\n* **Abrigo F:**  Possui 18 vagas livres e a necessidade por ração é média, mas também precisam de outros materiais.\n* **Abrigo G:** Possui 10 vagas livres, mas a necessidade de ração não é urgente.\n* **Abrigo J:** Possui 18 vagas livres e a necessidade de ração é alta, além de precisarem de areia sanitária.\n\nConsiderando a urgência na necessidade de ração, você poderia entrar em contato com o Abrigo A ou o Abrigo C para verificar qual deles está com a necessidade mais crítica no momento. \n\nVocê gostaria de saber mais sobre algum desses abrigos ou prefere que eu te recomende outro? 😉"}],
      },
      {
        role: "user",
        parts: [{ text: "Você pode me mandar o telefone do abrigo A?"}],
      },
      {
        role: "model",
        parts: [{ text: "Com certeza, Thiago! O telefone do Abrigo A é (11) 1234-5678. \n\nLigue para eles e combine a entrega da sua doação de ração. Tenho certeza que eles ficarão muito felizes com a sua ajuda! 😄 \n\nSe precisar de alguma outra informação sobre os abrigos, pode me perguntar! 😊"}],
      },
    ],
  });

  const result = await chat.sendMessage("YOUR_USER_INPUT");
  const response = result.response;
  console.log(response.text());
}

runChat();