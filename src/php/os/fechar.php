<?php
require_once("../connection/connection.php");

$id = $_GET['id'];

$sql = "UPDATE os_ordemservico SET os_status = '3' WHERE os_id = $id";
mysqli_query($connection, $sql);

header("Location: ../../../system/os.php");
die();
