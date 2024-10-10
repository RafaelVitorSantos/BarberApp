<?php
require_once("../../src/php/connection/connection.php");

date_default_timezone_set('America/Sao_Paulo');

if (isset($_SESSION['barber_admin_id'])) {
  $id = $_SESSION['barber_admin_id'];

  $sqlValidate = "SELECT log_id FROM cad_login WHERE log_id = $id";
  $queryValidate = mysqli_query($connection, $sqlValidate);
  $rowValidate = mysqli_num_rows($queryValidate);
  if ($rowValidate == 0) {
    header("Location: ../index.php?error");
    die();
  }

  $sqlEditarUsuario = "SELECT * FROM cad_login WHERE log_id = $id LIMIT 1";
  $queryEditarUsuario = mysqli_query($connection, $sqlEditarUsuario);
  $dadosEditarUsuario = mysqli_fetch_array($queryEditarUsuario);
} else if (isset($_SESSION['barber_func_id'])) {
  $id = $_SESSION['barber_func_id'];

  $sqlValidate = "SELECT func_id FROM cad_funcionario WHERE func_id = $id";
  $queryValidate = mysqli_query($connection, $sqlValidate);
  $rowValidate = mysqli_num_rows($queryValidate);
  if ($rowValidate == 0) {
    header("Location: ../index.php?error");
    die();
  }

  $sqlEditarFuncionario = "SELECT * FROM cad_funcionario WHERE func_id = $id LIMIT 1";
  $queryEditarFuncionario = mysqli_query($connection, $sqlEditarFuncionario);
  $dadosEditarFuncionario = mysqli_fetch_array($queryEditarFuncionario);
} else {
  header("Location: ../index.php?error");
  die();
}
?>
<nav class="navbar navbar-dark pt-3 pb-3 bg-secondary fixed-top m-0 p-0">
  <div class="container-fluid">
    <a class="btn btn-transparent text-white outline-0" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample">
      <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5" />
      </svg>
    </a>
    <div class="d-flex">
      <a class="navbar-brand"><b>Brooklyn Barbearia</b></a>
      <div class="btn-group dropstart ">
        <img src="img/user.png" style="width: 28px; height: 32px;" class="dropdown-toggle" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
          <li><a class="dropdown-item" href="../../src/php/admin/login/logout.php">Sair</a></li>
        </ul>
      </div>
    </div>
  </div>
