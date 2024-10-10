<?php
require_once("../../connection/connection.php");

$id = $_GET['id'];

$sql = "UPDATE cad_fornecedor SET for_ativo='0' WHERE for_id = $id";
mysqli_query($connection, $sql);

header("Location: ../../../../admin/page/fornecedor.php?d=1");
die();
