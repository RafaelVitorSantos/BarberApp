<?php
require_once("../../connection/connection.php");

$id = $_GET['id'];

$sql = "UPDATE cad_login SET log_ativo='0' WHERE log_id = $id";
mysqli_query($connection, $sql);

header("Location: ../../../../admin/page/administrador.php?d=1");
die();
