<?php
require_once("../connection/connection.php");

$nome = $_POST['nome'];
$uf = $_POST['uf'];

$sql = "INSERT INTO cad_cidade(cid_nome, cid_uf_id) VALUES('$nome', '$uf')";
mysqli_query($connection, $sql);

header("Location: ../../../system/cidade.php?c=1");
die();
