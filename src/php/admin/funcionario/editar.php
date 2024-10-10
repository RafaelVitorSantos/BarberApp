<?php
require_once("../../connection/connection.php");

$id = $_POST['id'];
$nome = $_POST['nome'];
$email = $_POST['email'];
$cpf = $_POST['cpf'];
$agendamentoativo = (isset($_POST['agendamentoativo'])) ? 1 : 0;
$senhaNova = $_POST['senhanova'];

if ($senhaNova != '') {
  $senhaNova = password_hash($senhaNova, PASSWORD_DEFAULT);
  $sql = "UPDATE cad_funcionario SET func_nome='$nome', func_email='$email', func_cpf='$cpf', func_senha='$senhaNova', func_agendamentoativo='$agendamentoativo' WHERE func_id = $id";
} else {
  $sql = "UPDATE cad_funcionario SET func_nome='$nome', func_email='$email', func_cpf='$cpf', func_agendamentoativo='$agendamentoativo' WHERE func_id = $id";
}
mysqli_query($connection, $sql);

header("Location: ../../../../admin/page/funcionario.php?ed=1");
die();
