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
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    
    // Criptografa a senha
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Prepara e executa a consulta SQL para inserir o novo usuário no banco de dados
    $sql = "INSERT INTO usuarios (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
    if (mysqli_query($conn, $sql)) {
        echo "Usuário registrado com sucesso!";
    } else {
        echo "Erro ao registrar usuário: " . mysqli_error($conn);
    }
}

// Fecha a conexão com o banco de dados
mysqli_close($conn);
?>
