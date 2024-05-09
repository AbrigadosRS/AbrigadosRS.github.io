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

// Obtém o ID do usuário atualmente logado da sessão
$id_usuario = $_SESSION['id_usuario'];

// Consulta para selecionar os abrigos do usuário atualmente logado
$sql = "SELECT * FROM abrigos_animais WHERE usuario_id = $id_usuario";

// Execute a consulta
$result = $conn->query($sql);

// Crie um array para armazenar os abrigos
$abrigos = array();

// Popule o array com os abrigos obtidos do banco de dados
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $abrigos[] = $row;
    }
}

// Converta o array em formato JSON
$abrigos_json = json_encode(array("abrigos" => $abrigos));

// Envie os dados JSON de volta para o JavaScript
echo $abrigos_json;

// Feche a conexão com o banco de dados
$conn->close();
?>
