<?php 
require_once("../connection/connection.php");

$nome = $_POST['nome'];
$telefone = $_POST['telefone'];
$email = $_POST['email'];
$cep = $_POST['cep'];
$numero = $_POST['numero'];
$endereco = $_POST['endereco'];
$cidade = $_POST['cidade'];
$descricao = $_POST['descricao'];

$sql = "INSERT INTO cad_cliente(cli_nome, cli_telefone, cli_email, cli_cep, cli_numero, cli_endereco, cli_cid_id, cli_descricao)
            VALUES('$nome','$telefone','$email','$cep','$numero','$endereco','$cidade','$descricao')";
mysqli_query($connection, $sql);

header("Location: ../../../system/cliente.php?c=1");
die();