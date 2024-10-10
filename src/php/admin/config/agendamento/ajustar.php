<?php
require_once("../../../connection/connection.php");

$intervalo = $_POST['intervalo'];
$status = $_POST['status'];
$mensagempadrao = $_POST['mensagempadrao'];

$sqlUpdate = "UPDATE cad_config SET config_intervalo='$intervalo:00', config_status='$status', config_mensagempadrao='$mensagempadrao' WHERE config_id = 1";
mysqli_query($connection, $sqlUpdate);

header("Location: ../../../../../admin/page/index.php");
die();