</nav>

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
      <?php if(isset($_SESSION['barber_admin_id'])) { ?>
      <a href="administrador.php" class="d-flex mb-1 text-decoration-none text-dark">Administrador</a>
        <hr>
        <a href="funcionario.php" class="d-flex mb-1 text-decoration-none text-dark">Funcionario</a>
        <hr>
      <?php } ?>
        <a href="fornecedor.php" class="d-flex mb-1 text-decoration-none text-dark">Fornecedor</a>
        <hr>
        <a href="cliente.php" class="d-flex mb-1 text-decoration-none text-dark">Cliente</a>
        <hr>
      </div>
    </div>
    <a class="d-flex outline-0 mb-2 text-decoration-none btn btn-secondary" data-bs-toggle="collapse" href="#collapseFinan" role="button" aria-expanded="false" aria-controls="collapseExample">
      Financeiro
    </a>
    <div class="collapse pt-1 pb-3" id="collapseFinan">
      <div class="card card-body">
        <a href="frmpagamento.php" class="d-flex mb-1 text-decoration-none text-dark">Formas de pagamento</a>
        <hr>
      </div>
    </div>
    <a class="d-flex outline-0 mb-2 text-decoration-none btn btn-secondary" data-bs-toggle="collapse" href="#collapseServ" role="button" aria-expanded="false" aria-controls="collapseExample">
      Serviços
    </a>
    <div class="collapse pt-1 pb-3" id="collapseServ">
      <div class="card card-body">
        <a href="servico.php" class="d-flex mb-1 text-decoration-none text-dark">Serviços</a>
        <hr>
        <a href="calendario.php" class="d-flex mb-1 text-decoration-none text-dark">Agenda</a>
        <hr>
        <a href="agendamento.php" class="d-flex mb-1 text-decoration-none text-dark">Lista de Agendamentos</a>
        <hr>
      </div>
    </div>
    <a class="d-flex outline-0 mb-2 text-decoration-none btn btn-secondary" data-bs-toggle="collapse" href="#collapseConfig" role="button" aria-expanded="false" aria-controls="collapseExample">
      Configurações
    </a>
    <div class="collapse pt-1 pb-3" id="collapseConfig">
      <div class="card card-body">
        <a class="d-flex mb-1 text-decoration-none text-dark" data-bs-dismiss="offcanvas" data-bs-toggle="modal" data-bs-target="#modalConfigHorario">Horario de funcionamento</a>
        <hr>
        <a class="d-flex mb-1 text-decoration-none text-dark" data-bs-dismiss="offcanvas" data-bs-toggle="modal" data-bs-target="#modalConfigAgendamento">Agendamentos</a>
        <hr>
        <a class="d-flex mb-1 text-decoration-none text-dark" data-bs-dismiss="offcanvas" data-bs-toggle="modal" data-bs-target="#modalConfigInformacoes">Informações</a>
      </div>
    </div>
  </div>
  <div class="offcanva-footer">
    <a href="../../src/php/admin/backup/backup.php" class="d-flex outline-0 align-items-center justify-content-between gap-1 me-3 ms-3 mb-2 text-decoration-none btn btn-secondary">
      Backup <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-database-down" viewBox="0 0 16 16">
        <path d="M12.5 9a3.5 3.5 0 1 1 0 7 3.5 3.5 0 0 1 0-7m.354 5.854 1.5-1.5a.5.5 0 0 0-.708-.708l-.646.647V10.5a.5.5 0 0 0-1 0v2.793l-.646-.647a.5.5 0 0 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0" />
        <path d="M12.096 6.223A5 5 0 0 0 13 5.698V7c0 .289-.213.654-.753 1.007a4.5 4.5 0 0 1 1.753.25V4c0-1.007-.875-1.755-1.904-2.223C11.022 1.289 9.573 1 8 1s-3.022.289-4.096.777C2.875 2.245 2 2.993 2 4v9c0 1.007.875 1.755 1.904 2.223C4.978 15.71 6.427 16 8 16c.536 0 1.058-.034 1.555-.097a4.5 4.5 0 0 1-.813-.927Q8.378 15 8 15c-1.464 0-2.766-.27-3.682-.687C3.356 13.875 3 13.373 3 13v-1.302c.271.202.58.378.904.525C4.978 12.71 6.427 13 8 13h.027a4.6 4.6 0 0 1 0-1H8c-1.464 0-2.766-.27-3.682-.687C3.356 10.875 3 10.373 3 10V8.698c.271.202.58.378.904.525C4.978 9.71 6.427 10 8 10q.393 0 .774-.024a4.5 4.5 0 0 1 1.102-1.132C9.298 8.944 8.666 9 8 9c-1.464 0-2.766-.27-3.682-.687C3.356 7.875 3 7.373 3 7V5.698c.271.202.58.378.904.525C4.978 6.711 6.427 7 8 7s3.022-.289 4.096-.777M3 4c0-.374.356-.875 1.318-1.313C5.234 2.271 6.536 2 8 2s2.766.27 3.682.687C12.644 3.125 13 3.627 13 4c0 .374-.356.875-1.318 1.313C10.766 5.729 9.464 6 8 6s-2.766-.27-3.682-.687C3.356 4.875 3 4.373 3 4" />
      </svg>
    </a>
    <div class="p-3 w-100">
      <a href="../../src/php/admin/login/logout.php" class="btn btn-outline-secondary">Sair</a>
    </div>
  </div>
</div>

<?php
$sqlConfigHorarioDomingo = "SELECT * FROM age_horariofuncionamento WHERE hrfun_diasemana = 0 AND hrfun_ativo = 1 LIMIT 1";
$queryConfigHorarioDomingo = mysqli_query($connection, $sqlConfigHorarioDomingo);
$dadosConfigHorarioDomingo = (mysqli_num_rows($queryConfigHorarioDomingo) > 0) ? mysqli_fetch_array($queryConfigHorarioDomingo) : 0;

$sqlConfigHorarioSegunda = "SELECT * FROM age_horariofuncionamento WHERE hrfun_diasemana = 1 AND hrfun_ativo = 1 LIMIT 1";
$queryConfigHorarioSegunda = mysqli_query($connection, $sqlConfigHorarioSegunda);
$dadosConfigHorarioSegunda = (mysqli_num_rows($queryConfigHorarioSegunda) > 0) ? mysqli_fetch_array($queryConfigHorarioSegunda) : 0;

