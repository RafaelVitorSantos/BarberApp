<?php
require_once("../connection/connection.php");

$id = $_POST['id'];
$nome = $_POST['nome'];
$endereco = $_POST['endereco'];
$numero = $_POST['numero'];
$cidade = $_POST['cidade'];
$cep = $_POST['cep'];
$telefone = $_POST['telefone'];
$email = $_POST['email'];
$cliente = $_POST['cliente'];
$descricao = $_POST['descricao'];

$sql = "UPDATE cad_escola SET esc_nome='$nome', esc_endereco='$endereco', esc_numero='$numero', esc_cid_id='$cidade',
esc_cep='$cep', esc_telefone='$telefone', esc_email='$email', esc_cli_id='$cliente', esc_descricao='$descricao' WHERE esc_id = $id";
mysqli_query($connection, $sql);

header("Location: ../../../system/escola.php?ed=1");
die();
