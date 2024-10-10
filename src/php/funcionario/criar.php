<?php
require_once("../connection/connection.php");

$nome = $_POST['nome'];
$email = $_POST['email'];
$senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
$cpf = $_POST['cpf'];

$sql = "INSERT INTO cad_funcionario(func_nome, func_email, func_senha, func_cpf) VALUES('$nome','$email','$senha','$cpf')";
mysqli_query($connection, $sql);

header("Location: ../../../system/funcionario.php?c=1");
die();