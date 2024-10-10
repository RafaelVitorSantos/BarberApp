<?php
require_once("../../connection/connection.php");

$id = $_GET['id'];

$sql = "UPDATE os_ordemservicoitem SET osi_ativo = 0 WHERE osi_id = $id";
mysqli_query($connection, $sql);

$sql = "SELECT * FROM os_ordemservicoitem WHERE osi_id = $id";
$query = mysqli_query($connection, $sql);
$dados = mysqli_fetch_array($query);
$id = $dados['osi_os_id'];

header("Location: ../../../../system/os.php?id=$id&nvl=1");
die();
