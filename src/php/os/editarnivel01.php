<?php

require_once("../connection/connection.php");

$titulo = $_POST['titulo'];
$datainicial = $_POST['datainicial'];
$datafinal = $_POST['datafinal'];
$horarioinicial = $_POST['horarioinicial'];
$horariofinal = $_POST['horariofinal'];
$responsavel = $_POST['responsavel'];
$tipo = $_POST['tipo'];
$observacao = $_POST['observacao'];
$cliente = $_POST['cliente'];
$escola = $_POST['escola'];
$id = $_POST['id'];

$sql = "UPDATE os_ordemservico SET os_titulo='$titulo', os_datainicial='$datainicial', os_horarioinicial='$horarioinicial',
        os_horariofinal='$horariofinal', os_datafinal='$datafinal', os_func_id='$responsavel', os_cli_id='$cliente', os_esc_id='$escola', os_tipo ='$tipo', os_observacao='$observacao' WHERE os_id = $id";
mysqli_query($connection, $sql);

header("Location: ../../../system/os.php?id=$id&nvl=1");
die();
