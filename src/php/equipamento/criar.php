<?php
require_once("../connection/connection.php");

$nome = $_POST['nome'];
$descricao = $_POST['descricao'];
$escola = $_POST['escola'];
$patrimonio = $_POST['patrimonio'];

$sql = "INSERT INTO cad_equipamento(equ_nome, equ_numeropatrimonio, equ_descricao, equ_esc_id) VALUES('$nome','$patrimonio','$descricao', '$escola')";
mysqli_query($connection, $sql);

header("Location: ../../../system/equipamento.php?s=1");
die();
