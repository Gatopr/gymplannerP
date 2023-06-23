<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/menu_navegacao.css">
    <link rel="stylesheet" type="text/css" href="../css/montar_cronograma.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="favicon.ico">

    <title>Cronograma</title>
</head>

<body class="corpo">

    <nav>
        <div class="nav-bar">
            <a class="menu-inicial" href="tela_inicial.php">Início</a>
            <a href="ver_exercicios.php">Exercícios</a>
            <a href="saiba_mais.php">Saiba Mais</a>
            <a href="calcular_imc.php">IMC</a>
        </div>
        <div>
            <a href="perfil_usuario.php"><img src="https://img.wattpad.com/2847a54156b8585551507c322a26d2c58a487e0f/68747470733a2f2f73332e616d617a6f6e6177732e636f6d2f776174747061642d6d656469612d736572766963652f53746f7279496d6167652f726350555f6446443230756d76513d3d2d3839303633343438322e313631663730643738383739386364353930343838353933353730302e6a7067?s=fit&w=720&h=720" alt="Imagem do usuário"></a>
        </div>
    </nav>

    <h3>Tabela de Exercícios dos Usuários:</h3>
    <table id="tabela-exercicios">
        <tr>
            <th>Categoria</th>
            <th>Exercício</th>
            <th>Série</th>
            <th>Repetições</th>
            <th>Tempo</th>
            <th><a href=/php/cronograma_editar.php>Editar</a></th>
        </tr>

        <?php
        session_start();
        // Conexão com o banco de dados
        $servername = "localhost";
        $username = "adim";
        $password = "1212";
        $dbname = "GYMPLANNER";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Falha na conexão: " . $conn->connect_error);
        }

        // Consulta SQL para obter os dados da tabela exercicios_usuarios
        $sql = "SELECT * FROM exercicios_usuarios";
        $result = $conn->query($sql);

        // Verifica se há resultados
        if ($result->num_rows > 0) {
            // Loop através dos resultados e adiciona-os ao array $exerciciosUsuarios
            $exerciciosUsuarios = array();
            while ($row = $result->fetch_assoc()) {
                $exercicioUsuario = array(
                    'id' => $row['id'],
                    'categoria' => $row['categoria'],
                    'nome' => $row['nome'],
                    'series' => $row['series'],
                    'repeticoes' => $row['repeticoes'],
                    'tempo' => $row['tempo'],
                    'usuario_id' => $row['usuario_id']
                );
                $exerciciosUsuarios[] = $exercicioUsuario;
            }

            // Exibe os dados na tabela
            foreach ($exerciciosUsuarios as $exercicioUsuario) {
                if ($exercicioUsuario['usuario_id'] == $_SESSION['usuario_id']) {
                    echo "<table id='exercicios_usuarios'>";
                    echo "<tr>";
                    echo "<td class= xx>" . $exercicioUsuario['categoria'] . "</td>";
                    echo "<td class = xx>" . $exercicioUsuario['nome'] . "</td>";
                    echo "<td class = xx>>" . $exercicioUsuario['series'] . "</td>";
                    echo "<td class = xx>>" . $exercicioUsuario['repeticoes'] . "</td>";
                    echo "<td class = xx>>" . $exercicioUsuario['tempo'] . "</td>";
                    echo "<td class = xx>>" . $exercicioUsuario['usuario_id'] . "</td>";
                    echo "<td><button data-id='" . $exercicioUsuario['id'] . "' onclick=\"excluirExercicio(event)\">Excluir</button></td>";
                    echo "</tr>";
                    echo"</table>";
                } 
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categoria = $_POST['categoria'];
    $exercicioId = $_POST['exercicio'];
    $series = $_POST['series'];
    $repeticoes = $_POST['repeticao'];
    $tempo = $_POST['tempo'];
    
    // Recupere o ID do usuário da sessão ou autenticação
    $usuarioId = $_SESSION["usuario_id"];

    $sql = "INSERT INTO exercicios_usuarios (categoria, nome, series, repeticoes, tempo, usuario_id, exercicios_id, email)
            SELECT ?, nome, ?, ?, ?, ?, ?, ? FROM exercicios WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siiisiis", $categoria, $series, $repeticoes, $tempo, $usuarioId, $exercicioId, $_SESSION["email"], $exercicioId);
    $stmt->execute();
    $stmt->close();
}

        
    ?>

    </table>

    <?php

    $sql = "SELECT * FROM exercicios_usuarios";
    $result = $conn->query($sql);

    $exerciciosUsuarios = array();
    if ($conn->connect_error) {
        die("Falha na conexão: " . $conn->connect_error);
    }
///////////////////////////
////////////////////    

    $categoria = isset($_GET['categoria']) ? $_GET['categoria'] : '';

    $sql = "SELECT DISTINCT categoria FROM exercicios";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        echo '<form method="POST" action="">';
        echo '<select id="categoria" name="categoria">';
        echo '<option value="">Selecione uma categoria</option>';
        while ($row = $result->fetch_assoc()) {
            $selected = $categoria === $row['categoria'] ? 'selected' : '';
            echo '<option value="' . $row['categoria'] . '" ' . $selected . '>' . $row['categoria'] . '</option>';
        }
        echo '</select>';
    }
        $exercicios = array();
        if (!empty($categoria)) {
            $sql = "SELECT * FROM exercicios WHERE categoria = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $categoria);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $exercicio = array(
                        'id' => $row['id'],
                        'nome' => $row['nome'],
                        'series' => $row['series'],
                        'repeticao' => $row['repeticao'],
                        'tempo' => $row['tempo']
                    );
                    array_push($exercicios, $exercicio);
                }
            }
            $stmt->close();
        }

        $conn->close();
        ?>
        <br>
        <h3>Escolha o Exercício:</h3>
        <select id="exercicio" name="exercicio">
            <?php
            foreach ($exercicios as $exercicio) {
                echo '<option value="' . $exercicio['id'] . '">' . $exercicio['nome'] . '</option>';
            }
            ?>
        </select>

        <br>
        <h3>Escolha a Série:</h3>
        <br>

        <input type="number" name="series" value="<?php echo isset($exercicio['series']) ? $exercicio['series'] : ''; ?>">

        <br>
        <h3>Escolha a Repetição:</h3>
        <input type="number" name="repeticao" value="<?php echo isset($exercicio['repeticao']) ? $exercicio['repeticao'] : ''; ?>">

        <br>
        <h3>Escolha o Tempo:</h3>
        <input type="number" name="tempo" value="<?php echo isset($exercicio['tempo']) ? $exercicio['tempo'] : ''; ?>">

        <br><br>
        <button onclick="minhaFuncao()">Clique aqui</button>
        </form>
        <?php

    ?>

    </main>

    <script>


