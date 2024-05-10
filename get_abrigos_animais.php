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

        // Determinar a classe CSS com base na ocupação percentual
        $bar_color = '';
        if ($ocupacao_percentual < 70) {
            $bar_color = 'green';
        } elseif ($ocupacao_percentual >= 70 && $ocupacao_percentual < 90) {
            $bar_color = 'yellow';
        } else {
            $bar_color = 'red';
        }

        // Output dos dados em linhas estilo botão
        echo "<table class='abrigos-table'>";
        echo "<tr>";
        echo "<td>" . $row["nome"] . "</td>";
        echo "<td>" . $row["endereco"] . "</td>";
        echo "<td>" . $row["telefone"] . "</td>";
        echo "<td>" . $row["vagas"] . "/" . $row["vagasocupadas"] . "</td>";
        echo "<td>";
        echo "<div class='occupation-bar' style='background-color: $bar_color; width: " . min($ocupacao_percentual, 100) . "%'></div>";
        echo "</td>";
        echo "</tr>";
        echo "</table>";
    }
} else {
    echo "<p>Nenhum abrigo encontrado</p>";
}
$conn->close();
?>