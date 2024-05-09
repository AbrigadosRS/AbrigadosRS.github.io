<?php
// Configurações do banco de dados
$servername = "localhost:3306";
$username = "abrigopet";
$password = "o~m6r57P4";
$dbname = "sistema_abrigo_animais";

// Conecta ao banco de dados
$conn = mysqli_connect($servername, $username, $password, $dbname);
$conn->set_charset("utf8");
// Verifica a conexão
if (!$conn) {
    die("Erro na conexão com o banco de dados: " . mysqli_connect_error());
}

// Verifica se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Coleta e sanitiza os dados do formulário
    $nome = mysqli_real_escape_string($conn, $_POST['nome']);
    $endereco = mysqli_real_escape_string($conn, $_POST['endereco']);
    $telefone = mysqli_real_escape_string($conn, $_POST['telefone']);
    $vagas = (int)$_POST['vagas'];
    $vagasocupadas = (int)$_POST['vagasocupadas'];
    
    // Verifica se todos os campos foram preenchidos
    if (empty($nome) || empty($endereco) || empty($telefone) || $vagas <= 0 || $vagasocupadas < 0 || $vagasocupadas > $vagas) {
        echo "Por favor, preencha todos os campos corretamente!";
    } else {
        // Insere os dados do abrigo de animais na tabela correspondente no banco de dados
        $sql = "INSERT INTO abrigos_animais (nome, endereco, telefone, vagas, vagasocupadas) VALUES ('$nome', '$endereco', '$telefone', $vagas, $vagasocupadas)";
        if (mysqli_query($conn, $sql)) {
            echo "Abrigo de animais cadastrado com sucesso!";
        } else {
            echo "Erro ao cadastrar abrigo de animais: " . mysqli_error($conn);
        }
    }
}

// Fecha a conexão com o banco de dados
mysqli_close($conn);
?>