///
function excluirExercicio(event) {
    if (confirm("Tem certeza de que deseja excluir este exercício?")) {
        var exercicioId = event.target.dataset.id; // Obtém o ID do exercício

        // Faz uma requisição AJAX para excluir o exercício do banco de dados
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {
                // Atualiza a tabela removendo a linha correspondente ao exercício excluído
                var tabelaExercicios = document.getElementById('exercicios_usuarios');
                var linhas = tabelaExercicios.getElementsByTagName('tr');
                for (var i = 0; i < linhas.length; i++) {
                    var celulaId = linhas[i].getElementsByTagName('td')[0];
                    if (celulaId && celulaId.textContent == exercicioId) {
                        tabelaExercicios.deleteRow(i);
                        break;
                    }
                }
                alert(this.responseText); // Exibe a resposta de sucesso ou erro
            }
        };
        xhttp.open("POST", "cronograma_excluir.php", true); // Substitua "nome_do_arquivo.php" pelo nome do arquivo PHP que processa a exclusão
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("id=" + exercicioId); // Envia o ID do exercício para o arquivo PHP
    }
}

///
// Função para adicionar o exercício selecionado à tabela e atualizar a página
function adicionarExercicio() {
  var exercicioSelect = document.getElementById('exercicio');
  var exercicioSelecionado = exercicioSelect.options[exercicioSelect.selectedIndex].text;

  var tabela = document.querySelector('.tabela table');
  var novaLinha = tabela.insertRow();

  var colunaGrupoMuscular = novaLinha.insertCell();
  var colunaExercicio = novaLinha.insertCell();
  var colunaSerieRepeticao = novaLinha.insertCell();
  var colunaDuracao = novaLinha.insertCell();
  var colunaEmail = novaLinha.insertCell();

  colunaGrupoMuscular.textContent = document.getElementById('categoria').value;
  colunaExercicio.textContent = exercicioSelecionado;
  colunaSerieRepeticao.textContent = document.getElementById('serie').value + ' x ' + document.getElementById('repeticao').value;
  colunaDuracao.textContent = document.getElementById('tempo').value + ' min';
  colunaEmail.textContent = document.getElementById('email').value;

  // Limpa os campos de seleção
  exercicioSelect.selectedIndex = 0;
  document.getElementById('serie').selectedIndex = 0;
  document.getElementById('repeticao').selectedIndex = 0;
  document.getElementById('tempo').selectedIndex = 0;
  document.getElementById('email').value = '';

  // Atualiza a página
  location.reload();
}



        // Função para adicionar o exercício selecionado à tabela
        function adicionarExercicio() {
            var exercicioSelect = document.getElementById('exercicio');
            var exercicioSelecionado = exercicioSelect.options[exercicioSelect.selectedIndex].text;

            var tabela = document.querySelector('.tabela table');
            var novaLinha = tabela.insertRow();

            var colunaGrupoMuscular = novaLinha.insertCell();
            var colunaExercicio = novaLinha.insertCell();
            var colunaSerieRepeticao = novaLinha.insertCell();
            var colunaDuracao = novaLinha.insertCell();
            var colunaEmail = novaLinha.insertCell();

            colunaGrupoMuscular.textContent = document.getElementById('categoria').value;
            colunaExercicio.textContent = exercicioSelecionado;
            colunaSerieRepeticao.textContent = document.getElementById('serie').value + ' x ' + document.getElementById('repeticao').value;
            colunaDuracao.textContent = document.getElementById('tempo').value + ' min';
            colunaEmail.textContent = document.getElementById('email').value;

            // Limpa os campos de seleção
            exercicioSelect.selectedIndex = 0;
            document.getElementById('serie').selectedIndex = 0;
            document.getElementById('repeticao').selectedIndex = 0;
            document.getElementById('tempo').selectedIndex = 0;
            document.getElementById('email').value = '';
        }

        document.getElementById('categoria').addEventListener('change', function() {
            var categoria = document.getElementById('categoria').value; // Obtém a categoria selecionada
            location.href = 'montar_cronograma.php?categoria=' + encodeURIComponent(categoria);
        });
        function adicionarExercicio() {
        // Código JavaScript existente

        // Limpar os campos após adicionar o exercício
        document.getElementById('exercicio').selectedIndex = 0;
        document.getElementById('categoria').selectedIndex = 0;
        document.querySelector('input[name="series"]').value = "";
        document.querySelector('input[name="repeticao"]').value = "";
        document.querySelector('input[name="tempo"]').value = "";

        // Atualizar a página
        location.reload();
    }
    </script>
</body>

</html>