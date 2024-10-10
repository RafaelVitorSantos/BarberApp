<?php
require_once("../connection/connection.php");

$nome = $_POST['nome'];
$descricao = $_POST['descricao'];
$valor = $_POST['valor'];

$sql = "INSERT INTO cad_servico(ser_nome, ser_valor, ser_descricao) VALUES('$nome', '$valor', '$descricao')";
mysqli_query($connection, $sql);

header("Location: ../../../system/servico.php?c=1");
die();
