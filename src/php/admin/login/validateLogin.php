<?php
require_once("../../connection/connection.php");

$email = $_POST['email'];
$senha = $_POST['senha'];

$sqlEmail = "SELECT * FROM cad_login WHERE upper(log_email) LIKE upper('$email') AND log_ativo = 1";
$queryEmail = mysqli_query($connection, $sqlEmail);
$rowEmail = mysqli_num_rows($queryEmail);

if ($rowEmail == 1) {
    $dadosEmail = mysqli_fetch_array($queryEmail);
    if (password_verify($senha, $dadosEmail['log_senha'])) {
        ini_set('session.cache_expire', 20000);
        ini_set('session.cache_limiter', 'none');
        ini_set('session.cookie_lifetime', 94608);
        ini_set('session.gc_maxlifetime', 94608);
        session_start();

        $_SESSION['barber_admin_id'] = $dadosEmail['log_id'];
        $_SESSION['barber_admin_email'] = $dadosEmail['log_email'];
        $_SESSION['barber_admin_name'] = $dadosEmail['log_nome'];

        header("Location: ../../../../admin/page/index.php");
        die();
    }
} else if ($rowEmail == 0) {
    $sqlEmailFunc = "SELECT * FROM cad_funcionario WHERE upper(func_email) LIKE upper('$email') AND func_ativo = 1";
    $queryEmailFunc = mysqli_query($connection, $sqlEmailFunc);
    $rowEmailFunc = mysqli_num_rows($queryEmailFunc);

    $dadosEmailFunc = mysqli_fetch_array($queryEmailFunc);
    if (password_verify($senha, $dadosEmailFunc['func_senha'])) {
        ini_set('session.cache_expire', 20000);
        ini_set('session.cache_limiter', 'none');
        ini_set('session.cookie_lifetime', 94608);
        ini_set('session.gc_maxlifetime', 94608);
        session_start();

        $_SESSION['barber_func_id'] = $dadosEmailFunc['func_id'];
        $_SESSION['barber_func_email'] = $dadosEmailFunc['func_email'];
        $_SESSION['barber_func_name'] = $dadosEmailFunc['func_nome'];

        header("Location: ../../../../admin/page/index.php");
        die();
    }
}

header("Location: ../../../../admin/index.php?error");
die();
