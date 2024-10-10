<?php
require_once("../connection/connection.php");

$id = $_GET['id'];

$sql = "UPDATE cad_escola SET esc_ativo='0' WHERE esc_id = $id";
mysqli_query($connection, $sql);

header("Location: ../../../system/escola.php?d=1");
die();
