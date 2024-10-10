<?php
require_once('../../src/php/connection/connection.php');
session_start();

$semana = array(
  'Sun' => 'Domingo',
  'Mon' => 'Segunda-Feira',
  'Tue' => 'Terca-Feira',
  'Wed' => 'Quarta-Feira',
  'Thu' => 'Quinta-Feira',
  'Fri' => 'Sexta-Feira',
  'Sat' => 'Sábado'
);

$mes_extenso = array(
  'Jan' => 'Janeiro',
  'Feb' => 'Fevereiro',
  'Mar' => 'Marco',
  'Apr' => 'Abril',
  'May' => 'Maio',
  'Jun' => 'Junho',
  'Jul' => 'Julho',
  'Aug' => 'Agosto',
  'Nov' => 'Novembro',
  'Sep' => 'Setembro',
  'Oct' => 'Outubro',
  'Dec' => 'Dezembro'
);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inicio</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <style>
    body {
      background: #333 !important;
    }

    .outline-0:focus {
      outline: 0 !important;
      box-shadow: 0 0 0 0 rgba(0, 0, 0, 0) !important;
    }

    .card-fechado {
      background-color: green;
      color: #fff;
      padding: 20px;
      margin: 10px;
      margin-left: 0px;
      text-align: center;
      font-family: 'Courier New', Courier, monospace;
      font-size: 25px;
      font-weight: bold;
    }

    .card-fechado p,
    .card-aberto p {
      margin: 0;
      padding: 0;
    }

    .card-aberto {
      background-color: red;
      color: #fff;
      padding: 20px;
      margin: 10px;
      margin-right: 0px;
      text-align: center;
      font-family: 'Courier New', Courier, monospace;
      font-size: 25px;
      font-weight: bold;
    }

    h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
      text-align: center;
    }

    .card-format {
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 20px;
    }

    .card-format-body {
      background-color: rgba(100, 200, 100, 0.3);
      margin-top: 20px;
      padding-top: 20px;
      padding-bottom: 20px;
      width: 90%;
      border-radius: 20px;
    }

    .card-format-body-cancelled {
      background-color: rgba(200, 100, 100, 0.3);
      padding-top: 20px;
      padding-bottom: 20px;
      width: 90%;
      border-radius: 20px;
    }

    .scroll-height {
      overflow-y: scroll;
      max-height: calc(100vh - 390px);
    }

    .division-6 {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 10px;
    }
  </style>
</head>

