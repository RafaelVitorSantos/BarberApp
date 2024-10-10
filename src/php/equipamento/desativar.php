<?php
require_once("../connection/connection.php");

$id = $_GET['id'];

$sql = "UPDATE cad_equipamento SET equ_ativo='0' WHERE equ_id = $id";
mysqli_query($connection, $sql);

header("Location: ../../../system/equipamento.php?d=1");
die();