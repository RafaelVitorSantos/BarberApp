<?php
require_once("../../connection/connection.php");

$id = $_POST['id'];
$nome = $_POST['nome'];
$cpfcnpj = $_POST['cpfcnpj'];
$telefone = $_POST['telefone'];
$observacao = $_POST['observacao'];

$sql = "UPDATE cad_fornecedor SET for_nome='$nome', for_telefone='$telefone',
            for_cpfcnpj='$cpfcnpj', for_observacao='$observacao' WHERE for_id = $id";

mysqli_query($connection, $sql);

header("Location: ../../../../admin/page/fornecedor.php?c=1");
die();
