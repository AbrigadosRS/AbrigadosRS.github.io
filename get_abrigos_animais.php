<?php
require_once 'config/config.php';

// Conecta ao banco de dados
$conn = mysqli_connect($servername, $username, $password, $dbname);
$conn->set_charset("utf8");

// Verificando a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Consulta SQL para obter os abrigos e seus dados extras
$sql = "SELECT abrigos_animais.nome, abrigos_animais.endereco, abrigos_animais.telefone, abrigos_animais.vagas, abrigos_animais.vagasocupadas,
        dados_extra_abrigo.tipo_animais, dados_extra_abrigo.necessidade_voluntarios, dados_extra_abrigo.necessidade_veterinario,
        dados_extra_abrigo.necessidade_alimentos, dados_extra_abrigo.outros
        FROM abrigos_animais
        LEFT JOIN dados_extra_abrigo ON abrigos_animais.id = dados_extra_abrigo.abrigo_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output dos dados de cada abrigo, incluindo os dados extras
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
        echo "<tr class='abrigo-row'>";
        echo "<td>" . $row["nome"] . "</td>";
        echo "<td>" . $row["endereco"] . "</td>";
        echo "<td>" . $row["telefone"] . "</td>";
        echo "<td>" . $row["vagasocupadas"] . "/" . $row["vagas"] . "</td>";
        echo "<td>";
        echo "<div class='occupation-bar' style='background-color: $bar_color; width: " . min($ocupacao_percentual, 100) . "%'></div>";
        echo "</td>";
        echo "</tr>";
        echo "<tr class='dropdown-menu'>";
        echo "<td colspan='5'>";
        echo "<strong>Tipo de animais recebidos:</strong> " . $row["tipo_animais"] . "<br>";
        echo "<strong>Necessidade de voluntários:</strong> " . $row["necessidade_voluntarios"] . "<br>";
        echo "<strong>Necessidade de veterinário:</strong> " . $row["necessidade_veterinario"] . "<br>";
        echo "<strong>Necessidade de alimentos:</strong> " . $row["necessidade_alimentos"] . "<br>";
        echo "<strong>Outros:</strong> " . $row["outros"];
        echo "</td>";
        echo "</tr>";
        echo "</table>";
    }
} else {
    echo "<p>Nenhum abrigo encontrado</p>";
}
$conn->close();
?>