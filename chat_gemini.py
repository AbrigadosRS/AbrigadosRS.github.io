import numpy as np
import pandas as pd
import google.generativeai as genai
genai.configure(api_key='AIzaSyCKteQlyaUoclJg3Ub8uWJdiO_fBNuVTUM')


# Set up the model
generation_config = {
  "temperature": 1,
  "top_p": 0.95,
  "top_k": 0,
  "max_output_tokens": 8192,
}

safety_settings = [
  {
    "category": "HARM_CATEGORY_HARASSMENT",
    "threshold": "BLOCK_MEDIUM_AND_ABOVE"
  },
  {
    "category": "HARM_CATEGORY_HATE_SPEECH",
    "threshold": "BLOCK_MEDIUM_AND_ABOVE"
  },
  {
    "category": "HARM_CATEGORY_SEXUALLY_EXPLICIT",
    "threshold": "BLOCK_MEDIUM_AND_ABOVE"
  },
  {
    "category": "HARM_CATEGORY_DANGEROUS_CONTENT",
    "threshold": "BLOCK_MEDIUM_AND_ABOVE"
  },
]

model = genai.GenerativeModel(model_name="gemini-1.5-pro-latest",
                              generation_config=generation_config,
                              safety_settings=safety_settings)

def extract_pdf_pages(pathname: str) -> list[str]:
  parts = [f"--- START OF PDF ${pathname} ---"]
  # Add logic to read the PDF and return a list of pages here.
  pages = []
  for index, page in enumerate(pages):
    parts.append(f"--- PAGE {index} ---")
    parts.append(page)
  return parts

convo = model.start_chat(history=[
  {
    "role": "user",
    "parts": extract_pdf_pages("<path>/document0.pdf")
  },
  {
    "role": "user",
    "parts": ["Você é um gestor de uma plataforma de abrigos de animais da enchente de 2024 no rio grande do sul. O seu Trabalho é ajudar as pessoas que acessarem o site a definir qual abrigo elas devem fornecer a sua ajuda, com base na cidade em que elas moram, na disponibilidade de recurso que elas tem e nos abrigos que apresentam maior necessidade. \nA tabela enviada contém os dados dos abrigos registrados na plataforma. Comece a conversa Perguntando o nome a cidade de quem está entrando no chat. Pergunte o que a pessoa está disposta a oferecer e com base na resposta dela, analise a tabela para definir qual abrigo ela deve ajudar primeiro.\nFaça uma pergunta de cada vez.\nVocê também pode se sentir livre para usar emojis que representem a causa animal (cães, gatos, etc)"]
  },
  {
    "role": "model",
    "parts": ["Olá! 👋 Sou um guia para te ajudar a encontrar o abrigo ideal para você oferecer sua ajuda 🙏. Para começarmos, me diga: qual o seu nome e a cidade onde você mora? 🐶🐱"]
  },
])

while True:
  user_input = input("YOUR_USER_INPUT: ")
  convo.send_message(user_input)
  print(convo.last.text)