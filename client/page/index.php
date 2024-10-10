<?php
require_once("../../src/php/connection/connection.php");
session_start();

$semana = array(
  'Sun' => 'Domingo',
  'Mon' => 'Segunda-Feira',
  'Tue' => 'Terca-Feira',
  'Wed' => 'Quarta-Feira',
  'Thu' => 'Quinta-Feira',
  'Fri' => 'Sexta-Feira',
  'Sat' => 'Sábado'
);

$mes_extenso = array(
  'Jan' => 'Janeiro',
  'Feb' => 'Fevereiro',
  'Mar' => 'Marco',
  'Apr' => 'Abril',
  'May' => 'Maio',
  'Jun' => 'Junho',
  'Jul' => 'Julho',
  'Aug' => 'Agosto',
  'Nov' => 'Novembro',
  'Sep' => 'Setembro',
  'Oct' => 'Outubro',
  'Dec' => 'Dezembro'
);

if (isset($_SESSION['barber_client_id'])) {
  $id = $_SESSION['barber_client_id'];

  $sqlValidate = "SELECT usu_id FROM cad_usuario WHERE usu_id = $id";
  $queryValidate = mysqli_query($connection, $sqlValidate);
  $rowValidate = mysqli_num_rows($queryValidate);
  if ($rowValidate == 0) {
    header("Location: ../index.php?error");
    die();
  }
} else {
  header("Location: ../index.php?error");
  die();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inicio</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <style>
    body {
      background: #333 !important;
    }

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

    .card-format {
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 20px;
    }

    .card-format-body {
      background-color: rgba(100, 200, 100, 0.3);
      padding-top: 20px;
      padding-bottom: 20px;
      width: 90%;
      border-radius: 20px;
    }

    .card-format-body-cancelled {
      background-color: rgba(200, 100, 100, 0.3);
      padding-top: 20px;
      padding-bottom: 20px;
      width: 90%;
      border-radius: 20px;
    }

    .scroll-height {
      overflow-y: scroll;
      height: 57vh;
    }
  </style>
</head>

<body class="d-flex justify-content-center align-items-center justify-items-center w-100" style="height: 100vh">
  <div style="max-width: 550px; width: 550px; height: 98vh; box-shadow: 0px 0px 15px rgba(0,0,0, 0.5)" class="bg-white">
    <nav class="navbar navbar-dark pt-3 pb-3 bg-secondary">
      <div class="container-fluid">
        <a class="navbar-brand"><b>Brooklyn Barbearia</b></a>
        <div class="d-flex">
          <div class="btn-group dropstart ">
            <img src="img/user.png" style="width: 28px; height: 32px;" class="dropdown-toggle" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
              <li><button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modalEditar">Editar Informações</button></li>
              <li><a class="dropdown-item" href="../../src/php/client/login/logout.php">Sair</a></li>
            </ul>
          </div>
        </div>
      </div>
    </nav>
    <?php
    $sqlConfig = "SELECT * FROM cad_config WHERE config_id = 1";
    $queryConfig = mysqli_query($connection, $sqlConfig);
    $dadosConfig = mysqli_fetch_array($queryConfig);
    ?>
    <?php if (isset($_GET['inativo']) || $dadosConfig['config_status'] == 0) { ?>
      <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
        <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
          <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
        </symbol>
      </svg>
      <div class="alert alert-danger d-flex align-items-center" role="alert">
        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:">
          <use xlink:href="#exclamation-triangle-fill" />
        </svg>
        <div>
          <small>No momento não é possivel fazer agendamentos!</small>
        </div>
      </div>
    <?php } ?>
    <h3 class="mb-4" style="margin-top: 4vh;">Agendamentos marcados</h3>

    <div class="scroll-height">
      <?php
      $sqlAgendamentos = "SELECT * FROM os_agendamento LEFT JOIN cad_funcionario ON age_func_id = func_id WHERE age_usu_id = $id AND age_status in(1,2) ORDER BY age_status";
      $queryAgendamentos = mysqli_query($connection, $sqlAgendamentos);
      $rowAgendamentos = mysqli_num_rows($queryAgendamentos);
      ?>
      <?php if ($rowAgendamentos > 0) { ?>
        <?php while ($dadosAgendamentos = mysqli_fetch_array($queryAgendamentos)) { ?>

          <?php
          $sqlServicos = "SELECT * FROM os_agendamentoservico LEFT JOIN ser_servico ON ageser_ser_id = ser_id WHERE ageser_age_id = $dadosAgendamentos[age_id]";
          $queryServicos = mysqli_query($connection, $sqlServicos);
          $rowServicos = mysqli_num_rows($queryServicos);
          $servicos = '';
          $valorFinal = 0;
          $i = 1;
          while ($dadosServicos = mysqli_fetch_array($queryServicos)) {
            if ($i < $rowServicos) {
              $servicos .= $dadosServicos['ser_nome'] . ',<br>';
            } else {
              $servicos .= $dadosServicos['ser_nome'];
            }
            $i++;

            $valorFinal += $dadosServicos['ser_valor'];
          }

          $data = date('D', strtotime($dadosAgendamentos['age_data']));
          $dia = date('d', strtotime($dadosAgendamentos['age_data']));
          $ano = date('Y', strtotime($dadosAgendamentos['age_data']));
          $mes = date('M', strtotime($dadosAgendamentos['age_data']));
          ?>

          <div class="row bg-light border border-1 m-0 mb-2">
            <p class="h6 fw-bold text-center mt-3 text-primary"><?php echo $semana["$data"] . ", {$dia} de " . $mes_extenso["$mes"] . " de {$ano}"; ?></p>
            <div class="card-format">
              <?php if ($dadosAgendamentos['age_status'] == 1) { ?>
                <div class="row card-format-body">
                <?php } else if ($dadosAgendamentos['age_status'] == 2) { ?>
                  <div class="row card-format-body-cancelled">
                  <?php } ?>
                  <div class="col-3 d-flex flex-column justify-content-end align-items-end">
                    <svg xmlns="http://www.w3.org/2000/svg" width="42" height="42" fill="currentColor" class="bi bi-clock" viewBox="0 0 16 16">
                      <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71z" />
                      <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0" />
                    </svg>
                    <p class="mb-0 fw-bold h6 text-primary"><?php echo date('H:i', strtotime($dadosAgendamentos['age_horarioinicio'])) ?></p>
                  </div>
                  <?php if ($dadosAgendamentos['age_status'] == 1) { ?>
                    <div class="col-9">
                      <div class="row">
                        <?php if ($dadosAgendamentos['age_status'] == 1) { ?>
                          <div class="col-10">
                          <?php } else if ($dadosAgendamentos['age_status'] == 2) { ?>
                            <div class="col-12">
                            <?php } ?>
                            <p class="mb-0 fw-bold h6"><?php echo $servicos; ?></p>
                            </div>
                            <?php if ($dadosAgendamentos['age_status'] == 1) { ?>
                              <div class="col-2">
                                <div class="dropdown">
                                  <button class="border-0 bg-transparent" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                      <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0" />
                                    </svg>
                                  </button>
                                  <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    <li><a class="dropdown-item" onclick="return validaCancelamento(this)" href="../../src/php/client/agendamento/cancelarAgendamento.php?id=<?php echo $dadosAgendamentos['age_id']; ?>">Cancelar agendamento</a></li>
                                  </ul>
                                </div>
                              </div>
                            <?php } ?>
                          </div>
                          <p class="mb-0 fw-bold">[R$<?php echo number_format($valorFinal, 2, ',', '. ');; ?>]</p>
                          <p class="mb-0 h6">Profissional: <?php echo ($dadosAgendamentos['func_nome'] != '') ? $dadosAgendamentos['func_nome'] : 'N/A'; ?></p>
                        <?php } else if ($dadosAgendamentos['age_status'] == 2) { ?>
                          <div class="col-9 d-flex align-items-center">
                            <p class="mb-0 fw-bold">Agendamento foi cancelado!</p>
                          <?php } ?>
                          </div>
                      </div>
                    </div>
                  </div>
                <?php } ?>
              <?php } else { ?>
                <p class="text-center">Nenhum agendamento marcado!</p>
              <?php } ?>
                </div>
            </div>

            <div data-bs-toggle="modal" data-bs-target="#exampleModal" class="d-flex justify-content-center align-items-center fixed-bottom" style="margin-bottom: 2.1rem!important">
              <button class="btn btn-secondary fw-bold d-flex justify-content-center align-items-center gap-2" style="padding: 20px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                  <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
                </svg>
                Faça seu Agendamento!</button>
            </div>

            <form method="POST" action="../../src/php/client/agendamento/criarAgendamento.php" class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Faça seu agendamento aqui!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <?php
                  $sqlFuncionario = "SELECT * FROM cad_funcionario WHERE func_ativo = 1 AND func_agendamentoativo = 1";
                  $queryFuncionario = mysqli_query($connection, $sqlFuncionario);

                  $sqlServicos = "SELECT * FROM ser_servico WHERE ser_ativo = 1";
                  $queryServicos = mysqli_query($connection, $sqlServicos);
                  ?>
                  <div class="modal-body">
                    <label for="funcionario" class="form-label">Responsável:</label>
                    <select name="funcionario" required id="funcionario" class="form-select">
                      <?php while ($dadosFuncionario = mysqli_fetch_array($queryFuncionario)) { ?>
                        <option value="<?php echo $dadosFuncionario['func_id']; ?>"><?php echo $dadosFuncionario['func_nome']; ?></option>
                      <?php } ?>
                    </select>
                    <label for="data" class="form-label">Data:</label>
                    <input type="date" required name="data" class="form-control" id="data" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                    <label for="servico" class="form-label">Serviço desejado:</label>
                    <select name="servico[]" required id="servico" class="form-select" multiple>
                      <?php while ($dadosServicos = mysqli_fetch_array($queryServicos)) { ?>
                        <option value="<?php echo $dadosServicos['ser_id']; ?>"><?php echo $dadosServicos['ser_nome']; ?></option>
                      <?php } ?>
                    </select>
                    <label for="horario" class="form-label">Horários disponíveis:</label>
                    <div id="horario-container">
                      <select name="horario" required id="horario" class="form-select">
                        <!-- Horários serão carregados via AJAX -->
                      </select>
                    </div>
                  </div>
                  <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="submit" onclick="return validaFinalizar(this)" class="btn btn-success">Finalizar</button>
                  </div>
                </div>
              </div>
            </form>

            <?php
            $sqlUsuario = "SELECT * FROM cad_usuario WHERE usu_id = $id LIMIT 1";
            $queryUsuario = mysqli_query($connection, $sqlUsuario);
            $rowUsuario = mysqli_num_rows($queryUsuario);

            if ($rowUsuario == 0) {
              header("Location: ../index.php?error");
              die();
            }

            $dadosUsuario = mysqli_fetch_array($queryUsuario);
            ?>

            <form method="POST" action="../../src/php/client/login/updateLogin.php" class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Editar Informações</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <input type="hidden" name="id" value="<?php echo $dadosUsuario['usu_id']; ?>">
                    <label for="nome" class="mb-1">Nome:</label>
                    <input type="text" name="nome" id="nome" value="<?php echo $dadosUsuario['usu_nome']; ?>" class="form-control mb-1" required>
                    <label for="email" class="mb-1">E-mail:</label>
                    <input type="mail" name="email" id="email" value="<?php echo $dadosUsuario['usu_email']; ?>" class="form-control mb-1" required>
                    <label for="telefone" class="form-label">Telefone:</label>
                    <input type="tel" name="telefone" maxlength="15" minlength="14" value="<?php echo $dadosUsuario['usu_telefone']; ?>" onblur="aplicarMascaraTelefone(this.value)" oninput="aplicarMascaraTelefone(this.value)" id="telefone" class="form-control" required>
                    <label for="senha" class="mb-1">Senha Atual:</label>
                    <div class="input-group mb-3">
                      <input type="password" name="senha" id="senhacadastro" class="form-control" required>
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="submit" onclick="return validaEdicao(this)" class="btn btn-success">Confirmar</button>
                  </div>
                </div>
              </div>
            </form>

            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script>
              document.addEventListener('DOMContentLoaded', function() {
                // Função para atualizar os horários disponíveis
                function updateHorarios() {
                  var servicoSelect = document.getElementById('servico');
                  var servicoId = Array.from(servicoSelect.selectedOptions).map(option => option.value).join(',');
                  var data = document.getElementById('data').value;
                  var funcionario = document.getElementById('funcionario').value;

                  if (servicoId && data) {
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', '../../src/php/client/agendamento/update_horarios.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onload = function() {
                      if (xhr.status === 200) {
                        document.getElementById('horario').innerHTML = xhr.responseText;
                      }
                    };
                    xhr.send('servico=' + servicoId + '&data=' + encodeURIComponent(data) + '&funcionario=' + funcionario);
                  }
                }


                // Adiciona um ouvinte de evento para a seleção do serviço
                document.getElementById('servico').addEventListener('change', updateHorarios);

                // Adiciona um ouvinte de evento para a seleção da data
                document.getElementById('data').addEventListener('change', updateHorarios);

                // Adiciona um ouvinte de evento para a seleção do responsável
                document.getElementById('funcionario').addEventListener('change', updateHorarios);
              });

              function validaFinalizar(valor) {
                if (confirm('Deseja realmente finalizar seu agendamento?')) {
                  return true;
                } else {
                  return false;
                }
              }

              function validaCancelamento(valor) {
                if (confirm('Deseja realmente cancelar esse agendamento?')) {
                  return true;
                } else {
                  return false;
                }
              }

              function validaEdicao(valor) {
                if (confirm('Deseja realmente editar suas informações?')) {
                  return true;
                } else {
                  return false;
                }
              }

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

              function aplicarMascaraTelefone(telefone) {
                telefone = telefone.replace(/\D/g, ""); // Remove tudo o que não for dígito
                telefone = telefone.replace(/^(\d{2})(\d)/g, "($1) $2"); // Coloca parênteses em volta dos dois primeiros dígitos
                telefone = telefone.replace(/(\d)(\d{4})$/, "$1-$2"); // Coloca o hífen entre o quinto e o sexto dígitos
                document.getElementById('telefone').value = telefone;
              }
            </script>
</body>

</html>