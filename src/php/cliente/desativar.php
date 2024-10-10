<?php
require_once("../connection/connection.php");

$id = $_GET['id'];

$sql = "UPDATE cad_cliente SET cli_ativo='0' WHERE cli_id = $id";
mysqli_query($connection, $sql);

header("Location: ../../../system/cliente.php?d=1");
die();
