<?php
require_once("../connection/connection.php");

$id = $_GET['id'];

$sql = "UPDATE cad_servico SET ser_ativo='0' WHERE ser_id = $id";
mysqli_query($connection, $sql);

header("Location: ../../../system/servico.php?d=1");
die();