$sqlConfigHorarioTerca = "SELECT * FROM age_horariofuncionamento WHERE hrfun_diasemana = 2 AND hrfun_ativo = 1 LIMIT 1";
$queryConfigHorarioTerca = mysqli_query($connection, $sqlConfigHorarioTerca);
$dadosConfigHorarioTerca = (mysqli_num_rows($queryConfigHorarioTerca) > 0) ? mysqli_fetch_array($queryConfigHorarioTerca) : 0;

$sqlConfigHorarioQuarta = "SELECT * FROM age_horariofuncionamento WHERE hrfun_diasemana = 3 AND hrfun_ativo = 1 LIMIT 1";
$queryConfigHorarioQuarta = mysqli_query($connection, $sqlConfigHorarioQuarta);
$dadosConfigHorarioQuarta = (mysqli_num_rows($queryConfigHorarioQuarta) > 0) ? mysqli_fetch_array($queryConfigHorarioQuarta) : 0;

$sqlConfigHorarioQuinta = "SELECT * FROM age_horariofuncionamento WHERE hrfun_diasemana = 4 AND hrfun_ativo = 1 LIMIT 1";
$queryConfigHorarioQuinta = mysqli_query($connection, $sqlConfigHorarioQuinta);
$dadosConfigHorarioQuinta = (mysqli_num_rows($queryConfigHorarioQuinta) > 0) ? mysqli_fetch_array($queryConfigHorarioQuinta) : 0;

$sqlConfigHorarioSexta = "SELECT * FROM age_horariofuncionamento WHERE hrfun_diasemana = 5 AND hrfun_ativo = 1 LIMIT 1";
$queryConfigHorarioSexta = mysqli_query($connection, $sqlConfigHorarioSexta);
$dadosConfigHorarioSexta = (mysqli_num_rows($queryConfigHorarioSexta) > 0) ? mysqli_fetch_array($queryConfigHorarioSexta) : 0;

$sqlConfigHorarioSabado = "SELECT * FROM age_horariofuncionamento WHERE hrfun_diasemana = 6 AND hrfun_ativo = 1 LIMIT 1";
$queryConfigHorarioSabado = mysqli_query($connection, $sqlConfigHorarioSabado);
$dadosConfigHorarioSabado = (mysqli_num_rows($queryConfigHorarioSabado) > 0) ? mysqli_fetch_array($queryConfigHorarioSabado) : 0;

