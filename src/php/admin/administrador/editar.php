<?php
require_once("../../connection/connection.php");

$id = $_POST['id'];
$nome = $_POST['nome'];
$email = $_POST['email'];
$cpf = $_POST['cpf'];
$senha = $_POST['senha'];
$senhaNova = $_POST['senhanova'];

$sqlValida = "SELECT log_senha FROM cad_login WHERE log_id = $id LIMIT 1";
$queryValida = mysqli_query($connection, $sqlValida);
$dadosValida = mysqli_fetch_array($queryValida);

if (password_verify($senha, $dadosValida['log_senha'])) {
  if ($senhaNova != '') {
    $senhaNova = password_hash($senhaNova, PASSWORD_DEFAULT);
    $sql = "UPDATE cad_login SET log_nome='$nome', log_email='$email', log_cpf='$cpf', log_senha='$senhaNova' WHERE log_id = $id";
  } else {
    $sql = "UPDATE cad_login SET log_nome='$nome', log_email='$email', log_cpf='$cpf' WHERE log_id = $id";
  }
  mysqli_query($connection, $sql);

  header("Location: ../../../../admin/page/administrador.php?ed=1");
  die();
}

header("Location: ../../../../admin/page/administrador.php?edit=$id&error=1");
die();
