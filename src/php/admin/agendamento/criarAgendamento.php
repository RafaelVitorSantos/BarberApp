<?php
require_once("../../connection/connection.php");
session_start();

$data = $_POST['data'];
$servico = $_POST['servico'];
$servicoImplode = implode(",", $_POST['servico']);
$horario = $_POST['horario'];
$id = $_POST['cliente'];
$funcionario = $_POST['profissional'];
$sqlUsuario = "SELECT * FROM cad_usuario WHERE usu_id = $id LIMIT 1";
$queryUsuario = mysqli_query($connection, $sqlUsuario);
$dadosUsuario = mysqli_fetch_array($queryUsuario);
$nome = $dadosUsuario['usu_nome'];
$email = $dadosUsuario['usu_email'];

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

$sqlId = "SELECT age_id FROM os_agendamento WHERE age_cadastro = 1 AND age_usu_id = $id AND age_nome = '$nome' AND age_email = '$email' AND age_data = '$data' AND age_horarioinicio='$horario' AND age_horariofinal='$horariofinal' AND age_func_id='$funcionario'";
$queryId = mysqli_query($connection, $sqlId);
$dadosId = mysqli_fetch_array($queryId);

$idAgendamento = $dadosId['age_id'];
foreach ($servico as $idServico) {
    $sqlServicos = "INSERT INTO os_agendamentoservico(ageser_age_id, ageser_ser_id) VALUES($idAgendamento, $idServico)";
    mysqli_query($connection, $sqlServicos);
}
if (isset($_POST['tela'])) {
    header("Location: ../../../../admin/page/agendamento.php?success");
} else {
    header("Location: ../../../../admin/page/calendario.php?success");
}
die();
