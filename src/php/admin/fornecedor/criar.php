<?php
require_once("../../connection/connection.php");

$nome = $_POST['nome'];
$cpfcnpj = $_POST['cpfcnpj'];
$telefone = $_POST['telefone'];
$observacao = $_POST['observacao'];

$sql = "INSERT INTO cad_fornecedor(for_nome, for_cpfcnpj, for_telefone, for_observacao)
        VALUES('$nome','$cpfcnpj', '$telefone', '$observacao')";
mysqli_query($connection, $sql);

header("Location: ../../../../admin/page/fornecedor.php?c=1");
die();
