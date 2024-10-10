<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <style>
    body {
      background-color: #333;
    }

    .centered {
      position: absolute;
      left: 50%;
      top: 44%;
      transform: translate(-50%, -50%);
      width: calc(30vh + 20vw) !important;
    }

    .ajuste {
      width: calc(30vh + 20vw) !important;
      margin-bottom: 3vh;
    }

    .shadow-light {
      box-shadow: 0 .5rem 1rem rgba(199, 199, 199, 0.15);
    }

    .bg-light {
      background-color: #f4f4f4 !important;
    }
  </style>
</head>

<body>

  <form method="POST" action="../src/php/client/login/validateLogin.php" style="border-radius: 5px;" class="container shadow-light d-flex flex-column justify-content-center align-items-center centered w-75 bg-light pt-3 pb-3">
    <img src="page/img/logo_dark.png" style="width: 70%;" alt="Logo Brooklyn Barber Shop">
    <hr class="w-75">
    <p class="mb-0"><b>Acesse com a sua conta!</b></p>
    <div class="w-100 d-flex flex-column">
      <?php
      if (isset($_GET['error'])) {
        echo '<p class="text-center text-danger fw-bold mb-1"> Email ou Senha inválido!</p>';
      }

      if (isset($_GET['mail']) && $_GET['mail'] == 0) {
        echo '<p class="text-center text-danger fw-bold mb-1"> Não foi possivel redefinir sua senha!</p>';
      }

      if (isset($_GET['mail']) && $_GET['mail'] == 1) {
        echo '<p class="text-center text-success fw-bold mb-1"> Senha redefinida com sucesso!</p>';
      }
      ?>
      <div class="mb-2">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" name="email" id="email" placeholder="Digite o e-mail">
      </div>
      <label for="senha" class="form-label">Senha</label>
      <div class="input-group mb-2">
        <input type="password" placeholder="Digite sua senha" name="senha" id="senha" class="form-control" aria-describedby="basic-addon2">
        <span onclick="mostrarSenhaLogin()" class="input-group-text bg-light text-dark" id="icon_mostrar_senha_login">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
            <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0" />
            <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7" />
          </svg>
        </span>
        <span onclick="esconderSenhaLogin()" class="input-group-text bg-light text-dark d-none" id="icon_esconder_senha_login">
          <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="currentColor" class="bi bi-eye-slash-fill" viewBox="0 0 16 16">
            <path d="m10.79 12.912-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7 7 0 0 0 2.79-.588M5.21 3.088A7 7 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474z" />
            <path d="M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829zm4.95.708-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829zm3.171 6-12-12 .708-.708 12 12z" />
          </svg>
        </span>
      </div>

      <button type="button" data-bs-toggle="modal" data-bs-target="#exampleModal" class="border-0 mb-3 text-start text-decoration-underline text-primary">Esqueceu sua senha?</button>

      <div class="d-flex justify-content-center">
        <button type="submit" class="btn btn-dark w-100">Entrar</button>
      </div>
    </div>
  </form>

  <div class="container pe-0 ps-0 w-75 fixed-bottom d-flex flex-column justify-content-center ajuste">
    <p class="text-white mb-1 text-center">Não possui uma conta?</p>
    <button type="submit" class="btn btn-primary w-100" style="height: 40px;" data-bs-toggle="modal" data-bs-target="#cadastro">Criar Conta</button>
  </div>

  <!-- Modal -->
  <form method="POST" action="../src/php/client/login/gerarCodigo.php" class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Redefinir Senha</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="emailt" class="form-label">Email</label>
            <input type="email" class="form-control" name="email" id="emailt" required>
          </div>
          <div class="mb-3">
            <label for="novasenha" class="form-label">Nova Senha</label>
            <input type="password" class="form-control" name="senha" id="novasenha" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
          <button type="submit" class="btn btn-primary">Confirmar</button>
        </div>
      </div>
    </div>
  </form>

  <!-- Modal -->
  <form method="POST" action="../src/php/client/login/createLogin.php" class="modal fade" id="cadastro" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Criar Conta</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <label for="nome" class="form-label">Nome:</label>
          <input type="text" name="nome" id="nome" class="form-control" required>
          <label for="email" class="form-label">E-mail:</label>
          <input type="mail" name="email" id="email" class="form-control" required>
          <label for="telefone" class="form-label">Telefone:</label>
          <input type="tel" name="telefone" maxlength="15" minlength="14" onblur="aplicarMascaraTelefone(this.value)" oninput="aplicarMascaraTelefone(this.value)" id="telefone" class="form-control" required>
          <label for="senhacadastro" class="form-label">Senha:</label>
          <div class="input-group mb-3">
            <input type="password" name="senha" id="senhacadastro" class="form-control" aria-describedby="basic-addon2">
            <span onclick="mostrarSenha()" class="input-group-text bg-light text-dark" id="icon_mostrar_senha">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0" />
                <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7" />
              </svg>
            </span>
            <span onclick="esconderSenha()" class="input-group-text bg-light text-dark d-none" id="icon_esconder_senha">
              <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="currentColor" class="bi bi-eye-slash-fill" viewBox="0 0 16 16">
                <path d="m10.79 12.912-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7 7 0 0 0 2.79-.588M5.21 3.088A7 7 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474z" />
                <path d="M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829zm4.95.708-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829zm3.171 6-12-12 .708-.708 12 12z" />
              </svg>
            </span>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Voltar</button>
          <button type="submit" class="btn btn-primary">Criar Conta!</button>
        </div>
      </div>
    </div>
  </form>

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
  <script>
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

    function esconderSenhaLogin() {
      let inputSenha = document.getElementById('senha');
      let iconMostrar = document.getElementById('icon_mostrar_senha_login');
      let iconEsconder = document.getElementById('icon_esconder_senha_login');
      inputSenha.type = 'password';
      iconMostrar.classList.add('d-flex');
      iconMostrar.classList.remove('d-none');
      iconEsconder.classList.remove('d-flex');
      iconEsconder.classList.add('d-none');
    }

    function mostrarSenhaLogin() {
      let inputSenha = document.getElementById('senha');
      let iconMostrar = document.getElementById('icon_mostrar_senha_login');
      let iconEsconder = document.getElementById('icon_esconder_senha_login');
      inputSenha.type = 'text';
      iconMostrar.classList.add('d-none');
      iconMostrar.classList.remove('d-flex');
      iconEsconder.classList.remove('d-none');
      iconEsconder.classList.add('d-flex');
    }

    function aplicarMascaraTelefone(telefone) {
      telefone = telefone.replace(/\D/g, ""); // Remove tudo o que não for dígito
      telefone = telefone.replace(/^(\d{2})(\d)/g, "($1) $2"); // Coloca parênteses em volta dos dois primeiros dígitos
      telefone = telefone.replace(/(\d)(\d{4})$/, "$1-$2"); // Coloca o hífen entre o quinto e o sexto dígitos
      document.getElementById('telefone').value = telefone;
    }
  </script>
</body>

</html>