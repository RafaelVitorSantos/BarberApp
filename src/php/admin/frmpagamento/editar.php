<?php
require_once("../../connection/connection.php");

$id = $_POST['id'];
$nome = $_POST['nome'];
$qtdeparcelamin = $_POST['qtdeparcelamin'];
$qtdeparcelamax = $_POST['qtdeparcelamax'];
$taxa = $_POST['taxa'];

$sql = "UPDATE fin_frmpagamento SET frmpag_nome='$nome', frmpag_qtdeminparcela='$qtdeparcelamin',
            frmpag_qtdemaxparcela='$qtdeparcelamax', frmpag_taxa='$taxa' WHERE frmpag_id = $id";

mysqli_query($connection, $sql);

header("Location: ../../../../admin/page/frmpagamento.php?c=1");
die();
