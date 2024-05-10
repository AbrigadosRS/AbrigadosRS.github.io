<?php

session_start();

// Configurações do banco de dados
require_once 'config/config.php';

// Conecta ao banco de dados
$conn = mysqli_connect($servername, $username, $password, $dbname);
$conn->set_charset("utf8");
// Verifica a conexão
if (!$conn) {
    die("Erro na conexão com o banco de dados: " . mysqli_connect_error());
}

// Verifica se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Coleta e sanitiza os dados do formulário
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    
    // Prepara e executa a consulta SQL para buscar o usuário no banco de dados
    $sql = "SELECT * FROM usuarios WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);
    
    if ($result && mysqli_num_rows($result) > 0) {
        // Usuário encontrado, verifica a senha
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['password'])) {
            // Login bem-sucedido, define a variável de sessão e redireciona para a página do usuário
            $_SESSION['usuario_logado'] = true;
            $_SESSION['id_usuario'] = $row['id']; // Suponha que 'id' seja o campo no banco de dados que identifica exclusivamente cada usuário
            header("Location: paginadousuario.html");
            exit();
        } else {
            // Senha incorreta
            header("Location: login.html?error=senha-incorreta");
            exit();
        }
    } else {
        // Nome de usuário não encontrado
        header("Location: login.html?error=usuario-nao-encontrado");
        exit();
    }
}

// Fecha a conexão com o banco de dados
mysqli_close($conn);
?>
