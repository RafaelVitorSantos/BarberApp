<?php
include('../../connection/connection.php');

if (isset($_POST['servico']) && isset($_POST['data'])) {
    $servicoId = $_POST['servico'];
    $data = mysqli_real_escape_string($connection, $_POST['data']);

    $sqlTempoServico = "SELECT * FROM ser_servico WHERE ser_id in ($servicoId)";
    $queryTempoServico = mysqli_query($connection, $sqlTempoServico);
    $somaMinutos = 0;

    // Soma a duração de todos os serviços em minutos
    while ($dadosTempoServico = mysqli_fetch_array($queryTempoServico)) {
        list($horas, $minutos) = explode(':', $dadosTempoServico['ser_duracao']);
        $somaMinutos += $horas * 60 + $minutos;
    }

    // Obtém o dia da semana da data
    $diaSemana = date('w', strtotime($data));

    // Consulta o horário de funcionamento
    $sqlHorarioFuncionamento = "SELECT * FROM age_horariofuncionamento WHERE hrfun_diasemana = $diaSemana AND hrfun_ativo = 1 LIMIT 1";
    $queryHorarioFuncionamento = mysqli_query($connection, $sqlHorarioFuncionamento);
    $dadosHorarioFuncionamento = mysqli_fetch_array($queryHorarioFuncionamento);

    $horarioinicial = $dadosHorarioFuncionamento['hrfun_horarioinicial'];
    $horariofinal = $dadosHorarioFuncionamento['hrfun_horariofinal'];

    $horarioMostrado = $horarioinicial;

    // Verifica a disponibilidade dos horários
    while (strtotime($horarioMostrado) < strtotime($horariofinal)) {
        // Calcula o horário de término do serviço
        $horarioMostradoFim = date('H:i', strtotime($horarioMostrado) + $somaMinutos * 60);  // Multiplica por 60 para converter minutos para segundos

        // Consulta os agendamentos existentes para o horário
        $sqlValidate = "SELECT * FROM os_agendamento WHERE age_status = 1 AND age_data = '$data'";
        $queryValidate = mysqli_query($connection, $sqlValidate);
        $rowValidate = mysqli_num_rows($queryValidate);
        $error = 0;

        if ($rowValidate > 0) {
            while ($dadosValidate = mysqli_fetch_array($queryValidate)) {
                if ((($horarioMostrado > date('H:i', strtotime($dadosValidate['age_horarioinicio']))) &&
                        ($horarioMostradoFim > date('H:i', strtotime($dadosValidate['age_horariofinal']))) &&
                        ($horarioMostrado > date('H:i', strtotime($dadosValidate['age_horariofinal']))) &&
                        ($horarioMostradoFim > date('H:i', strtotime($dadosValidate['age_horarioinicio'])))) ||
                    (($horarioMostradoFim < date('H:i', strtotime($dadosValidate['age_horarioinicio']))) &&
                        ($horarioMostrado < date('H:i', strtotime($dadosValidate['age_horariofinal']))) &&
                        ($horarioMostradoFim < date('H:i', strtotime($dadosValidate['age_horariofinal']))) &&
                        ($horarioMostrado < date('H:i', strtotime($dadosValidate['age_horarioinicio']))))
                ) {
                    // Horário disponível
                } else {
                    $error++;
                }
            }
        }
        // Se o horário estiver disponível, adiciona à lista
        if ($error == 0) {
            echo '<option value="' . $horarioMostrado . '">' . date('H:i', strtotime($horarioMostrado)) /*. '-' . $horarioMostradoFim */. '</option>';
        }

        $error = 0;

        // Adiciona 15 minutos ao horário mostrado
        $horarioMostrado = date('H:i', strtotime($horarioMostrado) + 15 * 60);
    }
}
