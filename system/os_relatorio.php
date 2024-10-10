<?php
require_once('../src/php/connection/connection.php');

$sqlOS = "SELECT * FROM os_ordemservico LEFT JOIN cad_escola ON os_esc_id = esc_id WHERE os_ativo = 1";
$queryOS = mysqli_query($connection, $sqlOS);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Relatório - OS</title>
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

  <h1 class="text-center mt-3 mb-3"><span class="fs-5">Relatório</span><br><b>Ordem de Serviço</b></h1>

  <hr>

  <?php
  $sqlEmpresa = "SELECT * FROM cad_filial WHERE fil_ativo = 1";
  $queryEmpresa = mysqli_query($connection, $sqlEmpresa);
  ?>
  <form method="POST" class="modal-body d-flex flex-column" action="../src/php/relatorio/os/gerar.php">
    <label for="empresa" class="form-label">Empresa/Filial</label>
    <select name="empresa" id="empresa" required class="form-select mb-2">
      <?php while ($dadosEmpresa = mysqli_fetch_array($queryEmpresa)) { ?>
        <option value="<?php echo $dadosEmpresa['fil_id']; ?>"><?php echo $dadosEmpresa['fil_id'] . ' - ' . $dadosEmpresa['fil_nome'] ?></option>
      <?php } ?>
    </select>
    <label for="os" class="form-label">Selecione as O.S:</label>
    <select name="os[]" class="form-select mb-3" required multiple id="os">
      <?php while ($dadosOS = mysqli_fetch_array($queryOS)) { ?>
        <?php if ($dadosOS['esc_id'] == 0 || is_null($dadosOS['esc_id'])) { ?>
          <option value="<?php echo $dadosOS['os_id']; ?>"><?php echo $dadosOS['os_id'] . ' - ' . $dadosOS['os_titulo']; ?></option>
        <?php } else { ?>
          <option value="<?php echo $dadosOS['os_id']; ?>"><?php echo $dadosOS['os_id'] . ' - ' . $dadosOS['os_titulo'] . " (" . $dadosOS['esc_nome'] . ")"; ?></option>
        <?php } ?>
      <?php } ?>
    </select>
    <label for="autoriza" class="mb-2"><input type="checkbox" onchange="mostraAprovador()" name="autoriza" id="autoriza"> Informar o aprovador na ordem de serviço?</label>
    <div id="div_aprovador" class="mb-3 d-none">
      <label for="aprovador" class="form-label">Nome Aprovador: </label>
      <input type="text" name="aprovador" class="form-control" id="aprovador">
    </div>
    <button type="submit" class="btn btn-primary" style="max-width: 45%; min-width: 110px;">Nova Impressão</button>
  </form>

  <script>
    function mostraAprovador() {
      let checkbox = document.getElementById('autoriza');

      if (checkbox.checked) {
        let div = document.getElementById('div_aprovador');
        let aprovador = document.getElementById('aprovador');

        aprovador.setAttribute('required', true);
        div.classList.remove('d-none');
      } else {
        let div = document.getElementById('div_aprovador');
        let aprovador = document.getElementById('aprovador');

        aprovador.removeAttribute('required');
        div.classList.add('d-none');
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