<?php
// Configurações do banco de dados
$servername = "localhost:3306";
$username = "abrigopet";
$password = "o~m6r57P4";
$dbname = "sistema_abrigo_animais";

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
            echo "Login bem-sucedido!";
            header("Location: paginadousuario.html");
            exit();
            // Inicie a sessão ou faça qualquer outra ação necessária após o login
        } else {
            echo "Senha incorreta!";
        }
    } else {
        echo "Nome de usuário não encontrado!";
    }
}

// Fecha a conexão com o banco de dados
mysqli_close($conn);
?>