<?php
require_once("../connection/connection.php");

$id = $_POST['id'];
$nome = $_POST['nome'];
$escola = $_POST['escola'];
$patrimonio = $_POST['patrimonio'];
$descricao = $_POST['descricao'];

$sql = "UPDATE cad_equipamento SET equ_nome='$nome', equ_numeropatrimonio='$patrimonio', equ_descricao='$descricao', equ_esc_id='$escola' WHERE equ_id = $id";
mysqli_query($connection, $sql);

header("Location: ../../../system/equipamento.php?ed=1");
die();
