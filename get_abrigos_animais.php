<?php
// Conexão com o banco de dados (substitua com suas credenciais)
$servername = "localhost";
$username = "seu_usuario";
$password = "sua_senha";
$dbname = "seu_banco_de_dados";

// Criando a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

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
        echo "<div class='progress-bar' style='width: " . $ocupacao_percentual . "%'></div>";
        echo "<p><strong>Nível de Ocupação:</strong> " . round($ocupacao_percentual, 2) . "%</p>";
        echo "</div>";
    }
} else {
    echo "0 resultados";
}
$conn->close();
?>