<body class="d-flex justify-content-center align-items-center justify-items-center w-100" style="height: 100vh">
  <div style="max-width: 550px; width: 550px; height: 98vh; box-shadow: 0px 0px 15px rgba(0,0,0, 0.5)" class="bg-white">
    <?php require_once('menu.php'); ?>
    <div class="ps-2 pe-2 d-flex flex-column gap-2" style="margin-top: 80px;">
      <hr>
      <?php
      $dataInicial = date('Y-m-d', strtotime('first day of this month'));
      $dataFinal = date('Y-m-d', strtotime('last day of this month'));
      $sqlAgendamentoAberto = "SELECT age_id FROM os_agendamento WHERE age_data >= '$dataInicial' and age_data <= '$dataFinal' and age_status = 1";
      $queryAgendamentoAberto = mysqli_query($connection, $sqlAgendamentoAberto);
      $rowAgendamentoAberto = mysqli_num_rows($queryAgendamentoAberto);

      $sqlAgendamentoFechado = "SELECT age_id FROM os_agendamento WHERE age_data >= '$dataInicial' and age_data <= '$dataFinal' and age_status = 0";
      $queryAgendamentoFechado = mysqli_query($connection, $sqlAgendamentoFechado);
      $rowAgendamentoFechado = mysqli_num_rows($queryAgendamentoFechado);

      $sqlValorPrevistos = "SELECT sum(ser_valor) valor FROM os_agendamentoservico LEFT JOIN ser_servico ON ageser_ser_id = ser_id LEFT JOIN os_agendamento ON ageser_age_id = age_id WHERE age_data >= '$dataInicial' and age_data <= '$dataFinal' and age_status = 1";
      $queryValorPrevistos = mysqli_query($connection, $sqlValorPrevistos);
      $dadosValorPrevistos = mysqli_fetch_array($queryValorPrevistos);

      $sqlValorReais = "SELECT sum(ser_valor) valor FROM os_agendamentoservico LEFT JOIN ser_servico ON ageser_ser_id = ser_id LEFT JOIN os_agendamento ON ageser_age_id = age_id WHERE age_data >= '$dataInicial' and age_data <= '$dataFinal' and age_status = 0";
      $queryValorReais = mysqli_query($connection, $sqlValorReais);
      $dadosValorReais = mysqli_fetch_array($queryValorReais);
      ?>
      <div class="division-6">
        <div class="d-flex justify-content-between ps-2 pe-2 align-items-center bg-secondary text-white" style="min-height: 60px; border-left: 5px solid #FEC601;">
          <span class="text-start h6 mb-0">Serviços<br>Abertos</span>
          <span class="text-end h6 mb-0"><?php echo $rowAgendamentoAberto; ?></span>
        </div>
        <div class="d-flex justify-content-between ps-2 pe-2 align-items-center bg-secondary text-white" style="min-height: 60px; border-left: 5px solid #0EB1D2;">
          <span class="text-start h6 mb-0">Serviços<br>Fechados</span>
          <span class="text-end h6 mb-0"><?php echo $rowAgendamentoFechado; ?></span>
        </div>
      </div>
      <div class="division-6">
        <div class="d-flex justify-content-between ps-2 pe-2 align-items-center bg-secondary text-white" style="min-height: 60px; border-left: 5px solid #50e0d1;">
          <span class="text-start h6 mb-0">Ganhos<br>Previstos</span>
          <span class="text-end h6 mb-0"><?php echo 'R$ ' . number_format($dadosValorPrevistos['valor'], 2, ',', '.'); ?></span>
        </div>
        <div class="d-flex justify-content-between ps-2 pe-2 align-items-center bg-secondary text-white" style="min-height: 60px; border-left: 5px solid #00d466;">
          <span class="text-start h6 mb-0">Ganhos<br>Reais</span>
          <span class="text-end h6 mb-0"><?php echo 'R$ ' . number_format($dadosValorReais['valor'], 2, ',', '.'); ?></span>
        </div>
      </div>
      <hr class="mb-0">

      <h3>Agendamentos do dia</h3>
      <div class="scroll-height">
        <?php
        $diaAtual = date('Y-m-d');
        $sqlAgendamentos = "SELECT * FROM os_agendamento WHERE age_data = '$diaAtual' AND age_status in(1)";
        $queryAgendamentos = mysqli_query($connection, $sqlAgendamentos);
        $rowAgendamentos = mysqli_num_rows($queryAgendamentos);
        ?>
        <?php
        if ($rowAgendamentos > 0) {
        ?>
          <?php while ($dadosAgendamentos = mysqli_fetch_array($queryAgendamentos)) { ?>

            <?php
            $sqlServicos = "SELECT * FROM os_agendamentoservico LEFT JOIN ser_servico ON ageser_ser_id = ser_id WHERE ageser_age_id = $dadosAgendamentos[age_id]";
            $queryServicos = mysqli_query($connection, $sqlServicos);
            $rowServicos = mysqli_num_rows($queryServicos);
            $servicos = '';
            $valorFinal = 0;
            $i = 1;
            while ($dadosServicos = mysqli_fetch_array($queryServicos)) {
              if ($i < $rowServicos) {
                $servicos .= $dadosServicos['ser_nome'] . ',<br>';
              } else {
                $servicos .= $dadosServicos['ser_nome'];
              }
              $i++;

              $valorFinal += $dadosServicos['ser_valor'];
            }

            $data = date('D', strtotime($dadosAgendamentos['age_data']));
            $dia = date('d', strtotime($dadosAgendamentos['age_data']));
            $ano = date('Y', strtotime($dadosAgendamentos['age_data']));
            $mes = date('M', strtotime($dadosAgendamentos['age_data']));
            ?>

            <div class="row bg-light border border-1 m-0 mb-2">
              <div class="card-format">
                <?php if ($dadosAgendamentos['age_status'] == 1) { ?>
                  <div class="row card-format-body">
                  <?php } else if ($dadosAgendamentos['age_status'] == 2) { ?>
                    <div class="row card-format-body-cancelled">
                    <?php } ?>
                    <div class="col-3 d-flex flex-column justify-content-end align-items-end">
                      <svg xmlns="http://www.w3.org/2000/svg" width="42" height="42" fill="currentColor" class="bi bi-clock" viewBox="0 0 16 16">
                        <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71z" />
                        <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0" />
                      </svg>
                      <p class="mb-0 fw-bold h6 text-primary"><?php echo date('H:i', strtotime($dadosAgendamentos['age_horarioinicio'])) ?></p>
                    </div>
                    <?php if ($dadosAgendamentos['age_status'] == 1) { ?>
                      <div class="col-9">
                        <div class="row">
                          <?php if ($dadosAgendamentos['age_status'] == 1) { ?>
                            <div class="col-10">
                            <?php } else if ($dadosAgendamentos['age_status'] == 2) { ?>
                              <div class="col-12">
                              <?php } ?>
                              <p class="mb-0 fw-bold h6"><?php echo $servicos; ?></p>
                              </div>
                            </div>
                            <p class="mb-0 fw-bold">[R$<?php echo number_format($valorFinal, 2, ',', '. ');; ?>]</p>
                            <p class="mb-0 h6">Profissional: Bruno Garcia</p>
                          <?php } else if ($dadosAgendamentos['age_status'] == 2) { ?>
                            <div class="col-9 d-flex align-items-center">
                              <p class="mb-0 fw-bold">Agendamento foi cancelado!</p>
                            <?php } ?>
                            </div>
                        </div>
                      </div>
                    </div>
                  <?php } ?>
                <?php
              } else {
                ?>
                  <div class="d-flex flex-column justify-content-center align-items-center">
                    <span class="text-center h6 text-secondary">Sem agendamentos no dia atual!</span>
                    <img src="../../src/img/not_found.png" class="img-fluid w-50" alt="Não encontrado">
                  </div>
                <?php
              }
                ?>
                  </div>
              </div>
            </div>
            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script>
              document.addEventListener('DOMContentLoaded', function() {
                // Função para atualizar os horários disponíveis
                function updateHorarios() {
                  var servicoId = document.getElementById('servico').value;
                  var data = document.getElementById('data').value;

                  if (servicoId && data) {
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', '../../src/php/client/agendamento/update_horarios.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onload = function() {
                      if (xhr.status === 200) {
                        document.getElementById('horario').innerHTML = xhr.responseText;
                      }
                    };
                    xhr.send('servico=' + servicoId + '&data=' + encodeURIComponent(data));
                  }
                }

                // Adiciona um ouvinte de evento para a seleção do serviço
                document.getElementById('servico').addEventListener('change', updateHorarios);

                // Adiciona um ouvinte de evento para a seleção da data
                document.getElementById('data').addEventListener('change', updateHorarios);
              });

              function validaFinalizar(valor) {
                if (confirm('Deseja realmente finalizar seu agendamento?')) {
                  return true;
                } else {
                  return false;
                }
              }

              function validaCancelamento(valor) {
                if (confirm('Deseja realmente cancelar esse agendamento?')) {
                  return true;
                } else {
                  return false;
                }
              }

              function validaEdicao(valor) {
                if (confirm('Deseja realmente editar suas informações?')) {
                  return true;
                } else {
                  return false;
                }
              }

              function esconderSenha() {
                let inputSenha = document.getElementById('senhacadastro');
                let iconMostrar = document.getElementById('icon_mostrar_senha');
                let iconEsconder = document.getElementById('icon_esconder_senha');
                inputSenha.type = 'password';
                iconMostrar.classList.add('d-flex');
                iconMostrar.classList.remove('d-none');
                iconEsconder.classList.remove('d-flex');
                iconEsconder.classList.add('d-none');
              }

              function mostrarSenha() {
                let inputSenha = document.getElementById('senhacadastro');
                let iconMostrar = document.getElementById('icon_mostrar_senha');
                let iconEsconder = document.getElementById('icon_esconder_senha');
                inputSenha.type = 'text';
                iconMostrar.classList.add('d-none');
                iconMostrar.classList.remove('d-flex');
                iconEsconder.classList.remove('d-none');
                iconEsconder.classList.add('d-flex');
              }
            </script>
</body>

</html>