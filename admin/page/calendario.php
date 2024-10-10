<?php
require_once('../../src/php/connection/connection.php');
session_start();

$sql = "SELECT * FROM ser_servico WHERE ser_ativo = 1";
$query = mysqli_query($connection, $sql);

$sqlOS = "SELECT * FROM os_calendario WHERE cal_os_id not in('0')";

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Calendario</title>
  <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>
  <link href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css' rel='stylesheet'>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/ag-grid-community/dist/ag-grid-community.min.js"></script>
  <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
  <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/bootstrap5@6.1.15/index.global.min.js"></script>
  <style>
    .outline-0:focus {
      outline: 0 !important;
      box-shadow: 0 0 0 0 rgba(0, 0, 0, 0) !important;
    }

    .fc-toolbar-title {
      font-size: 14px !important;
    }

    .fc-dayGridMonth-button {
      font-size: 12px !important;
      padding: 5px 10px !important;
    }

    .fc-listDay-button {
      font-size: 12px !important;
      padding: 5px 10px !important;
    }

    .bi-chevron-left,
    .bi-chevron-right {
      font-size: 10px !important;
    }

    .fc-prev-button,
    .fc-next-button {
      padding: 3px 10px !important;
    }

    #calendar {
      min-height: 500px;
    }

    .fc-event-time {
      display: none !important;
    }

    .fc-daygrid-dot-event {
      background-color: #000 !important;
      color: #fff !important;
    }

    .fc-daygrid-event-dot {
      border-color: #fff !important;
    }
  </style>
</head>

