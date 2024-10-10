<?php
require_once("../../connection/connection.php");

$nome = $_POST['nome'];
$telefone = $_POST['telefone'];
$email = $_POST['email'];

$sqlEmail = "SELECT * FROM cad_usuario WHERE upper(usu_email) LIKE upper('$email')";
$queryEmail = mysqli_query($connection, $sqlEmail);
$rowEmail = mysqli_num_rows($queryEmail);

if ($rowEmail == 0) {
        $sqlInsert = "INSERT INTO cad_usuario(usu_nome, usu_telefone, usu_email) VALUES('$nome', '$telefone', '$email')";
        mysqli_query($connection, $sqlInsert);

        header("Location: ../../../../admin/page/cliente.php?c=1");
        die();
}

header("Location: ../../../../admin/page/cliente.php?error");
die();
