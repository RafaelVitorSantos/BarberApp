<?php
require_once("../../connection/connection.php");

$nome = $_POST['nome'];
$email = $_POST['email'];
$senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
$cpf = $_POST['cpf'];

$sqlValidate = "SELECT * FROM cad_login WHERE upper(log_email) = upper('$email')  AND log_ativo = 1";
$queryValidate = mysqli_query($connection, $sqlValidate);
$RowValidate = mysqli_num_rows($queryValidate);
if ($rowValidate == 0) {
    $sqlValidateFunc = "SELECT * FROM cad_funcionario WHERE upper(func_email) = upper('$email') AND func_ativo = 1";
    $queryValidateFunc = mysqli_query($connection, $sqlValidateFunc);
    $rowValidateFunc = mysqli_num_rows($queryValidateFunc);

    if ($rowValidateFunc == 0) {
        $sql = "INSERT INTO cad_login(log_nome, log_email, log_senha, log_cpf) VALUES('$nome','$email','$senha','$cpf')";
        mysqli_query($connection, $sql);
    } else {
        header("Location: ../../../../admin/page/administrador.php?error=email");
        die();
    }
} else {
    header("Location: ../../../../admin/page/administrador.php?error=email");
    die();
}

header("Location: ../../../../admin/page/administrador.php?c=1");
die();
