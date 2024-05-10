<?php

require_once 'config/config.php';

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
        
        echo "<tr>";
        echo "<td>" . $row["nome"] . "</td>";
        echo "<td>" . $row["endereco"] . "</td>";
        echo "<td>" . $row["telefone"] . "</td>";
        echo "<td>" . $row["vagas"] . "/" . $row["vagasocupadas"] . "</td>";
        echo "<td><div class='progress-bar' style='width: " . $ocupacao_percentual . "%'></div></td>";
        echo "<td>" . round($ocupacao_percentual, 2) . "%</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='6'>Nenhum abrigo encontrado</td></tr>";
}
$conn->close();
?>