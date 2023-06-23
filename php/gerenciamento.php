<!DOCTYPE html>
<html>
<head>
    <title>Gerenciamento de Exercícios</title>
</head>
<body>
    <h1>Gerenciamento de Exercícios</h1>

    <h2>Adicionar Exercício</h2>
    <form action="" method="post">
        <label>Categoria:</label>
        <input type="text" name="categoria" required><br>

        <label>Nome:</label>
        <input type="text" name="nome" required><br>

        <label>Séries:</label>
        <input type="number" name="series" required><br>

        <label>Repetições:</label>
        <input type="number" name="repeticao" required><br>

        <label>Tempo:</label>
        <input type="number" name="tempo" required><br>

        <input type="submit" name="adicionar" value="Adicionar">
    </form>

    <h2>Remover Exercício</h2>
    <form action="" method="post">
        <label>ID do Exercício:</label>
        <input type="number" name="exercicioId" required><br>

        <input type="submit" name="remover" value="Remover">
    </form>

    <h2>Editar Exercício</h2>
    <form action="" method="post">
        <label>ID do Exercício:</label>
        <input type="number" name="exercicioId" required><br>

        <label>Categoria:</label>
        <input type="text" name="categoria" required><br>

        <label>Nome:</label>
        <input type="text" name="nome" required><br>

        <label>Séries:</label>
        <input type="number" name="series" required><br>

        <label>Repetições:</label>
        <input type="number" name="repeticao" required><br>

        <label>Tempo:</label>
        <input type="number" name="tempo" required><br>

        <input type="submit" name="editar" value="Editar">
    </form>

    <?php
    // Configuração do banco de dados
    $servername = "localhost";
    $username = "adim";
    $password = "1212";
    $dbname = "GYMPLANNER";

    // Cria a conexão com o banco de dados
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verifica a conexão
    if ($conn->connect_error) {
        die("Falha na conexão com o banco de dados: " . $conn->connect_error);
    }

    // Função para adicionar um exercício
    function adicionarExercicio($categoria, $nome, $series, $repeticao, $tempo) {
        global $conn;
        
        // Prepara a instrução SQL
        $stmt = $conn->prepare("INSERT INTO exercicios_usuarios (categoria, nome, series, repeticao, tempo) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiii", $categoria, $nome, $series, $repeticao, $tempo);
        
        // Executa a instrução SQL
        if ($stmt->execute() === TRUE) {
            echo "Exercício adicionado com sucesso.";
        } else {
            echo "Erro ao adicionar o exercício: " . $conn->error;
        }
        
        // Fecha a instrução e a conexão
        $stmt->close();
    }

    // Função para remover um exercício
    function removerExercicio($exercicioId) {
        global $conn;
        
        // Prepara a instrução SQL
        $stmt = $conn->prepare("DELETE FROM exercicios_usuarios WHERE id = ?");
        $stmt->bind_param("i", $exercicioId);
        
        // Executa a instrução SQL
        if ($stmt->execute() === TRUE) {
            echo "Exercício removido com sucesso.";
        } else {
            echo "Erro ao remover o exercício: " . $conn->error;
        }
        
        // Fecha a instrução e a conexão
        $stmt->close();
    }

    // Função para editar um exercício
    function editarExercicio($exercicioId, $categoria, $nome, $series, $repeticao, $tempo) {
        global $conn;
        
        // Prepara a instrução SQL
        $stmt = $conn->prepare("UPDATE exercicios_usuarios SET categoria = ?, nome = ?, series = ?, repeticao = ?, tempo = ? WHERE id = ?");
        $stmt->bind_param("ssiiii", $categoria, $nome, $series, $repeticao, $tempo, $exercicioId);
        
        // Executa a instrução SQL
        if ($stmt->execute() === TRUE) {
            echo "Exercício editado com sucesso.";
        } else {
            echo "Erro ao editar o exercício: " . $conn->error;
        }
        
        // Fecha a instrução e a conexão
        $stmt->close();
    }

    // Verifica se o formulário foi enviado para adicionar um exercício
    if (isset($_POST['adicionar'])) {
        $categoria = $_POST['categoria'];
        $nome = $_POST['nome'];
        $series = $_POST['series'];
        $repeticao = $_POST['repeticao'];
        $tempo = $_POST['tempo'];

        adicionarExercicio($categoria, $nome, $series, $repeticao, $tempo);
    }

    // Verifica se o formulário foi enviado para remover um exercício
    if (isset($_POST['remover'])) {
        $exercicioId = $_POST['exercicioId'];

        removerExercicio($exercicioId);
    }

    // Verifica se o formulário foi enviado para editar um exercício
    if (isset($_POST['editar'])) {
        $exercicioId = $_POST['exercicioId'];
        $categoria = $_POST['categoria'];
        $nome = $_POST['nome'];
        $series = $_POST['series'];
        $repeticao = $_POST['repeticao'];
        $tempo = $_POST['tempo'];

        editarExercicio($exercicioId, $categoria, $nome, $series, $repeticao, $tempo);
    }
    1
    // Fecha a conexão com o banco de dados
    ?>

</body>
</html>
