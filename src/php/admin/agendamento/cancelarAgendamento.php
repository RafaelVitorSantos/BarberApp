<?php
require_once("../../connection/connection.php");

$id = $_GET['id'];

$sqlUpdate = "UPDATE os_agendamento SET age_status = 2 WHERE age_id = $id";
mysqli_query($connection, $sqlUpdate);

header("Location: ../../../../admin/page/calendario.php?c=1");
die();
