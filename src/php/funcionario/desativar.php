<?php
require_once("../connection/connection.php");

$id = $_GET['id'];

$sql = "UPDATE cad_funcionario SET func_ativo='0' WHERE func_id = $id";
mysqli_query($connection, $sql);

header("Location: ../../../system/funcionario.php?d=1");
die();
