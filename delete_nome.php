<?php
// Verifica se o método de requisição é POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica se o parâmetro "nome" foi enviado via POST
    if (isset($_POST["nome"])) {
        // Conexão com o banco de dados
        $servername = "localhost:3306";
        $username = "abrigad";
        $password = "k^4l5Hg57";
        $dbname = "abrigad1_";

        // Cria a conexão
        $conn = mysqli_connect($servername, $username, $password, $dbname);

        // Verifica se houve erro na conexão
        if ($conn->connect_error) {
            die("Conexão falhou: " . $conn->connect_error);
        }

        // Prepara e executa a consulta SQL para remover o nome da tabela
        $nome = $_POST["nome"];
        $sql = "DELETE FROM abrigados WHERE nome = '$nome'";

        if ($conn->query($sql) === TRUE) {
            echo "Nome removido com sucesso!";
        } else {
            echo "Erro ao remover nome: " . $conn->error;
        }

        // Fecha a conexão com o banco de dados
        $conn->close();
    } else {
        echo "Parâmetro 'nome' não foi enviado.";
    }
} else {
    echo "Acesso negadosss.";
}
?>