<?php
require_once 'config/config.php'; // Inclui o arquivo de configuração

// Conecta ao banco de dados
$conn = mysqli_connect($servername, $username, $password, $dbname);
$conn->set_charset("utf8");

// Verifica se a conexão foi bem-sucedida
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// Consulta SQL para obter dados das duas tabelas
$sql = "
    SELECT 
        aa.*, 
        dea.* 
    FROM 
        abrigos_animais aa
    INNER JOIN 
        dados_extras_abrigos dea ON aa.id = dea.abrigo_id";

$result = $conn->query($sql);

$abrigos = [];

if ($result->num_rows > 0) {
    // Itera sobre os resultados e armazena os dados em um array
    while($row = $result->fetch_assoc()) {
        $abrigos[] = $row;
    }
} else {
    echo "Nenhum resultado encontrado.";
}

// Fecha a conexão com o banco de dados
$conn->close();

// Converte o array de abrigos para JSON e retorna
header('Content-Type: application/json');
echo json_encode($abrigos);

?>