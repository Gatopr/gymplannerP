<?php
session_start();

$servidor = "localhost";
$usuario = "adim";
$senha = "1212";
$banco = "GYMPLANNER";

$conexao = mysqli_connect($servidor, $usuario, $senha, $banco);

if (!$conexao) {
	die("Falha na conexão: " . mysqli_connect_error());
}

$email = $_SESSION["email"];

$consulta = "SELECT * FROM usuarios WHERE email = '$email'";
$resultado = mysqli_query($conexao, $consulta);

if (mysqli_num_rows($resultado) == 1) {
	$usuario = mysqli_fetch_assoc($resultado);
} else {
	header("Location: ../index.php");
	exit();
}

mysqli_close($conexao);
?>
<!DOCTYPE html>
<html>

<head>
	<title>Editar Informações</title>

	<!-- Adicione aqui os links para os arquivos CSS e fontes se necessário -->
</head>

<body>
	<!-- Adicione aqui o código HTML do formulário -->
	<form method="POST" action="salvar_info.php">
		<label for="nome">Nome:</label>
		<input type="text" name="nome" id="nome" value="<?php echo $usuario["nome"]; ?>"><br>

		<label for="email">E-mail:</label>
		<input type="email" name="email" id="email" value="<?php echo $usuario["email"]; ?>"><br>

		<label for="nascimento">Data de Nascimento:</label>
		<input type="date" name="nascimento" id="nascimento" value="<?php echo $usuario["nascimento"]; ?>"><br>

		<label for="sexo">Sexo:</label>
		<select name="sexo" id="sexo">
			<option value="masculino" <?php if ($usuario["sexo"] === "masculino") echo "selected"; ?>>Masculino</option>
			<option value="feminino" <?php if ($usuario["sexo"] === "feminino") echo "selected"; ?>>Feminino</option>
			<option value="outro" <?php if ($usuario["sexo"] === "outro") echo "selected"; ?>>Outro</option>
		</select><br>

		<!-- Adicione os campos restantes para os outros dados do usuário -->

		<button type="submit">Salvar</button>
	</form>
</body>

</html>
