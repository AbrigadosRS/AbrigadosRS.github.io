<?php

require_once 'config.php';

// Conecta ao banco de dados
$conn = mysqli_connect($servername, $username, $password, $dbname);
$conn->set_charset("utf8");

// Verificando a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Consulta SQL para obter os abrigos
$sql = "SELECT nome, endereco, telefone, vagas, vagasocupadas FROM abrigos_animais";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output dos dados de cada abrigo
    while($row = $result->fetch_assoc()) {
        // Calcular o nível de ocupação (em porcentagem)
        $ocupacao_percentual = ($row["vagasocupadas"] / $row["vagas"]) * 100;
        
        echo "<div class='abrigo'>";
        echo "<h2>" . $row["nome"] . "</h2>";
        echo "<p><strong>Endereço:</strong> " . $row["endereco"] . "</p>";
        echo "<p><strong>Telefone:</strong> " . $row["telefone"] . "</p>";
        echo "<p><strong>Vagas:</strong> " . $row["vagas"] . "</p>";
        echo "<p><strong>Vagas Ocupadas:</strong> " . $row["vagasocupadas"] . "</p>";
        echo "<div class='progress-bar' style='width: " . $ocupacao_percentual . "%'></div>";
        echo "<p><strong>Nível de Ocupação:</strong> " . round($ocupacao_percentual, 2) . "%</p>";
        echo "</div>";
    }
} else {
    echo "0 resultados";
}
$conn->close();
?>
