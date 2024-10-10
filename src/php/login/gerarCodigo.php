<?php

require_once('../connection/connection.php');
require '../../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;

if (isset($_GET['s']) && $_GET['s'] == 1) {
    $email = $_GET['email'];
    $senha = password_hash($_GET['senha'], PASSWORD_DEFAULT);

    $sqlUpdate = "UPDATE cad_funcionario SET func_senha='$senha' WHERE upper(func_email) = upper('$email')";
    mysqli_query($connection, $sqlUpdate);

    header("Location: ../../../index.php?mail=1");
} else {
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $codigo = rand(100000, 999999);

    $sqlUsers = "SELECT func_email, func_nome FROM cad_funcionario WHERE upper(func_email) LIKE upper('$email') LIMIT 1";
    $queryUsers = mysqli_query($connection, $sqlUsers);
    $dadosUsers = mysqli_fetch_array($queryUsers);
    $rowUsers = mysqli_num_rows($queryUsers);

    if ($rowUsers == 1) {
        $nome = $dadosUsers['func_nome'];
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';
        $mail->Username = 'rafaelvitordossantos123@gmail.com';
        $mail->Password = 'golbtktugsoogpzk';
        $mail->Port = 587;
        $mail->CharSet = "UTF-8";

        $mail->setFrom('rafaelvitordossantos123@gmail.com');
        $mail->addAddress($email, $nome);
        // $mail->addCC('email@email.com.br', 'Cópia');
        // $mail->addBCC('email@email.com.br', 'Cópia Oculta');

        $mail->isHTML(true);
        $mail->Subject = "Recuperação de senha";
        $mail->Body    = "<b>Redefinir senha</b>
                        <br>
                        <br>
                        Olá, <b>$nome</b>,
                        <br>
                        <br>
                        <br>
                        Vamos redefinir sua senha de acesso, utilizando o seguinte código: <b>$codigo</b>";
        // $mail->addAttachment('/tmp/image.jpg', 'nome.jpg');

        if (!$mail->send()) {
            header("Location: ../../../index.php?mail=0");
        } else {
            echo "<script>
        let valor = prompt('digite o código enviado para seu e-mail');

        if (valor == $codigo) {
            window.location.href='?email=$email&senha=$senha&s=1';
        } else {
            window.location.href='../../../index.php?mail=0';
        }
        </script>";
        }
    } else {
        header("Location: ../../../index.php?mail=0");
    }
}
