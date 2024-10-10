<?php
require_once("../connection/connection.php");

$id = $_POST['id'];
$nome = $_POST['nome'];
$cnpj = $_POST['cnpj'];

$sql = "UPDATE cad_filial SET fil_nome='$nome', fil_cnpj='$cnpj' WHERE fil_id = $id";
mysqli_query($connection, $sql);

header("Location: ../../../system/filial.php?ed=1");
die();
