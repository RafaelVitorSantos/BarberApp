<?php
require_once("../../connection/connection.php");

$nome = $_POST['nome'];
$valor = $_POST['valor'];
$duracao = $_POST['duracao'];
$observacao = $_POST['observacao'];

$valorSemFormato = preg_replace('/[^\d,]/', '', $valor);
$valorNumerico = str_replace(',', '.', $valorSemFormato);
$valor = $valorNumerico;

$sql = "INSERT INTO ser_servico(ser_nome, ser_valor, ser_duracao, ser_observacao)
        VALUES('$nome','$valor', '$duracao', '$observacao')";
mysqli_query($connection, $sql);

header("Location: ../../../../admin/page/servico.php?c=1");
die();
