<?php
require_once('../src/php/connection/connection.php');

$sql = "SELECT * FROM cad_servico WHERE ser_ativo = 1";
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
    .fixed-top {
      position: fixed;
      top: 5px;
      left: 5px;
    }

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

    .fc-listWeek-button {
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
  </style>
</head>

<body>
  <?php require_once('menu.php'); ?>

  <h1 class="text-center mt-3 mb-3">Calendario</h1>

  <div class="p-2">
    <div id='calendar'></div>
  </div>

  <!-- modal create calendario -->
  <form method="POST" action="../src/php/calendario/criar.php" class="modal fade" id="CriarEvento" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Criar Evento</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-1">
            <label for="data" class="form-label">Data:</label>
            <input type="date" class="form-control" name="data" id="data" required>
          </div>
          <div class="mb-1">
            <label for="nome" class="form-label">Nome:</label>
            <input type="text" class="form-control" name="nome" id="nome" required>
          </div>
          <?php
          $sqlOS = "SELECT * FROM os_calendario WHERE cal_os_id not in('0')";
          $queryOS = mysqli_query($connection, $sqlOS);
          $rowOS = mysqli_num_rows($queryOS);
          $osVinculada = '';
          $c = 0;
          if ($rowOS > 0) {
            while ($dadosOS = mysqli_fetch_array($queryOS)) {
              if ($c == 0) {
                $osVinculada = "'" . $dadosOS['cal_os_id'] . "'";
              } else {
                $osVinculada = $osVinculada . ', ' . "'" . $dadosOS['cal_os_id'] . "'";
              }
              $c++;
            }
          } else {
            $osVinculada = '0';
          }

          $sqlDisponivel = "SELECT * FROM os_ordemservico WHERE os_id not in($osVinculada)";
          $queryDisponivel = mysqli_query($connection, $sqlDisponivel);
          ?>
          <div class="mb-1">
            <label for="os" class="form-label d-flex gap-2 align-items-center">Vincular O.S: <small>(opcional)</small></label>
            <select name="os" class="form-select" id="os">
              <option value=""></option>
              <?php while ($dadosDisponivel = mysqli_fetch_array($queryDisponivel)) { ?>
                <option value="<?php echo $dadosDisponivel['os_id']; ?>"><?php echo $dadosDisponivel['os_id'] . ' - ' . $dadosDisponivel['os_titulo']; ?></option>
              <?php } ?>
            </select>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
          <button type="submit" class="btn btn-primary">Criar</button>
        </div>
      </div>
    </div>
  </form>
  <!-- end: modal create calendario -->

  <?php
  if (isset($_GET['data']) && isset($_GET['titulo'])) {
    $data = $_GET['data'];
    $titulo = $_GET['titulo'];
    $sqlVer = "SELECT * FROM os_calendario WHERE cal_nome LIKE '$titulo' AND cal_data = '$data' LIMIT 1";
    $queryVer = mysqli_query($connection, $sqlVer);
    $dadosVer = mysqli_fetch_array($queryVer);
  ?>
    <div class="modal fade" id="exampleModal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Ver Evento</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <h5 class="text-center">Informações do Evento</h5>
            <hr>
            <p><b>Titulo:</b> <?php echo $dadosVer['cal_nome']; ?></p>
            <p><b>Data:</b> <?php echo $dadosVer['cal_data']; ?></p>
            <?php if ($dadosVer['cal_os_id'] > 0) { ?>
              <?php
              $sqlVerOS = "SELECT * FROM os_ordemservico LEFT JOIN cad_funcionario ON os_func_id = func_id WHERE os_id = $dadosVer[cal_os_id] LIMIT 1";
              $queryVerOS = mysqli_query($connection, $sqlVerOS);
              $dadosVerOS = mysqli_fetch_array($queryVerOS);

              $nomeStatus = '';
              switch ($dadosVerOS['os_status']) {
                case 1:
                  $nomeStatus = 'Aberto';
                  break;
                case 2:
                  $nomeStatus = 'Parcial';
                  break;
                case 3:
                  $nomeStatus = 'Fechado';
                  break;
                default:
                  $nomeStatus = 'Error';
                  break;
              }
              ?>
              <hr>
              <h5 class="text-center">Informações da OS</h5>
              <hr>
              <p><b>Titulo:</b> <?php echo $dadosVerOS['os_titulo']; ?></p>
              <p><b>Responsável:</b> <?php echo $dadosVerOS['func_nome']; ?></p>
              <p><b>Status:</b> <?php echo $nomeStatus; ?></p>
            <?php } ?>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            <?php if ($dadosVer['cal_os_id'] > 0) { ?>
              <a href="os.php?id=<?php echo $dadosVer['cal_os_id']; ?>&nvl=0" class="btn btn-primary">Ver O.S</a>
            <?php } else { ?>
              <button type="button" class="btn btn-primary">Criar O.S</button>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  <?php } ?>

  <script>
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

    document.addEventListener('DOMContentLoaded', function() {
      var calendarEl = document.getElementById('calendar');

      var calendar = new FullCalendar.Calendar(calendarEl, {
        themeSystem: 'bootstrap5',
        timeZone: 'UTC',
        initialView: 'dayGridMonth',
        headerToolbar: {
          left: 'prev,next',
          center: 'title',
          right: 'listWeek,dayGridMonth'
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
            $sqlEventos = "SELECT * FROM os_calendario WHERE cal_ativo = 1";
            $queryEventos = mysqli_query($connection, $sqlEventos);
            while ($dadosEventos = mysqli_fetch_array($queryEventos)) { ?> {
                title: '<?php echo $dadosEventos['cal_nome']; ?>',
                start: '<?php echo $dadosEventos['cal_data']; ?>',
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