<?php
// Conexão com o banco de dados
$servername = "localhost:3306";
$username = "abrigad";
$password = "k^4l5Hg57";
$dbname = "abrigad1_";

$conn = mysqli_connect($servername, $username, $password, $dbname);
$conn->set_charset("utf8");
// Verifica a conexão
if (!$conn) {
    die("Conexão falhou: " . mysqli_connect_error());
}

// Recebe o nome pesquisado enviado via POST
$nomePesquisado = $_POST['nome'];

// Executa a consulta SQL
$sql = "SELECT * FROM abrigados WHERE nome LIKE '%$nomePesquisado%'";
$result = mysqli_query($conn, $sql);

// Prepara os resultados em um array associativo
$resultados = array();
while ($row = mysqli_fetch_assoc($result)) {
    $resultados[] = $row;
}

// Retorna os resultados em formato JSON
echo json_encode($resultados);

// Fecha a conexão
mysqli_close($conn);
?>