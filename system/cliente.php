<?php
require_once('../src/php/connection/connection.php');

$sql = "SELECT * FROM cad_cliente WHERE cli_ativo = 1";
$query = mysqli_query($connection, $sql);

mysqli_query($connection, "SET NAMES 'utf8'");
mysqli_query($connection, 'SET character_set_connection=utf8');
mysqli_query($connection, 'SET character_set_client=utf8');
mysqli_query($connection, 'SET character_set_results=utf8');
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cliente</title>
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

  <h1 class="text-center mt-3 mb-3">Clientes</h1>

  <div class="d-flex justify-content-end gap-3 p-2">
    <input type="text" class="form-control">
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#adicionarModal">Adicionar</button>
  </div>

  <table class="table table-striped">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Nome</th>
        <th scope="col">Telefone</th>
        <th scope="col"></th>
      </tr>
    </thead>
    <tbody>
      <?php while ($dados = mysqli_fetch_array($query)) { ?>
        <tr>
          <th scope="row"><?php echo $dados['cli_id']; ?></th>
          <td><?php echo $dados['cli_nome']; ?></td>
          <td><?php echo $dados['cli_telefone']; ?></td>
          <td>
            <button class="btn btn-transparent pt-0 pb-0" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots" viewBox="0 0 16 16">
                <path d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3" />
              </svg>
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
              <li><a class="dropdown-item" href="?edit=<?php echo $dados['cli_id']; ?>">Editar</a></li>
              <li><a class="dropdown-item" onclick="return validaDesativar()" href="../src/php/cliente/desativar.php?id=<?php echo $dados['cli_id']; ?>">Desativar</a></li>
            </ul>
          </td>
        </tr>
      <?php } ?>
    </tbody>
  </table>

  <!-- modal create cliente -->
  <?php
  $sqlCidade = "SELECT * FROM cad_cidade ORDER BY cid_id";
  $queryCidade = mysqli_query($connection, $sqlCidade);
  ?>
  <form method="POST" action="../src/php/cliente/criar.php" class="modal fade" id="adicionarModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Criar Cliente</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-1">
            <label for="nome" class="form-label">Nome:</label>
            <input type="text" class="form-control" name="nome" id="nome" required>
          </div>
          <div class="mb-1">
            <label for="telefone" class="form-label">Telefone:</label>
            <input type="tel" class="form-control" name="telefone" id="telefone" required>
          </div>
          <div class="mb-1">
            <label for="email" class="form-label">Email: <small>(opcional)</small></label>
            <input type="email" class="form-control" name="email" id="email">
          </div>
          <div class="row">
            <div class="col-8 mb-1">
              <label for="cep" class="form-label">CEP:</label>
              <input type="text" onchange="pesquisarCep()" class="form-control" name="cep" id="cep" required>
            </div>
            <div class="col-4 mb-1">
              <label for="numero" class="form-label">Número:</label>
              <input type="number" class="form-control" name="numero" id="numero" required>
            </div>
          </div>
          <div class="mb-1">
            <label for="endereco" class="form-label">Endereço:</label>
            <input type="text" class="form-control" name="endereco" id="endereco" required>
          </div>
          <div class="mb-1">
            <label for="valor" class="form-label">Cidade:</label>
            <select name="cidade" id="cidade" class="form-select">
              <?php echo $i = 1; ?>
              <?php while ($dadosCidade = mysqli_fetch_array($queryCidade)) { ?>
                <option data-posicao="<?php echo $i; ?>" id="<?php echo $dadosCidade['cid_ibge']; ?>" value="<?php echo $dadosCidade['cid_id']; ?>"><?php echo $dadosCidade['cid_nome']; ?></option>
              <?php $i++;
              } ?>
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
  <!-- end: modal create cliente -->

  <!-- modal update cliente -->
  <?php
  if (isset($_GET['edit'])) {
    $id = $_GET['edit'];

    $sqlEdit = "SELECT * FROM cad_cliente WHERE cli_id = $id LIMIT 1";
    $queryEdit = mysqli_query($connection, $sqlEdit);
    $dadosEdit = mysqli_fetch_array($queryEdit);


    $sqlCidade = "SELECT * FROM cad_cidade ORDER BY cid_id";
    $queryCidade = mysqli_query($connection, $sqlCidade);
  }
  ?>

  <form method="POST" action="../src/php/cliente/editar.php" class="modal fade" id="editarModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Editar Cliente</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-1">
            <label for="nome" class="form-label">Identificador:</label>
            <input type="text" class="form-control" value="<?php echo $dadosEdit['cli_id']; ?>" disabled>
            <input type="hidden" name="id" value="<?php echo $dadosEdit['cli_id']; ?>">
          </div>
          <div class="mb-1">
            <label for="nome" class="form-label">Nome:</label>
            <input type="text" class="form-control" value="<?php echo $dadosEdit['cli_nome']; ?>" name="nome" id="nome" required>
          </div>
          <div class="mb-1">
            <label for="telefone" class="form-label">Telefone:</label>
            <input type="tel" class="form-control" value="<?php echo $dadosEdit['cli_telefone']; ?>" name="telefone" id="telefone" required>
          </div>
          <div class="mb-1">
            <label for="email" class="form-label">Email: <small>(opcional)</small></label>
            <input type="email" class="form-control" value="<?php echo $dadosEdit['cli_email']; ?>" name="email" id="email">
          </div>
          <div class="row">
            <div class="col-8 mb-1">
              <label for="cepedit" class="form-label">CEP:</label>
              <input type="text" onchange="pesquisarCepEdit()" value="<?php echo $dadosEdit['cli_cep']; ?>" class="form-control" name="cep" id="cepedit" required>
            </div>
            <div class="col-4 mb-1">
              <label for="numero" class="form-label">Número:</label>
              <input type="number" class="form-control" value="<?php echo $dadosEdit['cli_numero']; ?>" name="numero" id="numero" required>
            </div>
          </div>
          <div class="mb-1">
            <label for="enderecoedit" class="form-label">Endereço:</label>
            <input type="text" class="form-control" value="<?php echo $dadosEdit['cli_endereco']; ?>" name="endereco" id="enderecoedit" required>
          </div>
          <div class="mb-1">
            <label for="valor" class="form-label">Cidade:</label>
            <select name="cidade" id="cidadeedit" class="form-select">
              <?php echo $i = 1; ?>
              <?php while ($dadosCidade = mysqli_fetch_array($queryCidade)) { ?>
                <option <?php echo $selected = ($dadosCidade['cid_id'] != $dadosEdit['cli_cid_id']) ?: ' SELECTED '; ?> data-posicao="<?php echo $i; ?>" id="<?php echo $dadosCidade['cid_ibge']; ?>edit" value="<?php echo $dadosCidade['cid_id']; ?>"><?php echo $dadosCidade['cid_nome']; ?></option>
              <?php $i++;
              } ?>
            </select>
          </div>
          <div class="mb-1">
            <label for="descricao" class="form-label d-flex gap-2 align-items-center">Descrição: <small>(opcional)</small></label>
            <textarea class="form-control" name="descricao" id="descricao"><?php echo $dadosEdit['cli_descricao']; ?></textarea>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
          <button type="submit" class="btn btn-primary">Editar</button>
        </div>
      </div>
    </div>
  </form>
  <!-- end: modal update cliente -->

  <script>
    const eNumero = (numero) => /^[0-9]+$/.test(numero);

    const cepValido = (cep) => cep.length == 8 && eNumero(cep);

    const pesquisarCep = async () => {
      let cep = document.getElementById('cep').value;
      cep = cep.replace("-", "");
      const url = `https://viacep.com.br/ws/${cep}/json/`;
      if (cepValido(cep)) {
        const dados = await fetch(url);
        const endereco = await dados.json();
        if (endereco.hasOwnProperty('erro')) {
          alert("CEP Não encontrado");
        } else {
          let cidade = document.getElementById(`${endereco.ibge}`).getAttribute('data-posicao');
          let select = document.getElementById('cidade');
          select.value = cidade;

          document.getElementById('endereco').value = endereco.logradouro + ', ' + endereco.bairro
        }
      } else {
        alert("CEP Incorreto");
      }

    }

    const pesquisarCepEdit = async () => {
      let cep = document.getElementById('cepedit').value;
      cep = cep.replace("-", "");
      const url = `https://viacep.com.br/ws/${cep}/json/`;
      if (cepValido(cep)) {
        const dados = await fetch(url);
        const endereco = await dados.json();
        if (endereco.hasOwnProperty('erro')) {
          alert("CEP Não encontrado");
        } else {
          let cidade = document.getElementById(`${endereco.ibge}edit`).getAttribute('data-posicao');
          let select = document.getElementById('cidadeedit');
          select.value = cidade;

          document.getElementById('enderecoedit').value = endereco.logradouro + ', ' + endereco.bairro
        }
      } else {
        alert("CEP Incorreto");
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