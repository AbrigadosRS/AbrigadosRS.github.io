<?php
// Inicie a sessão para acessar as variáveis de sessão (se necessário)
session_start();

// Verifique se o usuário está autenticado
if (!isset($_SESSION['usuario_logado'])) {
    // Se não estiver autenticado, redirecione para a página de login
    header("Location: login.html");
    exit();
}

// Aqui você precisa conectar ao seu banco de dados (usando mysqli, PDO ou outra extensão)
// Substitua os valores de conexão com os dados do seu servidor
$servername = "localhost:3306";
$username = "abrigopet";
$password = "o~m6r57P4";
$dbname = "sistema_abrigo_animais";

// Conecte-se ao banco de dados
$conn = mysqli_connect($servername, $username, $password, $dbname);
$conn->set_charset("utf8");

// Verifique se a conexão foi bem-sucedida
if ($conn->connect_error) {
    die("Erro de conexão com o banco de dados: " . $conn->connect_error);
}

// Consulta para selecionar os abrigos do usuário atualmente logado
$id_usuario = $_SESSION['id_usuario']; // Suponha que você tenha armazenado o ID do usuário na sessão
$sql = "SELECT * FROM abrigos_animais WHERE id_usuario = $id_usuario";

// Execute a consulta
$result = $conn->query($sql);

// Verifique se há resultados
if ($result->num_rows > 0) {
    // Exiba os abrigos em formato HTML
    while ($row = $result->fetch_assoc()) {
        echo "<p>Nome do Abrigo: " . $row["nome"] . "</p>";
        echo "<p>Endereço: " . $row["endereco"] . "</p>";
        echo "<p>Cidade: " . $row["cidade"] . "</p>";
        echo "<p>Vagas: " . $row["vagas"] . "</p>";
        echo "<p>Vagas Ocupadas: " . $row["vagasocupadas"] . "</p>";
        // Adicione mais campos conforme necessário
        echo "<hr>"; // Adicione uma linha horizontal entre os abrigos
    }
} else {
    echo "Nenhum abrigo encontrado para este usuário.";
}

// Feche a conexão com o banco de dados
$conn->close();
?>