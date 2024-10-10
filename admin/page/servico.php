<?php
require_once('../../src/php/connection/connection.php');
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Serviço</title>
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
  $sql = "SELECT * FROM ser_servico WHERE ser_ativo = 1";
  $query = mysqli_query($connection, $sql);
  ?>

  <h1 class="text-center mb-3" style="margin-top: 96px;">Serviços</h1>

  <div class="d-flex justify-content-end gap-3 p-2">
    <input type="text" class="form-control">
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#adicionarModal">Adicionar</button>
  </div>

  <table class="table table-striped">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Nome</th>
        <th scope="col">Valor</th>
        <th scope="col"></th>
      </tr>
    </thead>
    <tbody>
      <?php while ($dados = mysqli_fetch_array($query)) { ?>
        <tr>
          <th scope="row"><?php echo $dados['ser_id']; ?></th>
          <td><?php echo $dados['ser_nome']; ?></td>
          <td><?php echo 'R$ ' . number_format($dados['ser_valor'], 2, ",", "."); ?></td>
          <td>
            <button class="btn btn-transparent pt-0 pb-0" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots" viewBox="0 0 16 16">
                <path d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3" />
              </svg>
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
              <li><a class="dropdown-item" href="?edit=<?php echo $dados['ser_id']; ?>">Editar</a></li>
              <li><a class="dropdown-item" onclick="return validaDesativar()" href="../../src/php/admin/servico/desativar.php?id=<?php echo $dados['ser_id']; ?>">Desativar</a></li>
            </ul>
          </td>
        </tr>
      <?php } ?>
    </tbody>
  </table>

  <!-- modal create fornecedor -->
  <form method="POST" action="../../src/php/admin/servico/criar.php" class="modal fade" id="adicionarModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Criar serviço</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-2">
            <label for="nome" class="form-label">Nome:</label>
            <input type="text" class="form-control" name="nome" id="nome" required>
          </div>
          <div class="d-flex gap-2 w-100">
            <div class="mb-1 w-50">
              <label for="valor" class="form-label">Valor:</label>
              <input type="text" onInput="mascaraMoeda(event);" class="form-control" name="valor" id="valor" required>
            </div>
            <div class="mb-1 w-50">
              <label for="duracao" class="form-label">Duração (hr:min):</label>
              <input type="time" class="form-control" name="duracao" id="duracao" required>
            </div>
          </div>
          <div class="mb-2">
            <label for="observacao" class="form-label">Observação:</label>
            <textarea name="observacao" id="observacao" class="form-control"></textarea>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
          <button type="submit" class="btn btn-primary">Criar</button>
        </div>
      </div>
    </div>
  </form>
  <!-- end: modal create fornecedor -->

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
    function validaDesativar() {
      if (confirm("Deseja realmente desativar?")) {
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
</body>

</html>