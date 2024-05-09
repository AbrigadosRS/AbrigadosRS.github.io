<?php
// Inicia a sessão, se ainda não estiver iniciada
session_start();

// Finaliza a sessão
session_destroy();

// Redireciona o usuário para a página de login
header("Location: login.html");
exit; // Certifique-se de sair após o redirecionamento
?>