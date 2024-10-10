<?php

require_once("../connection/connection.php");
session_start();

$email = $_POST['email'];
$senha = $_POST['senha'];

$sql = "SELECT func_nome, func_email, func_senha, func_id FROM cad_funcionario WHERE upper(func_email) LIKE upper('$email') AND func_ativo = 1";
$query = mysqli_query($connection, $sql);
$row = mysqli_num_rows($query);

if ($row > 0) {
  while ($dados = mysqli_fetch_array($query)) {
    if (password_verify($senha, $dados['func_senha'])) {
      $_SESSION['email'] = $dados['func_email'];
      $_SESSION['id'] = $dados['func_id'];

      header("Location: ../../../system/index.php");
      die();
    }
  }

  header("Location: ../../../index.php?error");
  die();
} else {
  header("Location: ../../../index.php?error");
  die();
}