?>
<form method="POST" action="../../src/php/admin/config/horariofuncionamento/ajustar.php" class="modal fade" id="modalConfigHorario" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Horario de Funcionamento</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="p-0 m-0">Domingo</p>
        <div class="d-flex justify-content-between align-items-center gap-1">
          <input type="checkbox" style="height: 50px; width: 50px!important;" <?php echo $checked = ($dadosConfigHorarioDomingo == 0) ?: 'checked'; ?> name="domingo" id="domingo">
          <input type="time" class="form-control" name="inicio_domingo" id="inicio_domingo" value="<?php echo ($dadosConfigHorarioDomingo == 0) ?: $dadosConfigHorarioDomingo['hrfun_horarioinicial'] ?>">
          <input type="time" class="form-control" name="fim_domingo" id="fim_domingo" value="<?php echo ($dadosConfigHorarioDomingo == 0) ?: $dadosConfigHorarioDomingo['hrfun_horariofinal'] ?>">
        </div>
        <p class="p-0 m-0">Segunda-feira</p>
        <div class="d-flex justify-content-between align-items-center gap-1">
          <input type="checkbox" style="height: 50px; width: 50px!important;" <?php echo $checked = ($dadosConfigHorarioSegunda == 0) ?: 'checked'; ?> name="segunda" id="segunda">
          <input type="time" class="form-control" name="inicio_segunda" id="inicio_segunda" value="<?php echo ($dadosConfigHorarioSegunda == 0) ?: $dadosConfigHorarioSegunda['hrfun_horarioinicial'] ?>">
          <input type="time" class="form-control" name="fim_segunda" id="fim_segunda" value="<?php echo ($dadosConfigHorarioSegunda == 0) ?: $dadosConfigHorarioSegunda['hrfun_horariofinal'] ?>">
        </div>
        <p class="p-0 m-0">Terça-feira</p>
        <div class="d-flex justify-content-between align-items-center gap-1">
          <input type="checkbox" style="height: 50px; width: 50px!important;" <?php echo $checked = ($dadosConfigHorarioTerca == 0) ?: 'checked'; ?> name="terca" id="terca">
          <input type="time" class="form-control" name="inicio_terca" id="inicio_terca" value="<?php echo ($dadosConfigHorarioTerca == 0) ?: $dadosConfigHorarioTerca['hrfun_horarioinicial'] ?>">
          <input type="time" class="form-control" name="fim_terca" id="fim_terca" value="<?php echo ($dadosConfigHorarioTerca == 0) ?: $dadosConfigHorarioTerca['hrfun_horariofinal'] ?>">
        </div>
        <p class="p-0 m-0">Quarta-feira</p>
        <div class="d-flex justify-content-between align-items-center gap-1">
          <input type="checkbox" style="height: 50px; width: 50px!important;" <?php echo $checked = ($dadosConfigHorarioQuarta == 0) ?: 'checked'; ?> name="quarta" id="quarta">
          <input type="time" class="form-control" name="inicio_quarta" id="inicio_quarta" value="<?php echo ($dadosConfigHorarioQuarta == 0) ?: $dadosConfigHorarioQuarta['hrfun_horarioinicial'] ?>">
          <input type="time" class="form-control" name="fim_quarta" id="fim_quarta" value="<?php echo ($dadosConfigHorarioQuarta == 0) ?: $dadosConfigHorarioQuarta['hrfun_horariofinal'] ?>">
        </div>
        <p class="p-0 m-0">Quinta-feira</p>
        <div class="d-flex justify-content-between align-items-center gap-1">
          <input type="checkbox" style="height: 50px; width: 50px!important;" <?php echo $checked = ($dadosConfigHorarioQuinta == 0) ?: 'checked'; ?> name="quinta" id="quinta">
          <input type="time" class="form-control" name="inicio_quinta" id="inicio_quinta" value="<?php echo ($dadosConfigHorarioQuinta == 0) ?: $dadosConfigHorarioQuinta['hrfun_horarioinicial'] ?>">
          <input type="time" class="form-control" name="fim_quinta" id="fim_quinta" value="<?php echo ($dadosConfigHorarioQuinta == 0) ?: $dadosConfigHorarioQuinta['hrfun_horariofinal'] ?>">
        </div>
        <p class="p-0 m-0">Sexta-feira</p>
        <div class="d-flex justify-content-between align-items-center gap-1">
          <input type="checkbox" style="height: 50px; width: 50px!important;" <?php echo $checked = ($dadosConfigHorarioSexta == 0) ?: 'checked'; ?> name="sexta" id="sexta">
          <input type="time" class="form-control" name="inicio_sexta" id="inicio_sexta" value="<?php echo ($dadosConfigHorarioSexta == 0) ?: $dadosConfigHorarioSexta['hrfun_horarioinicial'] ?>">
          <input type="time" class="form-control" name="fim_sexta" id="fim_sexta" value="<?php echo ($dadosConfigHorarioSexta == 0) ?: $dadosConfigHorarioSexta['hrfun_horariofinal'] ?>">
        </div>
        <p class="p-0 m-0">Sábado</p>
        <div class="d-flex justify-content-between align-items-center gap-1">
          <input type="checkbox" style="height: 50px; width: 50px!important;" <?php echo $checked = ($dadosConfigHorarioSabado == 0) ?: 'checked'; ?> name="sabado" id="sabado">
          <input type="time" class="form-control" name="inicio_sabado" id="inicio_sabado" value="<?php echo ($dadosConfigHorarioSabado == 0) ?: $dadosConfigHorarioSabado['hrfun_horarioinicial'] ?>">
          <input type="time" class="form-control" name="fim_sabado" id="fim_sabado" value="<?php echo ($dadosConfigHorarioSabado == 0) ?: $dadosConfigHorarioSabado['hrfun_horariofinal'] ?>">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
        <button type="submit" class="btn btn-primary" onclick="return validateHorarios()">Salvar</button>
      </div>
    </div>
  </div>
