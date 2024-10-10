<?php
require_once("../../connection/connection.php");

$email = $_POST['email'];
$senha = $_POST['senha'];

$sqlEmail = "SELECT * FROM cad_usuario WHERE upper(usu_email) LIKE upper('$email') AND usu_ativo = 1";
$queryEmail = mysqli_query($connection, $sqlEmail);
$rowEmail = mysqli_num_rows($queryEmail);

if ($rowEmail == 1) {
    $dadosEmail = mysqli_fetch_array($queryEmail);
    if (password_verify($senha, $dadosEmail['usu_senha'])) {
        ini_set('session.cache_expire', 20000);
        ini_set('session.cache_limiter', 'none');
        ini_set('session.cookie_lifetime', 94608);
        ini_set('session.gc_maxlifetime', 94608);
        session_start();

        $_SESSION['barber_client_id'] = $dadosEmail['usu_id'];
        $_SESSION['barber_client_telefone'] = $dadosUser['usu_telefone'];
        $_SESSION['barber_client_email'] = $dadosEmail['usu_email'];
        $_SESSION['barber_client_name'] = $dadosEmail['usu_nome'];

        header("Location: ../../../../client/page/index.php");
        die();
    }
}

header("Location: ../../../../client/index.php?error");
die();
