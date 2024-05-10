<?php
// Configurações do banco de dados
require_once 'config/config.php';

// Conecta ao banco de dados
$conn = mysqli_connect($servername, $username, $password, $dbname);
$conn->set_charset("utf8");

// Verifique se os dados foram enviados via POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["abrigo_id"])) {
    // Recupere os dados do POST
    $abrigo_id = $_POST["abrigo_id"];
    $tipo_animais = $_POST["tipo_animais"];
    $necessidade_voluntarios = $_POST["necessidade_voluntarios"];
    $necessidade_veterinario = $_POST["necessidade_veterinario"];
    $necessidade_alimentos = $_POST["necessidade_alimentos"];
    $outros = $_POST["outros"];

    // Conecte-se ao banco de dados (substitua com suas credenciais)
    $servername = "seu_servidor";
    $username = "seu_usuario";
    $password = "sua_senha";
    $dbname = "seu_banco_de_dados";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verifique a conexão
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    // Prepare e execute a consulta SQL para inserir os dados
    $sql = "INSERT INTO dados_extra_abrigo (abrigo_id, tipo_animais, necessidade_voluntarios, necessidade_veterinario, necessidade_alimentos, outros) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssss", $abrigo_id, $tipo_animais, $necessidade_voluntarios, $necessidade_veterinario, $necessidade_alimentos, $outros);
    $stmt->execute();

    // Verifique se a inserção foi bem-sucedida
    if ($stmt->affected_rows > 0) {
        echo "Dados salvos com sucesso.";
    } else {
        echo "Erro ao salvar os dados.";
    }

    // Feche a conexão e o statement
    $stmt->close();
    $conn->close();
}
?>