</form>

<?php
$sqlConfigAgendamento = "SELECT * FROM cad_config WHERE config_id = 1";
$queryConfigAgendamento = mysqli_query($connection, $sqlConfigAgendamento);
$dadosConfigAgendamento = mysqli_fetch_array($queryConfigAgendamento);
?>
<form method="POST" action="../../src/php/admin/config/agendamento/ajustar.php" class="modal fade" id="modalConfigAgendamento" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Opções de Agendamento</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="mb-0">Intervalo dos agendamentos <small>(padrão: 00:15):</small></p>
        <input type="time" name="intervalo" id="intervalo" min="00:10" max="01:00" value="<?php echo $dadosConfigAgendamento['config_intervalo']; ?>" class="form-control mb-2">
        <p class="mb-0">Status dos Agendamentos:</p>
        <select name="status" class="form-select mb-2" id="status">
          <option value="1" <?php echo ($dadosConfigAgendamento['config_status'] != 1) ?: 'SELECTED'; ?>>Ativo, será possivel fazer agendamentos</option>
          <option value="0" <?php echo ($dadosConfigAgendamento['config_status'] != 0) ?: 'SELECTED'; ?>>Desativado, não será possivel fazer agendamentos</option>
        </select>
        <p class="mb-0">Mensagem Padrão enviadas aos clientes:</p>
        <textarea name="mensagempadrao" id="mensagempadrao" class="form-control mb-2"><?php echo $dadosConfigAgendamento['config_mensagempadrao']; ?></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
        <button type="submit" class="btn btn-primary" onclick="return validateHorarios()">Salvar</button>
      </div>
    </div>
  </div>
</form>

<script>
  function validateHorarios() {
    let domingo = document.getElementById('domingo');
    let segunda = document.getElementById('segunda');
    let terca = document.getElementById('terca');
    let quarta = document.getElementById('quarta');
    let quinta = document.getElementById('quinta');
    let sexta = document.getElementById('sexta');
    let sabado = document.getElementById('sabado');

    if (domingo.checked) {
      let inicio_domingo = document.getElementById('inicio_domingo');
      let fim_domingo = document.getElementById('fim_domingo');

      if (inicio_domingo.value == '' || fim_domingo.value == '') {
        alert('Insira o horario de inicio e fim do Domingo')
        return false;
      }
    }

    if (segunda.checked) {
      let inicio_segunda = document.getElementById('inicio_segunda');
      let fim_segunda = document.getElementById('fim_segunda');

      if (inicio_segunda.value == '' || fim_segunda.value == '') {
        alert('Insira o horario de inicio e fim da Segunda-feira')
        return false;
      }
    }

    if (terca.checked) {
      let inicio_terca = document.getElementById('inicio_terca');
      let fim_terca = document.getElementById('fim_terca');

      if (inicio_terca.value == '' || fim_terca.value == '') {
        alert('Insira o horario de inicio e fim da Terça-feira')
        return false;
      }
    }

    if (quarta.checked) {
      let inicio_quarta = document.getElementById('inicio_quarta');
      let fim_quarta = document.getElementById('fim_quarta');

      if (inicio_quarta.value == '' || fim_quarta.value == '') {
        alert('Insira o horario de inicio e fim da Quarta-feira')
        return false;
      }
    }

    if (quinta.checked) {
      let inicio_quinta = document.getElementById('inicio_quinta');
      let fim_quinta = document.getElementById('fim_quinta');

      if (inicio_quinta.value == '' || fim_quinta.value == '') {
        alert('Insira o horario de inicio e fim da Quinta-feira')
        return false;
      }
    }

    if (sexta.checked) {
      let inicio_sexta = document.getElementById('inicio_sexta');
      let fim_sexta = document.getElementById('fim_sexta');

      if (inicio_sexta.value == '' || fim_sexta.value == '') {
        alert('Insira o horario de inicio e fim da Sexta-feira')
        return false;
      }
    }

    if (sabado.checked) {
      let inicio_sabado = document.getElementById('inicio_sabado');
      let fim_sabado = document.getElementById('fim_sabado');

      if (inicio_sabado.value == '' || fim_sabado.value == '') {
        alert('Insira o horario de inicio e fim do Sábado')
        return false;
      }
    }

    return true;
  }
</script>