<?php
require_once("../connection/connection.php");

$id = $_GET['id'];

$sql = "UPDATE cad_cidade SET cid_ativo='0' WHERE cid_id = $id";
mysqli_query($connection, $sql);

header("Location: ../../../system/cidade.php?d=1");
die();
