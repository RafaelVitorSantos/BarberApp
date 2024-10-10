<?php
require_once("../connection/connection.php");

$id = $_GET['id'];

$sql = "UPDATE cad_filial SET fil_ativo='0' WHERE fil_id = $id";
mysqli_query($connection, $sql);

header("Location: ../../../system/filial.php?d=1");
die();