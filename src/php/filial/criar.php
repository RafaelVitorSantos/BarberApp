<?php
require_once("../connection/connection.php");

$nome = $_POST['nome'];
$cnpj = $_POST['cnpj'];

$sql = "INSERT INTO cad_filial(fil_nome, fil_cnpj) VALUES('$nome','$cnpj')";
mysqli_query($connection, $sql);

header("Location: ../../../system/filial.php?s=1");
die();
