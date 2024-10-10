<?php
require_once('../src/php/connection/connection.php');

$sql = "SELECT * FROM cad_equipamento WHERE equ_ativo = 1";
$query = mysqli_query($connection, $sql);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Equipamento</title>
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
  </style>
</head>

<body>
  <?php require_once('menu.php'); ?>

  <h1 class="text-center mt-3 mb-3">Equipamentos</h1>

  <div class="d-flex justify-content-end gap-3 p-2">
    <input type="text" class="form-control">
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#adicionarModal">Adicionar</button>
  </div>

  <table class="table table-striped">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Nome</th>
        <th scope="col">N° Patrimonio</th>
        <th scope="col"></th>
      </tr>
    </thead>
    <tbody>
      <?php while ($dados = mysqli_fetch_array($query)) { ?>
        <tr>
          <th scope="row"><?php echo $dados['equ_id']; ?></th>
          <td><?php echo $dados['equ_nome']; ?></td>
          <td><?php echo $dados['equ_numeropatrimonio']; ?></td>
          <td>
            <button class="btn btn-transparent pt-0 pb-0" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots" viewBox="0 0 16 16">
                <path d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3" />
              </svg>
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
              <li><a class="dropdown-item" href="?edit=<?php echo $dados['equ_id']; ?>">Editar</a></li>
              <li><a class="dropdown-item" onclick="return validaDesativar()" href="../src/php/equipamento/desativar.php?id=<?php echo $dados['equ_id']; ?>">Desativar</a></li>
            </ul>
          </td>
        </tr>
      <?php } ?>
    </tbody>
  </table>

  <!-- modal create serviço -->
  <form method="POST" action="../src/php/equipamento/criar.php" class="modal fade" id="adicionarModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Criar Equipamento</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-1">
            <label for="nome" class="form-label">Nome:</label>
            <input type="text" class="form-control" name="nome" id="nome" required>
          </div>
          <div class="mb-1">
            <label for="patrimonio" class="form-label">N° Patrimonio: <small>(opcional)</small></label>
            <input type="text" class="form-control" name="patrimonio" id="patrimonio">
          </div>
          <div class="mb-1">
            <label for="escola" class="form-label">Escola: <small>(opcional)</small></label>
            <select name="escola" id="escola" class="form-select">
              <option value="0" selected></option>
              <?php
              $sqlEscola = "SELECT * FROM cad_escola WHERE esc_ativo = 1";
              $queryEscola = mysqli_query($connection, $sqlEscola);
              ?>
              <?php while ($dadosEscola = mysqli_fetch_array($queryEscola)) { ?>
                <option value="<?php echo $dadosEscola['esc_id']; ?>"><?php echo $dadosEscola['esc_nome']; ?></option>
              <?php } ?>
            </select>
          </div>
          <div class="mb-1">
            <label for="descricao" class="form-label d-flex gap-2 align-items-center">Descrição: <small>(opcional)</small></label>
            <textarea class="form-control" name="descricao" id="descricao"></textarea>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
          <button type="submit" class="btn btn-primary">Criar</button>
        </div>
      </div>
    </div>
  </form>
  <!-- end: modal create serviço -->

  <!-- modal update serviço -->
  <?php
  if (isset($_GET['edit'])) {
    $id = $_GET['edit'];

    $sqlEdit = "SELECT * FROM cad_equipamento WHERE equ_id = $id LIMIT 1";
    $queryEdit = mysqli_query($connection, $sqlEdit);
    $dadosEdit = mysqli_fetch_array($queryEdit);
  }
  ?>
  <form method="POST" action="../src/php/equipamento/editar.php" class="modal fade" id="editarModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Editar Equipamento</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-1">
            <label for="nome" class="form-label">Identificador:</label>
            <input type="text" class="form-control" value="<?php echo $dadosEdit['equ_id']; ?>" disabled>
            <input type="hidden" name="id" value="<?php echo $dadosEdit['equ_id']; ?>">
          </div>
          <div class="mb-1">
            <label for="nome" class="form-label">Nome:</label>
            <input type="text" class="form-control" name="nome" id="nome" value="<?php echo $dadosEdit['equ_nome']; ?>" required>
          </div>
          <div class="mb-1">
            <label for="patrimonio" class="form-label">N° Patrimonio: <small>(opcional)</small></label>
            <input type="text" class="form-control" name="patrimonio" value="<?php echo $dadosEdit['equ_numeropatrimonio']; ?>" id="patrimonio">
          </div>
          <div class="mb-1">
            <label for="escola" class="form-label">Escola: <small>(opcional)</small></label>
            <select name="escola" id="escola" class="form-select">
              <option value="0"></option>
              <?php
              $sqlEscola = "SELECT * FROM cad_escola WHERE esc_ativo = 1";
              $queryEscola = mysqli_query($connection, $sqlEscola);
              ?>
              <?php while ($dadosEscola = mysqli_fetch_array($queryEscola)) { ?>
                <option value="<?php echo $dadosEscola['esc_id']; ?>" <?php echo $select = ($dadosEscola['esc_id'] != $dadosEdit['equ_esc_id']) ?: 'SELECTED'; ?>><?php echo $dadosEscola['esc_nome']; ?></option>
              <?php } ?>
            </select>
          </div>
          <div class="mb-1">
            <label for="descricao" class="form-label d-flex gap-2 align-items-center">Descrição: <small>(opcional)</small></label>
            <textarea class="form-control" name="descricao" id="descricao"><?php echo $dadosEdit['equ_descricao']; ?></textarea>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
          <button type="submit" class="btn btn-primary">Criar</button>
        </div>
      </div>
    </div>
  </form>
  <!-- end: modal update serviço -->

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
  </script>

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

  <?php if ((isset($_GET['edit'])) && ($_GET['edit'] != '')) { ?>
    <script>
      var myModal = new bootstrap.Modal(document.getElementById("editarModal"), {});
      document.onreadystatechange = function() {
        myModal.show();
      };
    </script>
  <?php } ?>
</body>

</html>