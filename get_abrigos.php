<?php
// Conexão com o banco de dados
$servername = "localhost:3306";
$username = "abrigad";
$password = "k^4l5Hg57";
$dbname = "abrigad1_";

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8")

// Verifica a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Consulta SQL para buscar os abrigos
$sql = "SELECT nome, endereco, cidade FROM abrigos";
$result = $conn->query($sql);

// Verifica se houve resultados
if ($result->num_rows > 0) {
    // Saída de dados de cada linha
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row["nome"] . "</td><td>" . $row["endereco"] . "</td><td>" . $row["cidade"] . "</td></tr>";
    }
} else {
    echo "0 resultados";
}

// Fecha a conexão
$conn->close();
?>
