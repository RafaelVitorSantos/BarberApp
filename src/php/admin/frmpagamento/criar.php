<?php
require_once("../../connection/connection.php");

$nome = $_POST['nome'];
$qtdeparcelamin = $_POST['qtdeparcelamin'];
$qtdeparcelamax = $_POST['qtdeparcelamax'];
$taxa = $_POST['taxa'];

$sql = "INSERT INTO fin_frmpagamento(frmpag_nome, frmpag_qtdeminparcela, frmpag_qtdemaxparcela, frmpag_taxa)
        VALUES('$nome','$qtdeparcelamin', '$qtdeparcelamax', '$taxa')";
mysqli_query($connection, $sql);

header("Location: ../../../../admin/page/frmpagamento.php?c=1");
die();
