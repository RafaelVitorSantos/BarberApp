<?php
require_once('../src/php/connection/connection.php');

$sql = "SELECT * FROM os_ordemservico LEFT JOIN cad_escola ON os_esc_id = esc_id WHERE os_ativo = 1";
$query = mysqli_query($connection, $sql);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ordem de Serviço</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/ag-grid-community/dist/ag-grid-community.min.js"></script>
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

    .modal-header-2 {
      padding: 1rem 1rem;
      border-bottom: 1px solid #dee2e6;
      border-top-left-radius: calc(.3rem - 1px);
      border-top-right-radius: calc(.3rem - 1px);
    }
  </style>
</head>

<body>
  <?php require_once('menu.php'); ?>

  <h1 class="text-center mt-3 mb-3">Ordem de Serviço</h1>

  <div class="d-flex justify-content-end gap-3 p-2">
    <input type="text" class="form-control">
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#adicionarModal">Adicionar</button>
  </div>

  <table class="table table-striped">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Titulo</th>
        <th scope="col">Status</th>
        <th scope="col"></th>
      </tr>
    </thead>
    <tbody>
      <?php while ($dados = mysqli_fetch_array($query)) { ?>
        <?php
        $nomeStatus = '';
        switch ($dados['os_status']) {
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

        if ($dados['os_esc_id'] > 0) {
          $escolaName = " (" . $dados['esc_nome'] . ")";
        } else {
          $escolaName = '';
        }
        ?>
        <tr>
          <th scope="row"><?php echo $dados['os_id']; ?></th>
          <td><?php echo $dados['os_titulo'] . $escolaName; ?></td>
          <td><?php echo $nomeStatus; ?></td>
          <td>
            <button class="btn btn-transparent pt-0 pb-0" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots" viewBox="0 0 16 16">
                <path d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3" />
              </svg>
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
              <li><a class="dropdown-item" href="?id=<?php echo $dados['os_id']; ?>&nvl=0">Editar</a></li>
              <?php if ($dados['os_status'] == 1) { ?>
                <li><a class="dropdown-item" href="../src/php/os/fechar.php?id=<?php echo $dados['os_id']; ?>" onclick="return validaFechar()">Fechar</a></li>
              <?php } else if ($dados['os_status'] == 3 || $dados['os_status'] == 2) { ?>
                <li><a class="dropdown-item" href="../src/php/os/abrir.php?id=<?php echo $dados['os_id']; ?>" onclick="return validaAbrir()">Abrir</a></li>
              <?php } ?>
              <li><a class="dropdown-item" onclick="return validaDesativar()" href="../src/php/os/desativar.php?id=<?php echo $dados['os_id']; ?>">Desativar</a></li>
            </ul>
          </td>
        </tr>
      <?php } ?>
    </tbody>
  </table>

  <!-- modal create O.S nivel 01 -->
  <form method="POST" action="../src/php/os/criarnivel01.php" class="modal fade" id="adicionarModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Criar Ordem de Serviço</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-1">
            <label for="titulo" class="form-label">Titulo:</label>
            <input type="text" class="form-control" name="titulo" id="titulo" required>
          </div>
          <?php
          $sqlResp = "SELECT * FROM cad_funcionario WHERE func_ativo = 1";
          $queryResp = mysqli_query($connection, $sqlResp);
          ?>
          <div class="mb-1">
            <label for="responsavel" class="form-label">Responsável:</label>
            <select class="form-select" required name="responsavel" id="responsavel">
              <?php while ($dadosResp = mysqli_fetch_array($queryResp)) { ?>
                <option value="<?php echo $dadosResp['func_id']; ?>"><?php echo $dadosResp['func_nome']; ?></option>
              <?php } ?>
            </select>
          </div>
          <?php
          $sqlEsc = "SELECT * FROM cad_cliente WHERE cli_ativo = 1";
          $queryEsc = mysqli_query($connection, $sqlEsc);
          ?>
          <div class="mb-1">
            <label for="cliente" class="form-label">Cliente: </label>
            <select onchange="alteraEscola(this.value, 1)" class="form-select" name="cliente" id="cliente">
              <option value="0"></option>
              <?php while ($dadosEsc = mysqli_fetch_array($queryEsc)) { ?>
                <option value="<?php echo $dadosEsc['cli_id']; ?>"><?php echo $dadosEsc['cli_nome']; ?></option>
              <?php } ?>
            </select>
          </div>
          <?php
          $sqlEsc = "SELECT * FROM cad_escola WHERE esc_ativo = 1";
          $queryEsc = mysqli_query($connection, $sqlEsc);
          ?>
          <div class="mb-1 d-none" id="div_escola">
            <label for="escola" class="form-label">Escola: <small>(opcional)</small></label>
            <select class="form-select" name="escola" id="escola">
              <option value="0"></option>
              <?php while ($dadosEsc = mysqli_fetch_array($queryEsc)) { ?>
                <option value="<?php echo $dadosEsc['esc_id']; ?>"><?php echo $dadosEsc['esc_nome']; ?></option>
              <?php } ?>
            </select>
          </div>
          <hr>
          <div class="row">
            <div class="col-7 pe-2"></div>
            <div class="col-5 ps-2"><small>(opcional)</small></div>
          </div>
          <div class="row mb-1">
            <div class="col-7 pe-2 mb-1">
              <label for="datainicial" class="form-label">Data Inicio:</label>
              <input type="date" class="form-control" name="datainicial" id="datainicial" required>
            </div>
            <div class="col-5 ps-2 mb-1">
              <label for="horarioinicial" class="form-label">Hora Inicial:</label>
              <input type="time" class="form-control" name="horarioinicial" id="horarioinicial">
            </div>
          </div>
          <div class="row">
            <div class="col-7 pe-2"><small>(opcional)</small></div>
            <div class="col-5 ps-2"><small>(opcional)</small></div>
          </div>
          <div class="row">
            <div class="col-7 pe-2 mb-1">
              <label for="datafinal" class="form-label">Data Final:</label>
              <input type="date" class="form-control" name="datafinal" id="datafinal">
            </div>
            <div class="col-5 ps-2 mb-1">
              <label for="horariofinal" class="form-label">Hora Final:</label>
              <input type="time" class="form-control" name="horariofinal" id="horariofinal">
            </div>
          </div>
          <hr>
          <div class="mb-1">
            <label for="tipo" class="form-label">Tipo de Serviço:</label>
            <select class="form-select" name="tipo" id="tipo" required>
              <option value="1">Corretiva</option>
              <option value="2">Preventiva</option>
              <option value="3">Preditiva</option>
              <option value="4">Prescritiva</option>
            </select>
          </div>
          <div class="mb-1">
            <label for="observacao" class="form-label d-flex gap-2 align-items-center">Observação: <small>(opcional)</small></label>
            <textarea class="form-control" name="observacao" id="observacao"></textarea>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
          <button type="submit" onclick="return validaNvl01(1)" class="btn btn-primary">Avançar</button>
        </div>
      </div>
    </div>
  </form>
  <!-- end: modal create O.S nivel 01 -->

  <!-- modal create O.S nivel 02 -->
  <?php
  if (isset($_GET['id'])) {
    $sqlEscola = "SELECT * FROM os_ordemservico WHERE os_id = $_GET[id]";
    $queryEscola = mysqli_query($connection, $sqlEscola);
    $dadosEscola = mysqli_fetch_array($queryEscola);
  ?>
    <form method="POST" action="../src/php/os/criarnivel02.php" class="modal fade" id="modalNivel02" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header-2">
            <div class="row mb-3">
              <div class="col-10">
                <h5 class="modal-title" id="staticBackdropLabel">Criar Ordem de Serviço</h5>
              </div>
              <div class="col-2">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
            </div>
            <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
            <div class="d-flex justify-content-between">
              <a href="?id=<?php echo $_GET['id']; ?>&nvl=0" class="btn btn-secondary">Voltar</a>
              <a href="?id=<?php echo $_GET['id']; ?>&nvl=2" class="btn btn-secondary">Finalizar</a>
            </div>
          </div>
          <div class="modal-body">
            <div class="mb-1">
              <label for="servico" class="form-label">Serviços Prestados(opcional):</label>
              <?php
              $sqlServico = "SELECT * FROM cad_servico WHERE ser_ativo = '1'";
              $queryServico = mysqli_query($connection, $sqlServico);
              ?>
              <select name="servico" id="servico" class="form-select">
                <option value="0" selected></option>
                <?php while ($dadosServico = mysqli_fetch_array($queryServico)) { ?>
                  <option value="<?php echo $dadosServico['ser_id']; ?>"><?php echo $dadosServico['ser_nome']; ?></option>
                <?php } ?>
              </select>
            </div>
            <div class="mb-1">
              <label for="valor" class="form-label">Valor do Serviço (opcional):</label>
              <input type="text" class="form-control" name="valor" id="valor">
            </div>
            <div class="mb-1">
              <label for="equipamento" class="form-label">Equipamento Vinculado (opcional):</label>
              <?php
              $sqlProduto = "SELECT * FROM cad_equipamento WHERE equ_ativo = '1' AND equ_esc_id = $dadosEscola[os_esc_id]";
              $queryProduto = mysqli_query($connection, $sqlProduto);
              ?>
              <select name="equipamento" id="equipamento" class="form-select">
                <option value="0" selected></option>
                <?php while ($dadosProduto = mysqli_fetch_array($queryProduto)) { ?>
                  <option value="<?php echo $dadosProduto['equ_id']; ?>"><?php echo $dadosProduto['equ_numeropatrimonio'] . ' - ' . $dadosProduto['equ_nome']; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="submit" class="btn btn-secondary" data-bs-dismiss="modal">Cadastrar Serviço</button>
            <a class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#verServicos">Ver Serviços</a>
          </div>
        </div>
      </div>
    </form>
  <?php } ?>
  <!-- end: modal create O.S nivel 02 -->

  <!-- modal create O.S nivel 03 -->
  <?php
  if (isset($_GET['id'])) {
    $sqlFinal = "SELECT * FROM  os_ordemservico LEFT JOIN cad_escola ON os_esc_id = esc_id LEFT JOIN cad_funcionario ON os_func_id = func_id WHERE os_id = $_GET[id]";
    $queryFinal = mysqli_query($connection, $sqlFinal);
    $dadosFinal = mysqli_fetch_array($queryFinal);

    $nomeTipo = '';
    switch ($dadosFinal['os_tipo']) {
      case 1:
        $nomeTipo = 'Corretiva';
        break;
      case 2:
        $nomeTipo = 'Preventiva';
        break;
      case 3:
        $nomeTipo = 'Preditiva';
        break;
      default:
        $nomeTipo = 'Prescritiva';
        break;
    }

    $nomeStatus = '';
    switch ($dadosFinal['os_status']) {
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
    <form method="POST" action="../src/php/os/criarnivel03.php" class="modal fade" id="modalNivel03" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header-2">
            <div class="row mb-3">
              <div class="col-10">
                <h5 class="modal-title" id="staticBackdropLabel">Resumo Ordem de Serviço</h5>
              </div>
              <div class="col-2">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
            </div>
            <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
          </div>
          <div class="modal-body pt-0 mt-1">
            <p class="text-center m-0 mt-0 fw-bold">Informações da O.S </p>
            <hr class="mt-1 mb-1">
            <p class="mb-1"><b>1. Titulo:</b> <?php echo $dadosFinal['os_titulo']; ?></p>
            <p class="mb-1"><b>2. Responsável:</b> <?php echo $dadosFinal['func_id'] . ' - ' . $dadosFinal['func_nome']; ?></p>
            <p class="mb-1"><b>3. Escola:</b> <?php echo $escola = ($dadosFinal['os_esc_id'] != 0 && $dadosFinal['os_esc_id'] != '') ? $dadosFinal['esc_id'] . ' - ' . $dadosFinal['esc_nome'] : 'Não foi informado'; ?></p>
            <p class="mb-1"><b>4. Tipo:</b> <?php echo $nomeTipo; ?> <b class="ms-3">4. Status:</b> <?php echo $nomeStatus; ?></p>
            <p class="mb-1"><b>5. Dt. Hr. Inicial:</b> <?php echo $r = ($dadosFinal['os_datainicial'] != '0000-00-00') ? date('d/m/Y', strtotime($dadosFinal['os_datainicial'])) : ''; ?>
              <?php echo $r = ($dadosFinal['os_horarioinicial'] != '00:00:00') ? date('H:i', strtotime($dadosFinal['os_horarioinicial'])) : ''; ?>
            </p>
            <p class="mb-1"><b>6. Dt. Hr. Final:</b> <?php echo $r = ($dadosFinal['os_datafinal'] != '0000-00-00') ? date('d/m/Y', strtotime($dadosFinal['os_datafinal'])) : ''; ?>
              <?php echo $r = ($dadosFinal['os_horariofinal'] != '00:00:00') ? date('H:i', strtotime($dadosFinal['os_horariofinal'])) : ''; ?>
            </p>
            <p class="mb-1"><b>7. Aprovador:</b> <?php echo $aprovador = ($dadosFinal['os_nomeaprovador'] == '') ? $dadosFinal['os_nomeaprovador'] : 'Não foi informado'; ?></p>
            <p class="mb-1"><b>8. Observação:</b> <?php echo $dadosFinal['os_observacao']; ?></p>
            <hr class="mt-1 mb-1">
            <p class="text-center m-0 fw-bold">Serviços Vinculados </p>
            <hr class="mt-1 mb-1">
            <?php
            $sqlItens = "SELECT * FROM os_ordemservicoitem LEFT JOIN cad_equipamento ON osi_equ_id = equ_id LEFT JOIN cad_servico ON osi_ser_id = ser_id WHERE osi_os_id = $_GET[id] AND osi_ativo = 1 ORDER BY osi_dthrinclusao DESC";
            $queryItens = mysqli_query($connection, $sqlItens);
            $i = 1;
            $total = 0;
            ?>
            <?php while ($dadosItens = mysqli_fetch_array($queryItens)) { ?>
              <p class="mb-1"><b>1. Seq:</b> <?php echo $i; ?></p>
              <p class="mb-1"><b>2. Serviço:</b> <?php echo $dadosItens['ser_nome']; ?></p>
              <p class="mb-1"><b>4. Descrição Serviço:</b> <?php echo $dadosItens['ser_descricao']; ?></p>
              <p class="mb-1"><b>5. Equipamento:</b> <?php echo $equipamento = ($dadosItens['equ_nome'] != '' && $dadosItens['equ_nome'] != 0) ? $dadosItens['equ_nome'] : 'N/A'; ?></p>
              <p class="mb-1"><b>6. N° Patrimonio:</b> <?php echo $equipamento = ($dadosItens['equ_numeropatrimonio'] != '' && $dadosItens['equ_numeropatrimonio'] != 0) ? $dadosItens['equ_numeropatrimonio'] : 'N/A'; ?></p>
              <p class="mb-1"><b>7. Valor:</b> <?php echo $valor = ($dadosItens['osi_valor'] != 0) ? number_format($dadosItens['osi_valor'], 2, ",", ".") : 'Incluso'; ?></p>
              <hr class="mt-1 mb-1">
            <?php $i++;
              $total += $dadosItens['osi_valor'];
            } ?>
            <p class="text-center m-0 fw-bold">Totais </p>
            <hr class="mt-1 mb-1">
            <p class="mb-1"><b>1. Valor Total:</b> <?php echo number_format($total, 2, ",", "."); ?></p>
            <p class="mb-1"><b>2. Qtde. Serviços:</b> <?php echo $i - 1; ?></p>
          </div>
          <div class="modal-footer justify-content-between">
            <a href="?id=<?php echo $_GET['id']; ?>&nvl=0" class="btn btn-secondary">Voltar</a>
            <a class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Finalizar</a>
          </div>
        </div>
      </div>
    </form>
  <?php } ?>
  <!-- end: modal create O.S nivel 03 -->

  <!-- modal update O.S nivel 01 -->
  <?php
  if (isset($_GET['id'])) {
    $sqlOS = "SELECT * FROM os_ordemservico WHERE os_id = $_GET[id]";
    $queryOS = mysqli_query($connection, $sqlOS);
    $dadosOS = mysqli_fetch_array($queryOS);
  ?>
    <form method="POST" action="../src/php/os/editarnivel01.php" class="modal fade" id="modalNivel01" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">Editar Ordem de Serviço</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-1">
              <label for="identificador" class="form-label">Identificador:</label>
              <input type="text" class="form-control" value="<?php echo $dadosOS['os_id']; ?>" name="identificador" id="identificador" disabled>
              <input type="hidden" name="id" value="<?php echo $dadosOS['os_id']; ?>">
            </div>
            <div class="mb-1">
              <label for="titulo" class="form-label">Titulo:</label>
              <input type="text" class="form-control" value="<?php echo $dadosOS['os_titulo']; ?>" name="titulo" id="titulo" required>
            </div>
            <?php
            $sqlResp = "SELECT * FROM cad_funcionario WHERE func_ativo = 1";
            $queryResp = mysqli_query($connection, $sqlResp);
            ?>
            <div class="mb-1">
              <label for="responsavel" class="form-label">Responsável:</label>
              <select class="form-select" name="responsavel" id="responsavel">
                <?php while ($dadosResp = mysqli_fetch_array($queryResp)) { ?>
                  <option value="<?php echo $dadosResp['func_id']; ?>" <?php echo $selected = ($dadosResp['func_id'] != $dadosOS['os_func_id']) ?: ' selected '; ?>><?php echo $dadosResp['func_nome']; ?></option>
                <?php } ?>
              </select>
            </div>
            <?php
            $sqlCli = "SELECT * FROM cad_cliente WHERE cli_ativo = 1";
            $queryCli = mysqli_query($connection, $sqlCli);
            ?>
            <div class="mb-1">
              <label for="cliente" class="form-label">Cliente: </label>
              <select onchange="alteraEscola(this.value, 2)" class="form-select" required name="cliente" id="clienteedit">
                <option value="0"></option>
                <?php while ($dadosCli = mysqli_fetch_array($queryCli)) { ?>
                  <option value="<?php echo $dadosCli['cli_id']; ?>" <?php echo $selected = ($dadosCli['cli_id'] != $dadosOS['os_cli_id']) ?: ' selected '; ?>><?php echo $dadosCli['cli_nome']; ?></option>
                <?php } ?>
              </select>
            </div>
            <?php
            $sqlEsc = "SELECT * FROM cad_escola WHERE esc_ativo = 1";
            $queryEsc = mysqli_query($connection, $sqlEsc);
            ?>
            <div id="div_escolaedit" class="mb-1">
              <label for="escola" class="form-label">Escola: <small>(opcional)</small></label>
              <select class="form-select" required name="escola" id="escola">
                <option value="0"></option>
                <?php while ($dadosEsc = mysqli_fetch_array($queryEsc)) { ?>
                  <option value="<?php echo $dadosEsc['esc_id']; ?>" <?php echo $selected = ($dadosEsc['esc_id'] != $dadosOS['os_esc_id']) ?: ' selected '; ?>><?php echo $dadosEsc['esc_nome']; ?></option>
                <?php } ?>
              </select>
            </div>
            <hr>
            <div class="row">
              <div class="col-7 pe-2"></div>
              <div class="col-5 ps-2"><small>(opcional)</small></div>
            </div>
            <div class="row mb-1">
              <div class="col-7 pe-2 mb-1">
                <label for="datainicial" class="form-label">Data Inicio:</label>
                <input type="date" class="form-control" name="datainicial" id="datainicial" value="<?php echo $dadosOS['os_datainicial']; ?>" required>
              </div>
              <div class="col-5 ps-2 mb-1">
                <label for="horarioinicial" class="form-label">Hora Inicial:</label>
                <input type="time" class="form-control" name="horarioinicial" value="<?php echo $dadosOS['os_horarioinicial']; ?>" id="horarioinicial">
              </div>
            </div>
            <div class="row">
              <div class="col-7 pe-2"><small>(opcional)</small></div>
              <div class="col-5 ps-2"><small>(opcional)</small></div>
            </div>
            <div class="row">
              <div class="col-7 pe-2 mb-1">
                <label for="datafinal" class="form-label">Data Final:</label>
                <input type="date" class="form-control" name="datafinal" value="<?php echo $dadosOS['os_datafinal']; ?>" id="datafinal">
              </div>
              <div class="col-5 ps-2 mb-1">
                <label for="horariofinal" class="form-label">Hora Final:</label>
                <input type="time" class="form-control" name="horariofinal" value="<?php echo $dadosOS['os_horariofinal']; ?>" id="horariofinal">
              </div>
            </div>
            <hr>
            <div class="mb-1">
              <label for="tipo" class="form-label">Tipo de Serviço:</label>
              <select class="form-select" name="tipo" id="tipo" required>
                <option value="1" <?php echo $selected = ($dadosOS['os_tipo'] != 1) ?: 'selected'; ?>>Corretiva</option>
                <option value="2" <?php echo $selected = ($dadosOS['os_tipo'] != 2) ?: 'selected'; ?>>Preventiva</option>
                <option value="3" <?php echo $selected = ($dadosOS['os_tipo'] != 3) ?: 'selected'; ?>>Preditiva</option>
                <option value="4" <?php echo $selected = ($dadosOS['os_tipo'] != 4) ?: 'selected'; ?>>Prescritiva</option>
              </select>
            </div>
            <div class="mb-1">
              <label for="observacao" class="form-label d-flex gap-2 align-items-center">Observação: <small>(opcional)</small></label>
              <textarea class="form-control" name="observacao" id="observacao"><?php echo $dadosOS['os_observacao']; ?></textarea>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            <button type="submit" onclick="return validaNvl01(2)" class="btn btn-primary">Avançar</button>
          </div>
        </div>
      </div>
    </form>
  <?php
  }
  ?>
  <!-- end: modal update O.S nivel 01 -->

  <!-- modal update O.S nivel 02 -->
  <?php
  if (isset($_GET['os_edit'])) {
    $sqlItem = "SELECT * FROM os_ordemservicoitem WHERE osi_id = $_GET[os_edit]";
    $queryItem = mysqli_query($connection, $sqlItem);
    $dadosItem = mysqli_fetch_array($queryItem);

    $sqlEscola = "SELECT * FROM os_ordemservico WHERE os_id = $dadosItem[osi_os_id]";
    $queryEscola = mysqli_query($connection, $sqlEscola);
    $dadosEscola = mysqli_fetch_array($queryEscola);
  ?>
    <form method="POST" action="../src/php/os/item/editar.php" class="modal fade" id="editarNivel02" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header-2">
            <div class="row mb-3">
              <div class="col-10">
                <h5 class="modal-title" id="staticBackdropLabel">Editar Ordem de Serviço</h5>
              </div>
              <div class="col-2">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
            </div>
            <input type="hidden" name="id" value="<?php echo $_GET['os_edit']; ?>">
            <div class="d-flex justify-content-between">
              <a href="?id=<?php echo $_GET['id']; ?>&nvl=0" class="btn btn-secondary">Voltar</a>
              <a href="?id=<?php echo $_GET['id']; ?>&nvl=2" class="btn btn-secondary">Finalizar</a>
            </div>
          </div>
          <div class="modal-body">
            <div class="mb-1">
              <label for="servico" class="form-label">Serviços Prestados(opcional):</label>
              <?php
              $sqlServico = "SELECT * FROM cad_servico WHERE ser_ativo = '1'";
              $queryServico = mysqli_query($connection, $sqlServico);
              ?>
              <select name="servico" id="servico" class="form-select">
                <option value="0" selected></option>
                <?php while ($dadosServico = mysqli_fetch_array($queryServico)) { ?>
                  <option value="<?php echo $dadosServico['ser_id']; ?>" <?php echo $selected = ($dadosServico['ser_id'] != $dadosItem['osi_ser_id']) ?: ' SELECTED '; ?>><?php echo $dadosServico['ser_nome']; ?></option>
                <?php } ?>
              </select>
            </div>
            <div class="mb-1">
              <label for="valor" class="form-label">Valor do Serviço (opcional):</label>
              <input type="text" class="form-control" name="valor" id="valor" value="<?php echo $dadosItem['osi_valor']; ?>">
            </div>
            <div class="mb-1">
              <label for="equipamento" class="form-label">Equipamento Vinculado (opcional):</label>
              <?php
              $sqlProduto = "SELECT * FROM cad_equipamento WHERE equ_ativo = '1' AND equ_esc_id = $dadosEscola[os_esc_id]";
              $queryProduto = mysqli_query($connection, $sqlProduto);
              ?>
              <select name="equipamento" id="equipamento" class="form-select">
                <option value="0" selected></option>
                <?php while ($dadosProduto = mysqli_fetch_array($queryProduto)) { ?>
                  <option value="<?php echo $dadosProduto['equ_id']; ?>" <?php echo $selected = ($dadosProduto['equ_id'] != $dadosItem['osi_equ_id']) ?: ' SELECTED '; ?>><?php echo $dadosProduto['equ_numeropatrimonio'] . ' - ' . $dadosProduto['equ_nome']; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="submit" class="btn btn-secondary" data-bs-dismiss="modal">Editar Serviço</button>
            <a class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#verServicos">Ver Serviços</a>
          </div>
        </div>
      </div>
    </form>
  <?php } ?>
  <!-- end: modal update O.S nivel 02 -->

  <!-- modal ver serviços -->
  <?php
  if (isset($_GET['id'])) {
    $sqlItens = "SELECT * FROM os_ordemservicoitem LEFT JOIN cad_servico ON osi_ser_id = ser_id WHERE osi_os_id = $_GET[id] and osi_ativo = 1";
    $queryItens = mysqli_query($connection, $sqlItens);
  ?>
    <form method="POST" action="../src/php/os/criarnivel02.php" class="modal fade" id="verServicos" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header-2">
            <div class="row mb-3">
              <div class="col-10">
                <h5 class="modal-title" id="staticBackdropLabel">Ver Serviços</h5>
              </div>
              <div class="col-2">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
            </div>
          </div>
          <div class="modal-body" style="min-height: 400px">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th scope="col">Nome</th>
                  <th scope="col">Valor</th>
                  <th scope="col"></th>
                </tr>
              </thead>
              <tbody>
                <?php while ($dadosItens = mysqli_fetch_array($queryItens)) { ?>
                  <tr>
                    <th scope="row"><?php echo $dadosItens['ser_nome']; ?></th>
                    <td><?php echo number_format($dadosItens['osi_valor'], 2, ",", "."); ?></td>
                    <td>
                      <button class="btn btn-transparent pt-0 pb-0" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots" viewBox="0 0 16 16">
                          <path d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3" />
                        </svg>
                      </button>
                      <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                        <li><a class="dropdown-item" href="?id=<?php echo $dadosItens['osi_os_id']; ?>&os_edit=<?php echo $dadosItens['osi_id']; ?>">Editar</a></li>
                        <li><a class="dropdown-item" onclick="return validaDesativar()" href="../src/php/os/item/desativar.php?id=<?php echo $dadosItens['osi_id']; ?>">Desativar</a></li>
                      </ul>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
          <div class="modal-footer justify-content-between">

          </div>
        </div>
      </div>
    </form>
  <?php } ?>
  <!-- end: modal ver serviços -->

  <script>
    function validaNvl01(valor) {
      if (valor == 1) {
        let cliente = document.getElementById('cliente');
        if (cliente.value > 0) {
          return true;
        } else {
          alert("Preencha o campo Cliente!");
          return false;
        }
      } else if (valor == 2) {
        let cliente = document.getElementById('clienteedit');
        if (cliente.value > 0) {
          return true;
        } else {
          alert("Preencha o campo Cliente!");
          return false;
        }
      }
    }

    function alteraEscola(valor, tipo) {
      if (tipo == 1) {
        let div_escola = document.getElementById('div_escola');
        if (valor > 0) {
          div_escola.classList.remove('d-none');
        } else {
          div_escola.classList.add('d-none');
        }
      } else if (tipo == 2) {
        let div_escola = document.getElementById('div_escolaedit');
        if (valor > 0) {
          div_escola.classList.remove('d-none');
        } else {
          div_escola.classList.add('d-none');
        }
      } else {
        alert('Algo de Errado aconteceu!');
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

    function validaFechar() {
      if (confirm("Deseja realmente fechar essa Ordem de Serviço?")) {
        return true;
      } else {
        return false;
      }
    }

    function validaAbrir() {
      if (confirm("Deseja realmente abrir essa Ordem de Serviço?")) {
        return true;
      } else {
        return false;
      }
    }
  </script>

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

  <?php if ((isset($_GET['nvl'])) && ($_GET['nvl'] == '2')) { ?>
    <script>
      var modalNivel02 = new bootstrap.Modal(document.getElementById("modalNivel03"), {});
      document.onreadystatechange = function() {
        modalNivel02.show();
      };
    </script>
  <?php } ?>

  <?php if (isset($_GET['os_edit'])) { ?>
    <script>
      var modalNivel02 = new bootstrap.Modal(document.getElementById("editarNivel02"), {});
      document.onreadystatechange = function() {
        modalNivel02.show();
      };
    </script>
  <?php } ?>

  <?php if ((isset($_GET['nvl'])) && ($_GET['nvl'] == '1')) { ?>
    <script>
      var modalNivel02 = new bootstrap.Modal(document.getElementById("modalNivel02"), {});
      document.onreadystatechange = function() {
        modalNivel02.show();
      };
    </script>
  <?php } ?>

  <?php if ((isset($_GET['nvl'])) && ($_GET['nvl'] == '0')) { ?>
    <script>
      var modalNivel02 = new bootstrap.Modal(document.getElementById("modalNivel01"), {});
      document.onreadystatechange = function() {
        modalNivel02.show();
      };
    </script>
  <?php } ?>
</body>

</html>