<?php
require_once("../connection/connection.php");

$id = $_POST['id'];
$nome = $_POST['nome'];
$email = $_POST['email'];
$cpf = $_POST['cpf'];
$senha = $_POST['senha'];
$senhaNova = $_POST['senhanova'];

$sqlValida = "SELECT func_senha FROM cad_funcionario WHERE func_id = $id LIMIT 1";
$queryValida = mysqli_query($connection, $sqlValida);
$dadosValida = mysqli_fetch_array($queryValida);

if (password_verify($senha, $dadosValida['func_senha'])) {
  if ($senhaNova != '') {
    $senhaNova = password_hash($senhaNova, PASSWORD_DEFAULT);
    $sql = "UPDATE cad_funcionario SET func_nome='$nome', func_email='$email', func_cpf='$cpf', func_senha='$senhaNova' WHERE func_id = $id";
  } else {
    $sql = "UPDATE cad_funcionario SET func_nome='$nome', func_email='$email', func_cpf='$cpf' WHERE func_id = $id";
  }
  mysqli_query($connection, $sql);

  header("Location: ../../../system/funcionario.php?ed=1");
  die();
}

header("Location: ../../../system/funcionario.php?edit=$id&error=1");
die();
