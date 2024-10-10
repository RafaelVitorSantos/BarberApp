<?php
require_once("../connection/connection.php");

$id = $_POST['id'];
$nome = $_POST['nome'];
$descricao = $_POST['descricao'];
$valor = $_POST['valor'];

$sql = "UPDATE cad_servico SET ser_nome='$nome', ser_descricao='$descricao', ser_valor='$valor' WHERE ser_id = $id";
mysqli_query($connection, $sql);

header("Location: ../../../system/servico.php?ed=1");
die();
