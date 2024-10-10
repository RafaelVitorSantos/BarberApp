<?php
require_once('../../src/php/connection/connection.php');
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Administradores</title>
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
  $id = $_SESSION['barber_admin_id'];

  $sql = "SELECT log_id, log_nome, log_cpf, log_email FROM cad_login WHERE log_ativo = 1 AND log_id not in('$id')";
  $query = mysqli_query($connection, $sql);
  ?>

  <h1 class="text-center mb-3" style="margin-top: 96px;">Administradores</h1>

  <div class="d-flex justify-content-end gap-3 p-2">
    <input type="text" class="form-control">
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#adicionarModal">Adicionar</button>
  </div>

  <table class="table table-striped">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Nome</th>
        <th scope="col">CPF</th>
        <th scope="col"></th>
      </tr>
    </thead>
    <tbody>
      <?php while ($dados = mysqli_fetch_array($query)) { ?>
        <tr>
          <th scope="row"><?php echo $dados['log_id']; ?></th>
          <td><?php echo $dados['log_nome']; ?></td>
          <td><?php echo $dados['log_cpf']; ?></td>
          <td>
            <button class="btn btn-transparent pt-0 pb-0" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots" viewBox="0 0 16 16">
                <path d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3" />
              </svg>
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
              <li><a class="dropdown-item" href="?edit=<?php echo $dados['log_id']; ?>">Editar</a></li>
              <li><a class="dropdown-item" onclick="return validaDesativar()" href="../../src/php/admin/administrador/desativar.php?id=<?php echo $dados['log_id']; ?>">Desativar</a></li>
            </ul>
          </td>
        </tr>
      <?php } ?>
    </tbody>
  </table>

  <!-- modal create administrador -->
  <form method="POST" action="../../src/php/admin/administrador/criar.php" class="modal fade" id="adicionarModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Criar Administrador</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-1">
            <label for="nome" class="form-label">Nome:</label>
            <input type="text" class="form-control" name="nome" id="nome" required>
          </div>
          <div class="mb-1">
            <label for="email" class="form-label">Email:</label>
            <input type="email" class="form-control" name="email" id="email" required>
          </div>
          <div class="mb-1">
            <label for="cpft" class="form-label">CPF:</label>
            <input type="text" class="form-control" name="cpf" id="cpft" required>
          </div>
          <div class="mb-1">
            <label for="senha" class="form-label d-flex justify-content-between">
              <div>Senha: <small>(4 dígitos)</small></div> <span onclick="mostrarSenha()" class="text-decoration-underline">mostrar</span>
            </label>
            <input type="password" minlength="4" maxlength="4" class="form-control" name="senha" id="senha" required>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
          <button type="submit" class="btn btn-primary">Criar</button>
        </div>
      </div>
    </div>
  </form>
  <!-- end: modal create administrador -->

  <script>
    document.getElementById('cpft').addEventListener('input', function(e) {
      var value = e.target.value;
      var cpfPattern = value.replace(/\D/g, '') // Remove qualquer coisa que não seja número
        .replace(/(\d{3})(\d)/, '$1.$2') // Adiciona ponto após o terceiro dígito
        .replace(/(\d{3})(\d)/, '$1.$2') // Adiciona ponto após o sexto dígito
        .replace(/(\d{3})(\d)/, '$1-$2') // Adiciona traço após o nono dígito
        .replace(/(-\d{2})\d+?$/, '$1'); // Impede entrada de mais de 11 dígitos
      e.target.value = cpfPattern;
    });

    function validaCPF(cpf) {
      cpf = cpf.replace(/\D+/g, '');
      if (cpf.length !== 11) return false;

      let soma = 0;
      let resto;
      if (/^(\d)\1{10}$/.test(cpf)) return false; // Verifica sequências iguais

      for (let i = 1; i <= 9; i++) soma += parseInt(cpf.substring(i - 1, i)) * (11 - i);
      resto = (soma * 10) % 11;
      if ((resto === 10) || (resto === 11)) resto = 0;
      if (resto !== parseInt(cpf.substring(9, 10))) return false;

      soma = 0;
      for (let i = 1; i <= 10; i++) soma += parseInt(cpf.substring(i - 1, i)) * (12 - i);
      resto = (soma * 10) % 11;
      if ((resto === 10) || (resto === 11)) resto = 0;
      if (resto !== parseInt(cpf.substring(10, 11))) return false;

      return true;
    }

    document.getElementById('adicionarModal').addEventListener('submit', function(e) {
      var cpf = document.getElementById('cpft').value;
      if (!validaCPF(cpf)) {
        e.preventDefault(); // Impede o envio do formulário
        alert('CPF inválido. Verifique o número digitado.');
        document.getElementById('cpft').focus(); // Foca no campo de CPF após o erro
      }
    });
  </script>

  <!-- modal update administrador -->

  <?php
  if (isset($_GET['edit'])) {
    $id = $_GET['edit'];

    $sqlEdit = "SELECT * FROM cad_login WHERE log_id = $id LIMIT 1";
    $queryEdit = mysqli_query($connection, $sqlEdit);
    $dadosEdit = mysqli_fetch_array($queryEdit);
  }
  ?>
  <form method="POST" action="../../src/php/admin/administrador/editar.php" class="modal fade" id="editarModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Editar Administrador</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-1">
            <label class="form-label">Identificador:</label>
            <input type="text" disabled class="form-control" value="<?php echo $dadosEdit['log_id']; ?>" required>
            <input type="hidden" name="id" value="<?php echo $dadosEdit['log_id']; ?>">
          </div>
          <div class="mb-1">
            <label for="nome" class="form-label">Nome:</label>
            <input type="text" class="form-control" value="<?php echo $dadosEdit['log_nome']; ?>" name="nome" id="nome" required>
          </div>
          <div class="mb-1">
            <label for="email" class="form-label">Email:</label>
            <input type="email" class="form-control" name="email" value="<?php echo $dadosEdit['log_email']; ?>" id="email" required>
          </div>
          <div class="mb-1">
            <label for="cpf" class="form-label">CPF:</label>
            <input type="text" class="form-control" name="cpf" value="<?php echo $dadosEdit['log_cpf']; ?>" id="cpf" required>
          </div>
          <div class="mb-1">
            <label for="senhaedit" class="form-label d-flex justify-content-between">Senha: <span onclick="mostrarSenhaEdit()" class="text-decoration-underline">mostrar</span></label>
            <input type="password" class="form-control" name="senha" id="senhaedit" required>
          </div>
          <div class="mb-1">
            <label for="senhanova" class="form-label d-flex justify-content-between">Nova Senha: <span onclick="mostrarSenhaNova()" class="text-decoration-underline">mostrar</span></label>
            <input type="password" class="form-control" name="senhanova" id="senhanova">
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
          <button type="submit" class="btn btn-primary">Editar</button>
        </div>
      </div>
    </div>
  </form>
  <!-- end: modal update administrador -->

  <script>
    document.getElementById('cpf').addEventListener('input', function(e) {
      var value = e.target.value;
      var cpfPattern = value.replace(/\D/g, '') // Remove qualquer coisa que não seja número
        .replace(/(\d{3})(\d)/, '$1.$2') // Adiciona ponto após o terceiro dígito
        .replace(/(\d{3})(\d)/, '$1.$2') // Adiciona ponto após o sexto dígito
        .replace(/(\d{3})(\d)/, '$1-$2') // Adiciona traço após o nono dígito
        .replace(/(-\d{2})\d+?$/, '$1'); // Impede entrada de mais de 11 dígitos
      e.target.value = cpfPattern;
    });

    function validaCPF(cpf) {
      cpf = cpf.replace(/\D+/g, '');
      if (cpf.length !== 11) return false;

      let soma = 0;
      let resto;
      if (/^(\d)\1{10}$/.test(cpf)) return false; // Verifica sequências iguais

      for (let i = 1; i <= 9; i++) soma += parseInt(cpf.substring(i - 1, i)) * (11 - i);
      resto = (soma * 10) % 11;
      if ((resto === 10) || (resto === 11)) resto = 0;
      if (resto !== parseInt(cpf.substring(9, 10))) return false;

      soma = 0;
      for (let i = 1; i <= 10; i++) soma += parseInt(cpf.substring(i - 1, i)) * (12 - i);
      resto = (soma * 10) % 11;
      if ((resto === 10) || (resto === 11)) resto = 0;
      if (resto !== parseInt(cpf.substring(10, 11))) return false;

      return true;
    }

    document.getElementById('editarModal').addEventListener('submit', function(e) {
      var cpf = document.getElementById('cpf').value;
      if (!validaCPF(cpf)) {
        e.preventDefault(); // Impede o envio do formulário
        alert('CPF inválido. Verifique o número digitado.');
        document.getElementById('cpf').focus(); // Foca no campo de CPF após o erro
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

    function mostrarSenhaEdit() {
      let senhaInput = document.getElementById('senhaedit');

      if (senhaInput.type == 'password') {
        senhaInput.type = 'text';
      } else {
        senhaInput.type = 'password';
      }
    }

    function mostrarSenhaNova() {
      let senhaInputNova = document.getElementById('senhanova');

      if (senhaInputNova.type == 'password') {
        senhaInputNova.type = 'text';
      } else {
        senhaInputNova.type = 'password';
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