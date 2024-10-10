<?php
require_once("../src/php/connection/connection.php");
$sqlAberta = "SELECT count(os_id) as total FROM os_ordemservico WHERE os_status = 1 and os_ativo = 1";
$queryAberta = mysqli_query($connection, $sqlAberta);
$dadosAberta = mysqli_fetch_array($queryAberta);
$totalAberta = ($dadosAberta['total'] > 0) ? $dadosAberta['total'] : '0';

$sqlValorAberta = "SELECT SUM(ser_valor) as valortotal FROM os_ordemservicoitem LEFT JOIN os_ordemservico ON osi_os_id = os_id LEFT JOIN cad_servico ON osi_ser_id = ser_id WHERE osi_ativo = 1 and os_ativo = 1 and os_status = 1";
$queryValorAberta = mysqli_query($connection, $sqlValorAberta);
$dadosValorAberta = mysqli_fetch_array($queryValorAberta);
$totalValorAberta = ($dadosValorAberta['valortotal'] > 0) ? $dadosValorAberta['valortotal'] : '0';

$sqlFechado = "SELECT count(os_id) as total FROM os_ordemservico WHERE os_status = 3 and os_ativo = 1";
$queryFechado = mysqli_query($connection, $sqlFechado);
$dadosFechado = mysqli_fetch_array($queryFechado);
$totalFechado = ($dadosFechado['total'] > 0) ? $dadosFechado['total'] : '0';

$sqlValorFechado = "SELECT SUM(ser_valor) as valortotal FROM os_ordemservicoitem LEFT JOIN os_ordemservico ON osi_os_id = os_id LEFT JOIN cad_servico ON osi_ser_id = ser_id WHERE osi_ativo = 1 and os_ativo = 1 and os_status = 3";
$queryValorFechado = mysqli_query($connection, $sqlValorFechado);
$dadosValorFechado = mysqli_fetch_array($queryValorFechado);
$totalValorFechado = ($dadosValorFechado['valortotal'] > 0) ? $dadosValorFechado['valortotal'] : '0';

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inicio</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
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

    .card-fechado {
      background-color: green;
      color: #fff;
      padding: 20px;
      margin: 10px;
      margin-left: 0px;
      text-align: center;
      font-family: 'Courier New', Courier, monospace;
      font-size: 25px;
      font-weight: bold;
    }

    .card-fechado p,
    .card-aberto p {
      margin: 0;
      padding: 0;
    }

    .card-aberto {
      background-color: red;
      color: #fff;
      padding: 20px;
      margin: 10px;
      margin-right: 0px;
      text-align: center;
      font-family: 'Courier New', Courier, monospace;
      font-size: 25px;
      font-weight: bold;
    }

    h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
      text-align: center;
    }
  </style>
</head>

<body>
  <?php require_once('menu.php'); ?>

  <h4 class="mt-3">Serviços</h4>

  <div class="row mt-3">
    <div class="col-6">
      <p class="text-center mb-0 fw-bold">Qtde. Abertos</p>
    </div>
    <div class="col-6 ">
      <p class="text-center mb-0 fw-bold">Qtde. Fechados</p>
    </div>
  </div>
  <div class="row">
    <div class="col-6 ">
      <div class="card-aberto shadow-lg">
        <p><?php echo $totalAberta; ?></p>
      </div>
    </div>
    <div class="col-6">
      <div class="card-fechado shadow-lg">
        <p><?php echo $totalFechado; ?></p>
      </div>
    </div>
  </div>

  <div class="row mt-3">
    <div class="col-6 ">
      <p class="text-center mb-0 fw-bold">Vlr. Abertos</p>
    </div>
    <div class="col-6 ">
      <p class="text-center mb-0 fw-bold">Vlr. Fechados</p>
    </div>
  </div>
  <div class="row">
    <div class="col-6 ">
      <div class="card-aberto">
        <p><?php echo number_format($totalValorAberta, 2, ",", "."); ?></p>
      </div>
    </div>
    <div class="col-6">
      <div class="card-fechado">
        <p><?php echo number_format($totalValorFechado, 2, ",", "."); ?></p>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>

</html>