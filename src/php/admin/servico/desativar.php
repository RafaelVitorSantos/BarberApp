<?php
require_once("../../connection/connection.php");

$id = $_GET['id'];

$sql = "UPDATE ser_servico SET ser_ativo='0' WHERE ser_id = $id";
mysqli_query($connection, $sql);

header("Location: ../../../../admin/page/servico.php?d=1");
die();
