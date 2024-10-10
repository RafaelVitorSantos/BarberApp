<?php
require_once('../../src/php/connection/connection.php');
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Agendamentos</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/ag-grid-community/dist/ag-grid-community.min.js"></script>
  <style>
    .outline-0:focus {
      outline: 0 !important;
      box-shadow: 0 0 0 0 rgba(0, 0, 0, 0) !important;
    }
  </style>
</head>

<body>
  <?php require_once('menu.php'); ?>

  <?php
  $sql = "SELECT * FROM os_agendamento";
  $query = mysqli_query($connection, $sql);
  ?>

  <h1 class="text-center mb-3" style="margin-top: 96px;">Agendamentos</h1>

  <div class="d-flex justify-content-end gap-3 p-2">
    <input type="text" class="form-control">
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#adicionarModal">Adicionar</button>
  </div>

  <table class="table table-striped">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Nome</th>
        <th scope="col">Data</th>
        <th scope="col"></th>
      </tr>
    </thead>
    <tbody>
      <?php while ($dados = mysqli_fetch_array($query)) { ?>

        <?php
        $corStatus = '';
        $nomeStatus = '';
        switch ($dados['age_status']) {
          case 0:
            $corStatus = 'bg-success';
            $nomeStatus = 'Finalizado';
            break;
          case 1:
            $corStatus = 'bg-light';
            $nomeStatus = 'Agendado';
            break;
          case 2:
            $corStatus = 'bg-danger';
            $nomeStatus = 'Cancelado';
            break;
        }
        ?>
        <tr>
          <th scope="row" class="<?php echo $corStatus; ?>"><?php echo $nomeStatus; ?></th>
          <td><?php echo $dados['age_nome']; ?></td>
          <td><?php echo date('d/m/Y', strtotime($dados['age_data'])); ?></td>
          <td>
            <button class="btn btn-transparent pt-0 pb-0" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots" viewBox="0 0 16 16">
                <path d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3" />
              </svg>
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
              <!-- <li><a class="dropdown-item" href="?edit=<?php echo $dados['age_id']; ?>">Editar</a></li> -->
              <li><a class="dropdown-item" onclick="return validaFinalizacao()" href="../../src/php/admin/agendamento/finalizar.php?id=<?php echo $dados['age_id']; ?>">Finalizar</a></li>
              <li><a class="dropdown-item" onclick="return validaCancelamento()" href="../../src/php/admin/agendamento/cancelar.php?id=<?php echo $dados['age_id']; ?>">Cancelar</a></li>
            </ul>
          </td>
        </tr>
      <?php } ?>
    </tbody>
  </table>

  <!-- modal create calendario -->
  <form method="POST" action="../../src/php/admin/agendamento/criarAgendamento.php" class="modal fade" id="adicionarModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Cadastrar Agendamento</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <?php
        $sqlServicos = "SELECT * FROM ser_servico WHERE ser_ativo = 1";
        $queryServicos = mysqli_query($connection, $sqlServicos);

        $sqlClientes = "SELECT * FROM cad_usuario WHERE usu_ativo = 1";
        $queryClientes = mysqli_query($connection, $sqlClientes);
        ?>
        <div class="modal-body">
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
          <input type="hidden" name="tela" value="agendamento">
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
          <button type="submit" onclick="return validaFinalizar(this)" class="btn btn-success">Finalizar</button>
        </div>
      </div>
    </div>
  </form>
  <!-- end: modal create calendario -->

  <!-- modal update fornecedor -->

  <?php
  if (isset($_GET['edit'])) {
    $id = $_GET['edit'];

    $sqlEdit = "SELECT * FROM ser_servico WHERE ser_id = $id LIMIT 1";
    $queryEdit = mysqli_query($connection, $sqlEdit);
    $dadosEdit = mysqli_fetch_array($queryEdit);
  }
  ?>
  <form method="POST" action="../../src/php/admin/servico/editar.php" class="modal fade" id="editarModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Editar serviço</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-1">
            <label class="form-label">Identificador:</label>
            <input type="text" disabled class="form-control" value="<?php echo $dadosEdit['ser_id']; ?>" required>
            <input type="hidden" name="id" value="<?php echo $dadosEdit['ser_id']; ?>">
          </div>
          <div class="mb-2">
            <label for="nome" class="form-label">Nome:</label>
            <input type="text" class="form-control" name="nome" value="<?php echo $dadosEdit['ser_nome']; ?>" id="nome" required>
          </div>
          <div class="d-flex gap-2 w-100">
            <div class="mb-1 w-50">
              <label for="valor" class="form-label">Valor:</label>
              <input type="text" onInput="mascaraMoeda(event);" value="<?php echo 'R$ ' . $dadosEdit['ser_valor']; ?>" class="form-control" name="valor" id="valor" required>
            </div>
            <div class="mb-1 w-50">
              <label for="duracao" class="form-label">Duração (hr:min):</label>
              <input type="time" class="form-control" value="<?php echo $dadosEdit['ser_duracao']; ?>" name="duracao" id="duracao" required>
            </div>
          </div>
          <div class="mb-2">
            <label for="observacao" class="form-label">Observação:</label>
            <textarea name="observacao" id="observacao" class="form-control"><?php echo $dadosEdit['ser_observacao']; ?></textarea>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
          <button type="submit" class="btn btn-primary">Editar</button>
        </div>
      </div>
    </div>
  </form>
  <!-- end: modal update fornecedor -->


  <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>


  <script>
    function validaFinalizacao() {
      if (confirm("Deseja realmente finalizar o agendamento?")) {
        return true;
      } else {
        return false;
      }
    }

    function validaCancelamento() {
      if (confirm("Deseja realmente cancelar o agendamento?")) {
        return true;
      } else {
        return false;
      }
    }

    function validaFinalizar(value) {
      if (confirm("Deseja realmente cadastrar o agendamento?")) {
        return true;
      } else {
        return false;
      }
    }
  </script>

  <script>
    function sumirPopUp() {
      let popup = document.getElementById("popup");
      popup.remove();
    }

    const mascaraMoeda = (event) => {
      const onlyDigits = event.target.value
        .split("")
        .filter(s => /\d/.test(s))
        .join("")
        .padStart(3, "0")
      const digitsFloat = onlyDigits.slice(0, -2) + "." + onlyDigits.slice(-2)
      event.target.value = maskCurrency(digitsFloat)
    }

    const maskCurrency = (valor, locale = 'pt-BR', currency = 'BRL') => {
      return new Intl.NumberFormat(locale, {
        style: 'currency',
        currency
      }).format(valor)
    }
  </script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>

  <?php if ((isset($_GET['edit'])) && ($_GET['edit'] != '')) { ?>
    <script>
      var myModal = new bootstrap.Modal(document.getElementById("editarModal"), {});
      document.onreadystatechange = function() {
        myModal.show();
      };
    </script>
  <?php } ?>

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
  </script>
</body>

</html>