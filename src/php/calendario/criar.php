<?php
require_once("../connection/connection.php");

$data = $_POST['data'];
$nome = $_POST['nome'];
$os = $_POST['os'];

$sql = "INSERT INTO os_calendario(cal_nome, cal_data, cal_os_id) VALUES('$nome','$data','$os')";
mysqli_query($connection, $sql);

header("Location: ../../../system/calendario.php?s=1");
die();