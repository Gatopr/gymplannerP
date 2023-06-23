<?php
session_start();

$servername = "localhost";
$username = "adim";
$password = "1212";
$dbname = "GYMPLANNER";

$conexao = mysqli_connect($servername, $username, $password, $dbname);

if (!$conexao) {
	die("Falha na conexão: " . mysqli_connect_error());
}

$email = $_SESSION["email"];

$consulta = "SELECT * FROM usuarios WHERE email = '$email'";
$resultado = mysqli_query($conexao, $consulta);

if (mysqli_num_rows($resultado) == 1) {
	$usuario = mysqli_fetch_assoc($resultado);
} else {
	header("Location: perfil_usuario.php");
	exit();
}

mysqli_close($conexao);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$novoNome = $_POST["nome"];
	$novoEmail = $_POST["email"];
	$novoNascimento = $_POST["nascimento"];
	$novoSexo = $_POST["sexo"];

	$conexao = mysqli_connect($servername, $username, $password, $dbname);
	$update_query = "UPDATE usuarios SET nome = '$novoNome', email = '$novoEmail', nascimento = '$novoNascimento', sexo = '$novoSexo' WHERE email = '$email'";
	mysqli_query($conexao, $update_query);
	mysqli_close($conexao);

	header("Location: perfil_usuario.php");
	exit();
}
?>
