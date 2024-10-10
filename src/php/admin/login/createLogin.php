<?php
require_once("../../connection/connection.php");

$nome = $_POST['nome'];
$email = $_POST['email'];
$senha = $_POST['senha'];
$senha = password_hash($senha, PASSWORD_DEFAULT);

$sqlEmail = "SELECT * FROM cad_usuario WHERE upper(usu_email) LIKE upper('$email')";
$queryEmail = mysqli_query($connection, $sqlEmail);
$rowEmail = mysqli_num_rows($queryEmail);

if ($rowEmail == 0) {
    $sqlInsert = "INSERT INTO cad_usuario(usu_nome, usu_email, usu_senha) VALUES('$nome','$email','$senha')";
    mysqli_query($connection, $sqlInsert);
    ini_set('session.cache_expire', 20000);
    ini_set('session.cache_limiter', 'none');
    ini_set('session.cookie_lifetime', 94608);
    ini_set('session.gc_maxlifetime', 94608);
    session_start();

    $sqlUser = "SELECT * FROM cad_usuario WHERE upper(usu_email) LIKE upper('$email')";
    $queryUser = mysqli_query($connection, $sqlUser);
    $rowUser = mysqli_num_rows($queryUser);

    if ($rowUser == 1) {
        $dadosUser = mysqli_fetch_array($queryUser);
        $_SESSION['barber_client_id'] = $dadosUser['usu_id'];
        $_SESSION['barber_client_email'] = $dadosUser['usu_email'];
        $_SESSION['barber_client_name'] = $dadosUser['usu_nome'];

        header("Location: ../../../../client/page/index.php");
        die();
    }
}

header("Location: ../../../../client/index.php?error");
die();
