<?php
require_once("../../connection/connection.php");

$id = $_GET['id'];

$sqlUpdate = "UPDATE os_agendamento SET age_status = 0 WHERE age_id = $id";
mysqli_query($connection, $sqlUpdate);

header("Location: ../../../../admin/page/calendario.php?f=1");
die();
