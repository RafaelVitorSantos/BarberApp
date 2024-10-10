<?php 
require_once("../connection/connection.php");

$nome = $_POST['nome'];
$telefone = $_POST['telefone'];
$email = $_POST['email'];
$cep = $_POST['cep'];
$numero = $_POST['numero'];
$endereco = $_POST['endereco'];
$cidade = $_POST['cidade'];
$cliente = $_POST['cliente'];
$descricao = $_POST['descricao'];

$sql = "INSERT INTO cad_escola(esc_nome, esc_telefone, esc_email, esc_cep, esc_numero, esc_endereco, esc_cid_id, esc_cli_id, esc_descricao)
            VALUES('$nome','$telefone','$email','$cep','$numero','$endereco','$cidade', '$cliente', '$descricao')";
mysqli_query($connection, $sql);

header("Location: ../../../system/escola.php?c=1");
die();