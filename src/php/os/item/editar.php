<?php
require_once("../../connection/connection.php");

$id = $_POST['id'];
$servico = $_POST['servico'];
$valor = $_POST['valor'];
$equipamento = $_POST['equipamento'];

$sqlUpdate = "UPDATE os_ordemservicoitem SET osi_ser_id='$servico', osi_equ_id='$equipamento', osi_valor='$valor' WHERE osi_id = $id";
mysqli_query($connection, $sqlUpdate);

$sql = "SELECT * FROM os_ordemservicoitem WHERE osi_id = $id";
$query = mysqli_query($connection, $sql);
$dados = mysqli_fetch_array($query);

$id = $dados['osi_os_id'];

header("Location: ../../../../system/os.php?id=$id&nvl=1");
die();
