<?php
require_once("../../connection/connection.php");

$id = $_POST['id'];
$nome = $_POST['nome'];
$valor = $_POST['valor'];
$duracao = $_POST['duracao'];
$observacao = $_POST['observacao'];

$valorSemFormato = preg_replace('/[^\d,]/', '', $valor);
$valorNumerico = str_replace(',', '.', $valorSemFormato);
$valor = $valorNumerico;

$sql = "UPDATE ser_servico SET ser_nome='$nome', ser_valor='$valor',
            ser_duracao='$duracao', ser_observacao='$observacao' WHERE ser_id = $id";

mysqli_query($connection, $sql);

header("Location: ../../../../admin/page/servico.php?c=1");
die();
