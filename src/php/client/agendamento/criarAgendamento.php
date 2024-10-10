<?php
require_once("../../connection/connection.php");
session_start();

$sqlConfig = "SELECT * FROM cad_config WHERE config_id = 1";
$queryConfig = mysqli_query($connection, $sqlConfig);
$dadosConfig = mysqli_fetch_array($queryConfig);
if ($dadosConfig['config_status'] == 0) {
    header('Location: ../../../../client/page/index.php?inativo');
    die();
}
if (isset($_SESSION['barber_client_id'])) {
    $data = $_POST['data'];
    $servico = $_POST['servico'];
    $servicoImplode = implode(",", $_POST['servico']);
    $horario = $_POST['horario'];
    $id = $_SESSION['barber_client_id'];
    $nome = $_SESSION['barber_client_name'];
    $email = $_SESSION['barber_client_email'];
    $funcionario = $_POST['funcionario'];

    $sqlTempoServico = "SELECT * FROM ser_servico WHERE ser_id in ($servicoImplode)";
    $queryTempoServico = mysqli_query($connection, $sqlTempoServico);
    $somaTempos = date('H:i', strtotime('00:00:00'));
    while ($dadosTempoServico = mysqli_fetch_array($queryTempoServico)) {
        $somaTempos = date('H:i', strtotime($somaTempos) + strtotime($dadosTempoServico['ser_duracao']));
    }
    $horariofinal = date('H:i', strtotime($horario) + strtotime($somaTempos));

    $sql = "INSERT INTO os_agendamento(age_cadastro, age_usu_id, age_nome, age_email, age_data, age_horarioinicio, age_horariofinal, age_func_id)
    VALUES(1, $id,'$nome','$email','$data','$horario','$horariofinal', '$funcionario')";
    mysqli_query($connection, $sql);

    $sqlId = "SELECT age_id FROM os_agendamento WHERE age_cadastro = 1 AND age_usu_id = $id AND age_nome = '$nome' AND age_email = '$email' AND age_data = '$data' AND age_horarioinicio='$horario' AND age_horariofinal='$horariofinal'";
    $queryId = mysqli_query($connection, $sqlId);
    $dadosId = mysqli_fetch_array($queryId);

    $idAgendamento = $dadosId['age_id'];
    foreach ($servico as $idServico) {
        $sqlServicos = "INSERT INTO os_agendamentoservico(ageser_age_id, ageser_ser_id) VALUES($idAgendamento, $idServico)";
        mysqli_query($connection, $sqlServicos);
    }

    header("Location: ../../../../client/page/index.php?success");
    die();
}

header("Location: ../../../../client/index.php?error");
die();
