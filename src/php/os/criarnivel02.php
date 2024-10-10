<?php
require_once("../connection/connection.php");

$id = $_POST['id'];
$valor = $_POST['valor'];
$servico = $_POST['servico'];
$equipamento = $_POST['equipamento'];

$sql = "INSERT INTO os_ordemservicoitem(osi_os_id, osi_ser_id, osi_equ_id, osi_valor) VALUES('$id','$servico', '$equipamento','$valor')";
mysqli_query($connection, $sql);

header("Location: ../../../system/os.php?id=$id&nvl=1");
die();