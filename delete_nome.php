<?php
// Verifica se o método de requisição é POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica se o parâmetro "id" foi enviado via POST
    if (isset($_POST["id"])) {
        $id = $_POST["id"];
        echo "ID recebido: " . $id;
        
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

        // Prepara e executa a consulta SQL para remover o registro da tabela
        $sql = "DELETE FROM abrigados WHERE id = $id";

        if ($conn->query($sql) === TRUE) {
            echo "Registro removido com sucesso!";
        } else {
            echo "Erro ao remover registro: " . $conn->error;
        }

        // Fecha a conexão com o banco de dados
        $conn->close();
    } else {
        echo "Parâmetro 'id' não foi enviado.";
    }
} else {
    echo "Acesso negado.";
}
?>