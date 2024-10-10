<?php
require_once("../connection/connection.php");

$id = $_POST['id'];
$nome = $_POST['nome'];
$uf = $_POST['uf'];

$sql = "UPDATE cad_cidade SET cid_nome='$nome', cid_uf_id='$uf' WHERE cid_id = $id";
mysqli_query($connection, $sql);

header("Location: ../../../system/cidade.php?u=1");
die();
