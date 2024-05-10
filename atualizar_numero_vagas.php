<?php
// Aqui você precisa conectar ao seu banco de dados (usando mysqli, PDO ou outra extensão)
// Substitua os valores de conexão com os dados do seu servidor
require_once 'config/config.php';

// Conecta-se ao banco de dados
$conn = mysqli_connect($servername, $username, $password, $dbname);
$conn->set_charset("utf8");

// Verifica se a conexão foi bem sucedida
if (!$conn) {
    die("Falha na conexão com o banco de dados: " . mysqli_connect_error());
}

// Verifica se os dados foram recebidos via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtém o ID do abrigo e o novo número de vagas do POST
    $abrigo_id = $_POST["abrigo_id"];
    $novo_numero_vagas = $_POST["novo_numero_vagas"];

    // Atualiza o número de vagas no banco de dados
    $sql = "UPDATE abrigos_animais SET vagas = $novo_numero_vagas WHERE id = $abrigo_id";
    header("Location: paginadousuario.html");
    if (mysqli_query($conn, $sql)) {
        echo "Número de vagas atualizado com sucesso!";
    } else {
        echo "Erro ao atualizar número de vagas: " . mysqli_error($conn);
    }
}

// Fecha a conexão com o banco de dados
mysqli_close($conn);
?>
