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
$descricao = $_POST['descricao'];

$sql = "UPDATE cad_cliente SET cli_nome='$nome', cli_endereco='$endereco', cli_numero='$numero', cli_cid_id='$cidade',
cli_cep='$cep', cli_telefone='$telefone', cli_email='$email', cli_descricao='$descricao' WHERE cli_id = $id";
mysqli_query($connection, $sql);

header("Location: ../../../system/cliente.php?ed=1");
die();