<body>
  <?php require_once('menu.php'); ?>

  <h1 class="text-center mb-3" style="margin-top: 96px;">Calendario</h1>

  <div class="p-2">
    <div id='calendar'></div>
  </div>

  <!-- modal create calendario -->
  <form method="POST" action="../../src/php/admin/agendamento/criarAgendamento.php" class="modal fade" id="CriarEvento" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Faça seu agendamento aqui!</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <?php
        $sqlServicos = "SELECT * FROM ser_servico WHERE ser_ativo = 1";
        $queryServicos = mysqli_query($connection, $sqlServicos);

        $sqlClientes = "SELECT * FROM cad_usuario WHERE usu_ativo = 1";
        $queryClientes = mysqli_query($connection, $sqlClientes);

        $sqlProfissional = "SELECT * FROM cad_funcionario WHERE func_ativo = 1 AND func_agendamentoativo = 1";
        $queryProfissional = mysqli_query($connection, $sqlProfissional);
        ?>
        <div class="modal-body">
          <label for="profissional" class="form-label">Profissional:</label>
          <select name="profissional" id="profissional" class="form-select" required>
            <?php while ($dadosProfissional = mysqli_fetch_array($queryProfissional)) { ?>
              <option value="<?php echo $dadosProfissional['func_id']; ?>"><?php echo $dadosProfissional['func_nome']; ?></option>
            <?php } ?>
          </select>
          <label for="data" class="form-label">Data:</label>
          <input type="date" required name="data" class="form-control" id="data">
          <label for="cliente" class="form-label">Cliente:</label>
          <select name="cliente" id="cliente" class="form-select" required>
            <?php while ($dadosClientes = mysqli_fetch_array($queryClientes)) { ?>
              <option value="<?php echo $dadosClientes['usu_id']; ?>"><?php echo $dadosClientes['usu_nome']; ?></option>
            <?php } ?>
          </select>
          <label for="servico" class="form-label">Serviço desejado:</label>
          <select name="servico[]" required id="servico" class="form-select" multiple>
            <?php while ($dadosServicos = mysqli_fetch_array($queryServicos)) { ?>
              <option value="<?php echo $dadosServicos['ser_id']; ?>"><?php echo $dadosServicos['ser_nome']; ?></option>
            <?php } ?>
          </select>
          <label for="horario" class="form-label">Horários disponíveis:</label>
          <div id="horario-container">
            <select name="horario" required id="horario" class="form-select">
              <!-- Horários serão carregados via AJAX -->
            </select>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
          <button type="submit" onclick="return validaFinalizar(this)" class="btn btn-success">Finalizar</button>
        </div>
      </div>
    </div>
  </form>
  <!-- end: modal create calendario -->

  <?php
  if (isset($_GET['data']) && isset($_GET['titulo'])) {
    $data = date("Y-m-d", strtotime($_GET['data']));
    $titulo = $_GET['titulo'];
    $sqlVer = "SELECT * FROM os_agendamento LEFT JOIN cad_usuario ON age_usu_id = usu_id WHERE age_nome LIKE '$titulo' AND age_data = '$data' LIMIT 1";
    $queryVer = mysqli_query($connection, $sqlVer);
    $dadosVer = mysqli_fetch_array($queryVer);

    $nomeStatus = '';
    switch ($dadosVer['age_status']) {
      case 0:
        $nomeStatus = '<span class="text-success fw-bold">Finalizado</span>';
        break;
      case 1:
        $nomeStatus = '<span class="text-primary fw-bold">Agendado</span>';
        break;
      case 2:
        $nomeStatus = '<span class="text-danger fw-bold">Cancelado</span>';
        break;
      case 3:
        $nomeStatus = '<span class="text-danger fw-bold">Atrasado</span>';
        break;
      default:
        $nomeStatus = '<span class="text-danger fw-bold">Error</span>';
        break;
    }
  ?>
    <div class="modal fade" id="exampleModal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Ver Agendamento</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <h5 class="text-center">Informações do Agendamento</h5>
            <hr>
            <div class="d-flex justify-content-between">
              <p><b>Status:</b> <?php echo $nomeStatus; ?></p>
              <p><b>Seq. Pedido:</b> <?php echo str_pad(($dadosVer['age_sequencia'] + 1), 4, '0', STR_PAD_LEFT); ?></p>
            </div>
            <p><b>Cliente:</b> <?php echo $dadosVer['age_nome']; ?></p>
            <?php if ($dadosVer['age_usu_id'] > 0 && $dadosVer['age_cadastro'] == 1) { ?>
              <?php if ($dadosVer['usu_telefone'] != '') { ?>
                <p class="d-flex align-items-center gap-1"><b>Telefone:</b> <?php echo  $dadosVer['usu_telefone']; ?>
                  <a onclick="return validaConversar(this)" href="https://wa.me/55<?php echo preg_replace('/\D/', '', $dadosVer['usu_telefone']); ?>?text=" class="bg-secondary rounded-pill gap-1 text-white ps-2 pe-2 text-decoration-none" style="display: flex; justify-content: center; align-items: center; height: 28px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-whatsapp" viewBox="0 0 16 16">
                      <path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232" />
                    </svg>
                    Conversar
                  </a>
                </p>
              <?php } else { ?>
                <p><b>Telefone:</b> Sem telefone</p>
              <?php } ?>
            <?php } else { ?>
              <p><b>Telefone:</b> [Sem Registro]</p>
            <?php } ?>
            <p><b>Email:</b> <?php echo $dadosVer['age_email']; ?></p>
            <p><b>Data:</b> <?php echo date('d/m/Y', strtotime($dadosVer['age_data'])); ?> (<?php echo date('H:i', strtotime($dadosVer['age_horarioinicio'])) ?> às <?php echo date('H:i', strtotime($dadosVer['age_horariofinal'])) ?>)</p>
            <?php
            $sqlVerOS = "SELECT * FROM os_agendamentoservico LEFT JOIN ser_servico ON ageser_ser_id = ser_id WHERE ageser_age_id = $dadosVer[age_id] AND ageser_ativo = 1";
            $queryVerOS = mysqli_query($connection, $sqlVerOS);
            $valorFinal = 0;
            ?>
            <p><b>Serviços:</b>
            <ul>
              <?php while ($dadosVerOS = mysqli_fetch_array($queryVerOS)) { ?>
                <li><?php echo $dadosVerOS['ser_nome']; ?> (<?php echo 'R$ ' . number_format($dadosVerOS['ser_valor'], 2, ',', '.'); ?>)</li>

              <?php
                $valorFinal += $dadosVerOS['ser_valor'];
              } ?>
            </ul>
            </p>
            <p class="mt-3"><b>Valor Final:</b> <?php echo 'R$ ' . number_format($valorFinal, 2, ",", "."); ?></p>

          </div>
          <div class="modal-footer justify-content-between">
            <div class="d-flex gap-3">
              <a onclick="return confirmaCancelamento()" href="../../src/php/admin/agendamento/cancelarAgendamento.php?id=<?php echo $dadosVer['age_id']; ?>" class="btn btn-danger d-flex gap-1 justify-content-center align-items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16">
                  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                  <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708" />
                </svg>Cancelar</a>
              <a onclick="return confirmaFinalizacao()" href="../../src/php/admin/agendamento/finalizarAgendamento.php?id=<?php echo $dadosVer['age_id']; ?>" class="btn btn-success d-flex gap-1 justify-content-center align-items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-check2-circle" viewBox="0 0 16 16">
                  <path d="M2.5 8a5.5 5.5 0 0 1 8.25-4.764.5.5 0 0 0 .5-.866A6.5 6.5 0 1 0 14.5 8a.5.5 0 0 0-1 0 5.5 5.5 0 1 1-11 0" />
                  <path d="M15.354 3.354a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0z" />
                </svg>Finalizar</a>
            </div>
            <button type="button" class="btn" data-bs-dismiss="modal">Fechar</button>
          </div>
        </div>
      </div>
    </div>
  <?php } ?>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Função para atualizar os horários disponíveis
      function updateHorarios() {
        var servicoId = document.getElementById('servico').value;
        var data = document.getElementById('data').value;
        var funcionarioId = document.getElementById('profissional').value;

        if (servicoId && data) {
          var xhr = new XMLHttpRequest();
          xhr.open('POST', '../../src/php/client/agendamento/update_horarios.php', true);
          xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
          xhr.onload = function() {
            if (xhr.status === 200) {
              document.getElementById('horario').innerHTML = xhr.responseText;
            }
          };
          xhr.send('servico=' + servicoId + '&data=' + encodeURIComponent(data) +'&funcionario=' + funcionarioId);
        }
      }

      // Adiciona um ouvinte de evento para a seleção do serviço
      document.getElementById('servico').addEventListener('change', updateHorarios);

      // Adiciona um ouvinte de evento para a seleção da data
      document.getElementById('data').addEventListener('change', updateHorarios);

      // Adiciona um ouvinte de evento para a seleção do profissional
      document.getElementById('profissional').addEventListener('change', updateHorarios);
    });

    function validaConversar(valor) {
      if (confirm("Deseja abrir a conversa desse cliente?")) {
        return true;
      } else {
        return false;
      }
    }

    function mostrarSenha() {
      let senhaInput = document.getElementById('senha');

      if (senhaInput.type == 'password') {
        senhaInput.type = 'text';
      } else {
        senhaInput.type = 'password';
      }
    }

    function validaDesativar() {
      if (confirm("Deseja realmente desativar?")) {
        return true;
      } else {
        return false;
      }
    }

    function confirmaCancelamento() {
      if (confirm('Deseja realmente cancelar esse agendamento?')) {
        return true;
      } else {
        return false;
      }
    }

    function confirmaFinalizacao() {
      if (confirm('Deseja realmente finalizar esse agendamento?')) {
        return true;
      } else {
        return false;
      }
    }

    document.addEventListener('DOMContentLoaded', function() {
      var calendarEl = document.getElementById('calendar');

      var calendar = new FullCalendar.Calendar(calendarEl, {
        themeSystem: 'bootstrap5',
        timeZone: 'UTC',
        initialView: 'dayGridMonth',
        headerToolbar: {
          left: 'prev,next',
          center: 'title',
          right: 'listDay,dayGridMonth'
        },
        buttonText: {
          today: 'Hoje',
          month: 'Mês',
          week: 'Semana',
          day: 'Dias',
          list: 'Lista'
        },
        eventSources: [{
          events: [
            <?php
            $sqlEventos = "SELECT * FROM os_agendamento";
            $queryEventos = mysqli_query($connection, $sqlEventos);
            while ($dadosEventos = mysqli_fetch_array($queryEventos)) { ?> {
                title: '<?php echo $dadosEventos['age_nome']; ?>',
                start: '<?php echo $dadosEventos['age_data'] . 'T' . $dadosEventos['age_horarioinicio'];  ?>',
                end: '<?php echo $dadosEventos['age_data'] . 'T' . $dadosEventos['age_horariofinal'];  ?>',
              },
            <?php } ?>
          ],
          color: 'black',
          textColor: 'white',
        }],
        editable: false,
        selectable: true,
        eventClick: function(info) {
          let datainicial = info.event.startStr;
          let titulo = info.event.title;
          window.location.href = `?data=${datainicial}&titulo=${titulo}`;
        },
        dateClick: function(info) {
          var modalCriarEvento = new bootstrap.Modal(document.getElementById('CriarEvento'), {})
          let data = document.getElementById('data');
          data.value = info.dateStr;
          modalCriarEvento.show()
        }
      });

      calendar.setOption('locale', 'pt-br');

      calendar.render();
    });
  </script>

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

  <?php if (isset($_GET['data']) && isset($_GET['titulo'])) { ?>
    <script>
      var myModal = new bootstrap.Modal(document.getElementById('exampleModal'));
      myModal.show();
    </script>
  <?php } ?>
</body>

</html>