<?php
require_once('../../connection/connection.php');

$id = $_GET['id'];

$sql = "UPDATE os_agendamento SET age_status='0' WHERE age_id = $id";
mysqli_query($connection, $sql);

header("Location: ../../../../admin/page/agendamento.php?f=1");
die();