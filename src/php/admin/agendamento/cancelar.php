<?php
require_once('../../connection/connection.php');

$id = $_GET['id'];

$sql = "UPDATE os_agendamento SET age_status='2' WHERE age_id = $id";
mysqli_query($connection, $sql);

header("Location: ../../../../admin/page/agendamento.php?d=1");
die();