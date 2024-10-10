<?php
require_once("../connection/connection.php");

$id = $_GET['id'];

$sqlOS = "UPDATE os_ordemservico SET os_ativo='0' WHERE os_id = $id";
mysqli_query($connection, $sqlOS);

$sqlCalendario = "UPDATE os_calendario SET cal_ativo='0' WHERE cal_os_id = $id";
mysqli_query($connection, $sqlCalendario);

$sqlItem = "UPDATE os_ordemservicoitem SET osi_ativo='0' WHERE osi_os_id = $id";
mysqli_query($connection, $sqlItem);

header("Location: ../../../system/os.php?d=1");
die();
