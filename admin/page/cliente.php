<?php
require_once('../../src/php/connection/connection.php');
session_start();
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
    .outline-0:focus {
      outline: 0 !important;
      box-shadow: 0 0 0 0 rgba(0, 0, 0, 0) !important;
    }
  </style>
</head>

<body>
  <?php require_once('menu.php'); ?>

  <?php
  $sql = "SELECT * FROM cad_usuario WHERE usu_ativo = 1";
  $query = mysqli_query($connection, $sql);
  ?>

  <h1 class="text-center mb-3" style="margin-top: 96px;">Clientes</h1>

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
          <th scope="row"><?php echo $dados['usu_id']; ?></th>
          <td><?php echo $dados['usu_nome']; ?></td>
          <td><?php echo (!empty($dados['usu_telefone'])) ? $dados['usu_telefone'] : 'N/A'; ?></td>
          <td>
            <button class="btn btn-transparent pt-0 pb-0" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots" viewBox="0 0 16 16">
                <path d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3" />
              </svg>
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
              <li><a class="dropdown-item" href="?edit=<?php echo $dados['usu_id']; ?>">Ver</a></li>
              <li><a class="dropdown-item" onclick="return validaDesativar()" href="../../src/php/admin/cliente/desativar.php?id=<?php echo $dados['usu_id']; ?>">Desativar</a></li>
            </ul>
          </td>
        </tr>
      <?php } ?>
    </tbody>
  </table>

  <!-- modal create cliente -->
  <form method="POST" action="../../src/php/admin/cliente/criar.php" class="modal fade" id="adicionarModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Cadastrar Cliente</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-1">
            <label for="nome" class="form-label">Nome:</label>
            <input type="text" class="form-control" name="nome" id="nome" required>
          </div>
          <div class="mb-1">
            <label for="t+elefone" class="form-label">Telefone: <small>(opcional)</small></label>
            <input type="tel" class="form-control" name="telefone" id="telefone">
          </div>
          <div class="mb-1">
            <label for="email" class="form-label">E-mail: <small>(opcional)</small></label>
            <input type="mail" class="form-control" name="email" id="email">
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

    $sqlEdit = "SELECT * FROM cad_usuario WHERE usu_id = $id LIMIT 1";
    $queryEdit = mysqli_query($connection, $sqlEdit);
    $dadosEdit = mysqli_fetch_array($queryEdit);

    $mensagemPadrao = $dadosConfigAgendamento['config_mensagempadrao'];
    echo "<script> var mensagempadrao = '$mensagemPadrao'; </script>";
  }
  ?>
  <form method="POST" action="../../src/php/admin/cliente/editar.php" class="modal fade" id="editarModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Visualizar Cliente</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-1">
            <label class="form-label">Identificador:</label>
            <input disabled type="text" class="form-control bg-white" value="<?php echo $dadosEdit['usu_id']; ?>" required>
            <input type="hidden" name="id" value="<?php echo $dadosEdit['usu_id']; ?>">
          </div>
          <div class="mb-1">
            <label for="nome" class="form-label">Nome:</label>
            <input disabled type="text" class="form-control bg-white" name="nome" value="<?php echo $dadosEdit['usu_nome']; ?>" id="nome" required>
          </div>
          <div class="mb-1">
            <label for="t+elefone" class="form-label">Telefone:</label>
            <input disabled type="tel" class="form-control bg-white" value="<?php echo $dadosEdit['usu_telefone']; ?>" name="telefone" id="telefone">
          </div>
          <div class="mb-1">
            <label for="email" class="form-label">E-mail:</label>
            <input disabled type="mail" class="form-control bg-white" value="<?php echo $dadosEdit['usu_email']; ?>" name="email" id="email">
          </div>
        </div>
        <div class="modal-footer justify-content-start">
          <?php if ($dadosEdit['usu_telefone'] != '') { ?>
            <label for="mensagemPadrao" class="d-flex gap-2 align-items-center"><input type="checkbox" id="mensagemPadrao" onclick="mensagemPadraoChange()" style="width: 20px; height: 20px;">Mensagem Padrão</label>
          <?php } ?>
          <div class="d-flex justify-content-between align-items-center w-100">
            <?php if ($dadosEdit['usu_telefone'] != '') { ?>
              <a href="https://wa.me/55<?php echo preg_replace('/\D/', '', $dadosEdit['usu_telefone']); ?>?text=" class="btn btn-primary d-flex justify-content-center align-items-center gap-2" id="whatsapplink">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-whatsapp" viewBox="0 0 16 16">
                  <path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232" />
                </svg>
                Chamar Cliente
              </a>
            <?php } ?>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
          </div>
        </div>
      </div>
    </div>
  </form>
  <!-- end: modal update cliente -->


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

    function mensagemPadraoChange() {
      let checkboxButton = document.getElementById('mensagemPadrao');
      let linkmensagem = document.getElementById('whatsapplink');
      let linknovo = linkmensagem.href.split("&text=")[0];
      if (checkboxButton.checked) {
        linkmensagem.href = linknovo + '&text=' + mensagempadrao;
      } else {
        linkmensagem.href = linknovo;
      }
    }
  </script>

  <script>
    function sumirPopUp() {
      let popup = document.getElementById("popup");
      popup.remove();
    }

    const handlePhone = (event) => {
      let input = event.target
      input.value = phoneMask(input.value)
    }

    const phoneMask = (value) => {
      if (!value) return ""
      value = value.replace(/\D/g, '')
      value = value.replace(/(\d{2})(\d)/, "($1) $2")
      value = value.replace(/(\d)(\d{4})$/, "$1-$2")
      return value
    }

    const cpfInput = document.getElementById('cpfcnpj');
    const cpfcnpjedit = document.getElementById('cpfcnpjedit');

    cpfInput.addEventListener('blur', function() {
      const cpf = cpfInput.value.replace(/[^\d]/g, ''); // Remove caracteres não numéricos
      if (validarCPF(cpf)) {
        cpfInput.classList.remove('is-invalid'); // Remove a classe de estilo de erro, se estiver presente
      } else {
        cpfInput.classList.add('is-invalid'); // Adiciona a classe de estilo de erro
      }
    });

    cpfcnpjedit.addEventListener('blur', function() {
      const cpfedit = cpfcnpjedit.value.replace(/[^\d]/g, ''); // Remove caracteres não numéricos
      if (validarCPF(cpfedit)) {
        cpfcnpjedit.classList.remove('is-invalid'); // Remove a classe de estilo de erro, se estiver presente
      } else {
        cpfcnpjedit.classList.add('is-invalid'); // Adiciona a classe de estilo de erro
      }
    });

    function validarCPF(cpf) {
      // Remova todos os caracteres não numéricos do CPF
      cpf = cpf.replace(/\D/g, '');

      if (cpf.length <= 11) {
        // Verifique se o CPF tem 11 dígitos
        if (cpf.length !== 11) {
          return false;
        }

        // Verifique se todos os dígitos são iguais; se forem, o CPF é inválido
        const todosDigitosIguais = /^(\d)\1+$/.test(cpf);
        if (todosDigitosIguais) {
          return false;
        }

        // Calcula o primeiro dígito verificador
        let soma = 0;
        for (let i = 0; i < 9; i++) {
          soma += parseInt(cpf.charAt(i)) * (10 - i);
        }
        let primeiroDigito = 11 - (soma % 11);
        if (primeiroDigito === 10 || primeiroDigito === 11) {
          primeiroDigito = 0;
        }

        // Verifica se o primeiro dígito verificador é igual ao fornecido
        if (primeiroDigito !== parseInt(cpf.charAt(9))) {
          return false;
        }

        // Calcula o segundo dígito verificador
        soma = 0;
        for (let i = 0; i < 10; i++) {
          soma += parseInt(cpf.charAt(i)) * (11 - i);
        }
        let segundoDigito = 11 - (soma % 11);
        if (segundoDigito === 10 || segundoDigito === 11) {
          segundoDigito = 0;
        }

        // Verifica se o segundo dígito verificador é igual ao fornecido
        if (segundoDigito !== parseInt(cpf.charAt(10))) {
          return false;
        }

        return true;
      } else {
        cpf = cpf.replace(/[^\d]+/g, '');

        if (cpf == '') return false;

        if (cpf.length != 14)
          return false;

        // Elimina CNPJs invalidos conhecidos
        if (cpf == "00000000000000" ||
          cpf == "11111111111111" ||
          cpf == "22222222222222" ||
          cpf == "33333333333333" ||
          cpf == "44444444444444" ||
          cpf == "55555555555555" ||
          cpf == "66666666666666" ||
          cpf == "77777777777777" ||
          cpf == "88888888888888" ||
          cpf == "99999999999999")
          return false;

        // Valida DVs
        tamanho = cpf.length - 2
        numeros = cpf.substring(0, tamanho);
        digitos = cpf.substring(tamanho);
        soma = 0;
        pos = tamanho - 7;
        for (i = tamanho; i >= 1; i--) {
          soma += numeros.charAt(tamanho - i) * pos--;
          if (pos < 2)
            pos = 9;
        }
        resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        if (resultado != digitos.charAt(0))
          return false;

        tamanho = tamanho + 1;
        numeros = cpf.substring(0, tamanho);
        soma = 0;
        pos = tamanho - 7;
        for (i = tamanho; i >= 1; i--) {
          soma += numeros.charAt(tamanho - i) * pos--;
          if (pos < 2)
            pos = 9;
        }
        resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        if (resultado != digitos.charAt(1))
          return false;

        return true;
      }
    }
  </script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>

  <script>
    var options = {
      onKeyPress: function(cpf, ev, el, op) {
        var masks = ['000.000.000-000', '00.000.000/0000-00'];
        $('#cpfcnpj').mask((cpf.length > 14) ? masks[1] : masks[0], op);
      }
    }

    $('#cpfcnpj').length > 11 ? $('#cpfcnpj').mask('00.000.000/0000-00', options) : $('#cpfcnpj').mask('000.000.000-00#', options);
  </script>

  <script>
    var options = {
      onKeyPress: function(cpf, ev, el, op) {
        var masks = ['000.000.000-000', '00.000.000/0000-00'];
        $('#cpfcnpjedit').mask((cpf.length > 14) ? masks[1] : masks[0], op);
      }
    }

    $('#cpfcnpjedit').length > 11 ? $('#cpfcnpjedit').mask('00.000.000/0000-00', options) : $('#cpfcnpjedit').mask('000.000.000-00#', options);
  </script>

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