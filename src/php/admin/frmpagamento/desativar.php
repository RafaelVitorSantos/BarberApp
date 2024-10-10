<?php
require_once("../../connection/connection.php");

$id = $_GET['id'];

$sql = "UPDATE fin_frmpagamento SET frmpag_ativo='0' WHERE frmpag_id = $id";
mysqli_query($connection, $sql);

header("Location: ../../../../admin/page/frmpagamento.php?d=1");
die();
