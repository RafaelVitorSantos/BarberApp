<?php
require_once('../src/php/connection/connection.php');

$sql = "SELECT * FROM cad_filial WHERE fil_ativo = 1";
$query = mysqli_query($connection, $sql);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Empresa/Filial</title>
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

  <h1 class="text-center mt-3 mb-3">Empresa/Filial</h1>

  <div class="d-flex justify-content-end gap-3 p-2">
    <input type="text" class="form-control">
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#adicionarModal">Adicionar</button>
  </div>

  <table class="table table-striped">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Nome</th>
        <th scope="col"></th>
      </tr>
    </thead>
    <tbody>
      <?php while ($dados = mysqli_fetch_array($query)) { ?>
        <tr>
          <th scope="row"><?php echo $dados['fil_id']; ?></th>
          <td><?php echo $dados['fil_nome']; ?></td>
          <td>
            <button class="btn btn-transparent pt-0 pb-0" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots" viewBox="0 0 16 16">
                <path d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3" />
              </svg>
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
              <li><a class="dropdown-item" href="?edit=<?php echo $dados['fil_id']; ?>">Editar</a></li>
              <li><a class="dropdown-item" onclick="return validaDesativar()" href="../src/php/filial/desativar.php?id=<?php echo $dados['fil_id']; ?>">Desativar</a></li>
            </ul>
          </td>
        </tr>
      <?php } ?>
    </tbody>
  </table>

  <!-- modal create filial -->
  <form method="POST" action="../src/php/filial/criar.php" class="modal fade" id="adicionarModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Criar Empresa/Filial</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-1">
            <label for="nome" class="form-label">Nome:</label>
            <input type="text" class="form-control" name="nome" id="nome" required>
          </div>
          <div class="mb-1">
            <label for="cnpjt" class="form-label">CNPJ:</label>
            <input type="text" class="form-control" name="cnpj" id="cnpjt" min="10" required>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
          <button type="submit" class="btn btn-primary">Criar</button>
        </div>
      </div>
    </div>
  </form>
  <!-- end: modal create filial -->

  <script>
    document.getElementById('cnpjt').addEventListener('input', function(e) {
      var value = e.target.value;
      var rawValue = value.replace(/\D/g, ''); // Remove tudo que não é número

      // Verifica se o CNPJ tem 15 dígitos e se o primeiro dígito é '0'
      if (rawValue.length === 15 && rawValue.startsWith('0')) {
        // Verifica se, ao remover o '0', o restante é um CNPJ válido
        var potentialCNPJ = rawValue.substring(1);
        // Atualiza rawValue para o CNPJ sem o '0' inicial
        if (validaCNPJ(potentialCNPJ)) rawValue = potentialCNPJ;
      }

      var cnpjPattern = rawValue
        .replace(/^(\d{2})(\d)/, '$1.$2') // Adiciona ponto após o segundo dígito
        .replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3') // Adiciona ponto após o quinto dígito
        .replace(/\.(\d{3})(\d)/, '.$1/$2') // Adiciona barra após o oitavo dígito
        .replace(/(\d{4})(\d)/, '$1-$2') // Adiciona traço após o décimo segundo dígito
        .replace(/(-\d{2})\d+?$/, '$1'); // Impede a entrada de mais de 14 dígitos
      e.target.value = cnpjPattern;
    });

    function validaCNPJ(cnpj) {
      cnpj = cnpj.replace(/[^\d]+/g, '');
      if (cnpj == '' || cnpj.length != 14 || /^(\d)\1{13}$/.test(cnpj)) return false;

      // Valida DVs
      let tamanho = cnpj.length - 2
      let numeros = cnpj.substring(0, tamanho);
      let digitos = cnpj.substring(tamanho);
      let soma = 0;
      let pos = tamanho - 7;
      for (let i = tamanho; i >= 1; i--) {
        soma += numeros.charAt(tamanho - i) * pos--;
        if (pos < 2) pos = 9;
      }
      let resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
      if (resultado != digitos.charAt(0)) return false;

      tamanho = tamanho + 1;
      numeros = cnpj.substring(0, tamanho);
      soma = 0;
      pos = tamanho - 7;
      for (let i = tamanho; i >= 1; i--) {
        soma += numeros.charAt(tamanho - i) * pos--;
        if (pos < 2) pos = 9;
      }
      resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
      if (resultado != digitos.charAt(1)) return false;
      return true;
    }

    document.getElementById('adicionarModal').addEventListener('submit', function(e) {
      var cnpj = document.getElementById('cnpjt').value;
      if (!validaCNPJ(cnpj)) {
        e.preventDefault(); // Impede o envio do formulário
        alert('CNPJ inválido. Por favor, verifique o número digitado.');
        document.getElementById('cnpjt').focus(); // Foca no campo de CNPJ após detectar erro
      }
    });
  </script>

  <!-- modal update filial -->
  <?php
  if (isset($_GET['edit'])) {
    $id = $_GET['edit'];

    $sqlEdit = "SELECT * FROM cad_filial WHERE fil_id = $id LIMIT 1";
    $queryEdit = mysqli_query($connection, $sqlEdit);
    $dadosEdit = mysqli_fetch_array($queryEdit);
  }
  ?>
  <form method="POST" action="../src/php/filial/editar.php" class="modal fade" id="editarModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Editar Empresa/Filial</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-1">
            <label for="nome" class="form-label">Identificador:</label>
            <input type="text" class="form-control" value="<?php echo $dadosEdit['fil_id']; ?>" disabled>
            <input type="hidden" name="id" value="<?php echo $dadosEdit['fil_id']; ?>">
          </div>
          <div class="mb-1">
            <label for="nome" class="form-label">Nome:</label>
            <input type="text" class="form-control" name="nome" id="nome" value="<?php echo $dadosEdit['fil_nome']; ?>" required>
          </div>
          <div class="mb-1">
            <label for="cnpj" class="form-label">CNPJ:</label>
            <input type="text" class="form-control" name="cnpj" id="cnpj" value="<?php echo $dadosEdit['fil_cnpj']; ?>" required>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
          <button type="submit" class="btn btn-primary">Criar</button>
        </div>
      </div>
    </div>
  </form>
  <!-- end: modal update filial -->

  <script>
    document.getElementById('cnpj').addEventListener('input', function(e) {
      var value = e.target.value;
      var rawValue = value.replace(/\D/g, ''); // Remove tudo que não é número

      // Verifica se o CNPJ tem 15 dígitos e se o primeiro dígito é '0'
      if (rawValue.length === 15 && rawValue.startsWith('0')) {
        // Verifica se, ao remover o '0', o restante é um CNPJ válido
        var potentialCNPJ = rawValue.substring(1);
        // Atualiza rawValue para o CNPJ sem o '0' inicial
        if (validaCNPJ(potentialCNPJ)) rawValue = potentialCNPJ;
      }

      var cnpjPattern = rawValue
        .replace(/^(\d{2})(\d)/, '$1.$2') // Adiciona ponto após o segundo dígito
        .replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3') // Adiciona ponto após o quinto dígito
        .replace(/\.(\d{3})(\d)/, '.$1/$2') // Adiciona barra após o oitavo dígito
        .replace(/(\d{4})(\d)/, '$1-$2') // Adiciona traço após o décimo segundo dígito
        .replace(/(-\d{2})\d+?$/, '$1'); // Impede a entrada de mais de 14 dígitos
      e.target.value = cnpjPattern;
    });

    function validaCNPJ(cnpj) {
      cnpj = cnpj.replace(/[^\d]+/g, '');
      if (cnpj == '' || cnpj.length != 14 || /^(\d)\1{13}$/.test(cnpj)) return false;

      // Valida DVs
      let tamanho = cnpj.length - 2
      let numeros = cnpj.substring(0, tamanho);
      let digitos = cnpj.substring(tamanho);
      let soma = 0;
      let pos = tamanho - 7;
      for (let i = tamanho; i >= 1; i--) {
        soma += numeros.charAt(tamanho - i) * pos--;
        if (pos < 2) pos = 9;
      }
      let resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
      if (resultado != digitos.charAt(0)) return false;

      tamanho = tamanho + 1;
      numeros = cnpj.substring(0, tamanho);
      soma = 0;
      pos = tamanho - 7;
      for (let i = tamanho; i >= 1; i--) {
        soma += numeros.charAt(tamanho - i) * pos--;
        if (pos < 2) pos = 9;
      }
      resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
      if (resultado != digitos.charAt(1)) return false;
      return true;
    }

    document.getElementById('editarModal').addEventListener('submit', function(e) {
      var cnpj = document.getElementById('cnpj').value;
      if (!validaCNPJ(cnpj)) {
        e.preventDefault(); // Impede o envio do formulário
        alert('CNPJ inválido. Por favor, verifique o número digitado.');
        document.getElementById('cnpj').focus(); // Foca no campo de CNPJ após detectar erro
      }
    });
  </script>

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