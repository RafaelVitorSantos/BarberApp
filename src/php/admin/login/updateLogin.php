<?php
require_once("../../connection/connection.php");

$id = $_POST['id'];
$nome = $_POST['nome'];
$email = $_POST['email'];
$senha = $_POST['senha'];

$sqlValidate = "SELECT usu_email, usu_senha FROM cad_usuario WHERE usu_id = $id LIMIT 1";
$queryValidate = mysqli_query($connection, $sqlValidate);
$dadosValidate = mysqli_fetch_array($queryValidate);

if (password_verify($senha, $dadosValidate['usu_senha'])) {
    if (strtoupper($dadosValidate['usu_email']) == strtoupper($email)) {
        $sqlUpdate = "UPDATE cad_usuario SET usu_nome='$nome' WHERE usu_id = $id";
        mysqli_query($connection, $sqlUpdate);

        header("Location: ../../../../client/page/index.php?successEdit");
        die();
    } else {
        $sqlEmail = "SELECT * FROM cad_usuario WHERE upper(usu_email) LIKE upper('$email') AND usu_id not in($id)";
        $queryEmail = mysqli_query($connection, $sqlEmail);
        $rowEmail = mysqli_num_rows($queryEmail);

        if ($rowEmail == 0) {
            $sqlUpdate = "UPDATE cad_usuario SET usu_nome='$nome', usu_email='$email' WHERE usu_id = $id";
            mysqli_query($connection, $sqlUpdate);
            header("Location: ../../../../client/page/index.php?successEdit");
            die();
        } else {
            header("Location: ../../../../client/page/index.php?emailExist");
            die();
        }
    }
} else {
    header("Location: ../../../../client/page/index.php?passwordInvalid");
    die();
}
