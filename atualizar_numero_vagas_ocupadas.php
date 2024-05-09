<?php
// Aqui você precisa conectar ao seu banco de dados (usando mysqli, PDO ou outra extensão)
// Substitua os valores de conexão com os dados do seu servidor
$servername = "localhost:3306";
$username = "abrigopet";
$password = "o~m6r57P4";
$dbname = "sistema_abrigo_animais";

// Conecta-se ao banco de dados
$conn = mysqli_connect($servername, $username, $password, $dbname);
$conn->set_charset("utf8");

// Verifica se a conexão foi bem sucedida
if (!$conn) {
    die("Falha na conexão com o banco de dados: " . mysqli_connect_error());
}

// Verifica se os dados foram recebidos via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtém o ID do abrigo e o novo número de vagas ocupadas do POST
    $abrigo_id = $_POST["abrigo_id"];
    $novo_numero_vagas_ocupadas = $_POST["novo_numero_vagas_ocupadas"];

    // Atualiza o número de vagas ocupadas no banco de dados
    $sql = "UPDATE abrigos SET numero_vagas_ocupadas = $novo_numero_vagas_ocupadas WHERE id = $abrigo_id";

    if (mysqli_query($conn, $sql)) {
        echo "Número de vagas ocupadas atualizado com sucesso!";
    } else {
        echo "Erro ao atualizar número de vagas ocupadas: " . mysqli_error($conn);
    }
}

// Fecha a conexão com o banco de dados
mysqli_close($conn);
?>
