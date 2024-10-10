<?php
require_once("../../connection/connection.php");

$id = $_GET['id'];

$sql = "UPDATE cad_usuario SET usu_ativo='0' WHERE usu_id = $id";
mysqli_query($connection, $sql);

header("Location: ../../../../admin/page/cliente.php?d=1");
die();
