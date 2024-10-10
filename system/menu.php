<?php
// if (!isset($_SESSION['id']) || $_SESSION['id'] <= 0) {
//   echo "<script>window.location.href='../'</script>";
// }
?>

  <script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" defer></script>
<script>
  window.OneSignalDeferred = window.OneSignalDeferred || [];
  OneSignalDeferred.push(async function(OneSignal) {
    await OneSignal.init({
      appId: "16cd94fa-5189-4806-a570-6ab576f148c7",
      safari_web_id: "web.onesignal.auto.57017041-c410-4b69-86f6-455278402f0c",
      notifyButton: {
        enable: true,
      },
    });
  });
</script>

<div class="fixed-top">
  <a class="btn btn-transparent outline-0" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample">
    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
      <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5" />
    </svg>
  </a>
</div>

<div class="offcanvas offcanvas-start w-75" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title d-flex align-items-center justify-content-center" id="offcanvasExampleLabel">Menu <a href="index.php" style="font-size: 15px;" class="text-primary ms-2 text-decoration-underline">Inicio</a></h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <a class="d-flex outline-0 mb-2 text-decoration-none btn btn-secondary" data-bs-toggle="collapse" href="#collapseCad" role="button" aria-expanded="false" aria-controls="collapseExample">
      Cadastros
    </a>
    <div class="collapse pt-1 pb-3" id="collapseCad">
      <div class="card card-body">
        <a href="filial.php" class="d-flex mb-1 text-decoration-none text-dark">Empresa/Filial</a>
        <hr>
        <a href="funcionario.php" class="d-flex mb-1 text-decoration-none text-dark">Funcionarios</a>
        <hr>
        <!-- <a href="cidade.php" class="d-flex mb-1 text-decoration-none text-dark">Cidades</a>
        <hr> -->
        <a href="cliente.php" class="d-flex mb-1 text-decoration-none text-dark">Cliente</a>
        <hr>
        <!-- <a href="escola.php" class="d-flex mb-1 text-decoration-none text-dark">Escolas</a>
        <hr> -->
        <!-- <a href="equipamento.php" class="d-flex mb-1 text-decoration-none text-dark">Equipamentos</a>
        <hr> -->
        <a href="servico.php" class="d-flex mb-1 text-decoration-none text-dark">Serviços</a>
      </div>
    </div>

    <a class="d-flex outline-0 mb-2 text-decoration-none btn btn-secondary" data-bs-toggle="collapse" href="#collapseOS" role="button" aria-expanded="false" aria-controls="collapseExample">
      Serviços
    </a>
    <div class="collapse pt-1 pb-3" id="collapseOS">
      <div class="card card-body">
        <a href="calendario.php" class="d-flex mb-1 text-decoration-none text-dark">Agenda</a>
        <hr>
        <a href="os.php" class="d-flex mb-1 text-decoration-none text-dark">Ordem de Serviço (O.S)</a>
      </div>
    </div>

    <a class="d-flex outline-0 mb-2 text-decoration-none btn btn-secondary" data-bs-toggle="collapse" href="#collapseRelatorio" role="button" aria-expanded="false" aria-controls="collapseExample">
      Relatórios
    </a>
    <div class="collapse pt-1 pb-3" id="collapseRelatorio">
      <div class="card card-body">
        <a href="os_relatorio.php" class="d-flex mb-1 text-decoration-none text-dark">Ordem de Serviço (O.S)</a>
      </div>
    </div>
  </div>
  <div class="offcanva-footer">
    <div class="p-3 w-100">
      <a href="../index.php" class="btn btn-outline-secondary">Sair</a>
    </div>
  </div>
</div>