<?php
require_once("../../../connection/connection.php");

$domingo = (isset($_POST['domingo'])) ? $_POST['domingo'] : 0;
$segunda = (isset($_POST['segunda'])) ? $_POST['segunda'] : 0;
$terca = (isset($_POST['terca'])) ? $_POST['terca'] : 0;
$quarta = (isset($_POST['quarta'])) ? $_POST['quarta'] : 0;
$quinta = (isset($_POST['quinta'])) ? $_POST['quinta'] : 0;
$sexta = (isset($_POST['sexta'])) ? $_POST['sexta'] : 0;
$sabado = (isset($_POST['sabado'])) ? $_POST['sabado'] : 0;

if ($domingo != 0) {
    $inicio_domingo = $_POST['inicio_domingo'];
    $fim_domingo = $_POST['fim_domingo'];
}

if ($segunda != 0) {
    $inicio_segunda = $_POST['inicio_segunda'];
    $fim_segunda = $_POST['fim_segunda'];
}

if ($terca != 0) {
    $inicio_terca = $_POST['inicio_terca'];
    $fim_terca = $_POST['fim_terca'];
}

if ($quarta != 0) {
    $inicio_quarta = $_POST['inicio_quarta'];
    $fim_quarta = $_POST['fim_quarta'];
}

if ($quinta != 0) {
    $inicio_quinta = $_POST['inicio_quinta'];
    $fim_quinta = $_POST['fim_quinta'];
}

if ($sexta != 0) {
    $inicio_sexta = $_POST['inicio_sexta'];
    $fim_sexta = $_POST['fim_sexta'];
}

if ($sabado != 0) {
    $inicio_sabado = $_POST['inicio_sabado'];
    $fim_sabado = $_POST['fim_sabado'];
}

//domingo
$sqlValidate = "SELECT * FROM age_horariofuncionamento WHERE hrfun_diasemana = 0 and hrfun_ativo = 1 LIMIT 1";
$queryValidate = mysqli_query($connection, $sqlValidate);
$rowValidate = mysqli_num_rows($queryValidate);

if ($rowValidate > 0) {
    if ($domingo != 0) {
        $sqlUpdate = "UPDATE age_horariofuncionamento SET hrfun_horarioinicial='$inicio_domingo', hrfun_horariofinal='$fim_domingo' WHERE hrfun_diasemana = 0 and hrfun_ativo = 1";
        mysqli_query($connection, $sqlUpdate);
    } else {
        $sqlUpdate = "UPDATE age_horariofuncionamento SET hrfun_ativo='0' WHERE hrfun_diasemana = 0 and hrfun_ativo = 1";
        mysqli_query($connection, $sqlUpdate);
    }
} else {
    if ($domingo != 0) {
        $sqlInsert = "INSERT INTO age_horariofuncionamento(hrfun_diasemana, hrfun_horarioinicial, hrfun_horariofinal) VALUES ('0', '$inicio_domingo', '$fim_domingo')";
        mysqli_query($connection, $sqlInsert);
    }
}
//fim:domingo

//segunda
$sqlValidate = "SELECT * FROM age_horariofuncionamento WHERE hrfun_diasemana = 1 and hrfun_ativo = 1 LIMIT 1";
$queryValidate = mysqli_query($connection, $sqlValidate);
$rowValidate = mysqli_num_rows($queryValidate);

if ($rowValidate > 0) {
    if ($segunda != 0) {
        $sqlUpdate = "UPDATE age_horariofuncionamento SET hrfun_horarioinicial='$inicio_segunda', hrfun_horariofinal='$fim_segunda' WHERE hrfun_diasemana = 1 and hrfun_ativo = 1";
        mysqli_query($connection, $sqlUpdate);
    } else {
        $sqlUpdate = "UPDATE age_horariofuncionamento SET hrfun_ativo='0' WHERE hrfun_diasemana = 1 and hrfun_ativo = 1";
        mysqli_query($connection, $sqlUpdate);
    }
} else {
    if ($segunda != 0) {
        $sqlInsert = "INSERT INTO age_horariofuncionamento(hrfun_diasemana, hrfun_horarioinicial, hrfun_horariofinal) VALUES ('1', '$inicio_segunda', '$fim_segunda')";
        mysqli_query($connection, $sqlInsert);
    }
}
//fim:segunda

//terca
$sqlValidate = "SELECT * FROM age_horariofuncionamento WHERE hrfun_diasemana = 2 and hrfun_ativo = 1 LIMIT 1";
$queryValidate = mysqli_query($connection, $sqlValidate);
$rowValidate = mysqli_num_rows($queryValidate);

if ($rowValidate > 0) {
    if ($terca != 0) {
        $sqlUpdate = "UPDATE age_horariofuncionamento SET hrfun_horarioinicial='$inicio_terca', hrfun_horariofinal='$fim_terca' WHERE hrfun_diasemana = 2 and hrfun_ativo = 1";
        mysqli_query($connection, $sqlUpdate);
    } else {
        $sqlUpdate = "UPDATE age_horariofuncionamento SET hrfun_ativo='0' WHERE hrfun_diasemana = 2 and hrfun_ativo = 1";
        mysqli_query($connection, $sqlUpdate);
    }
} else {
    if ($terca != 0) {
        $sqlInsert = "INSERT INTO age_horariofuncionamento(hrfun_diasemana, hrfun_horarioinicial, hrfun_horariofinal) VALUES ('2', '$inicio_terca', '$fim_terca')";
        mysqli_query($connection, $sqlInsert);
    }
}
//fim:terca

//quarta
$sqlValidate = "SELECT * FROM age_horariofuncionamento WHERE hrfun_diasemana = 3 and hrfun_ativo = 1 LIMIT 1";
$queryValidate = mysqli_query($connection, $sqlValidate);
$rowValidate = mysqli_num_rows($queryValidate);

if ($rowValidate > 0) {
    if ($quarta != 0) {
        $sqlUpdate = "UPDATE age_horariofuncionamento SET hrfun_horarioinicial='$inicio_quarta', hrfun_horariofinal='$fim_quarta' WHERE hrfun_diasemana = 3 and hrfun_ativo = 1";
        mysqli_query($connection, $sqlUpdate);
    } else {
        $sqlUpdate = "UPDATE age_horariofuncionamento SET hrfun_ativo='0' WHERE hrfun_diasemana = 3 and hrfun_ativo = 1";
        mysqli_query($connection, $sqlUpdate);
    }
} else {
    if ($quarta != 0) {
        $sqlInsert = "INSERT INTO age_horariofuncionamento(hrfun_diasemana, hrfun_horarioinicial, hrfun_horariofinal) VALUES ('3', '$inicio_quarta', '$fim_quarta')";
        mysqli_query($connection, $sqlInsert);
    }
}
//fim:quarta

//quinta
$sqlValidate = "SELECT * FROM age_horariofuncionamento WHERE hrfun_diasemana = 4 and hrfun_ativo = 1 LIMIT 1";
$queryValidate = mysqli_query($connection, $sqlValidate);
$rowValidate = mysqli_num_rows($queryValidate);

if ($rowValidate > 0) {
    if ($quinta != 0) {
        $sqlUpdate = "UPDATE age_horariofuncionamento SET hrfun_horarioinicial='$inicio_quinta', hrfun_horariofinal='$fim_quinta' WHERE hrfun_diasemana = 4 and hrfun_ativo = 1";
        mysqli_query($connection, $sqlUpdate);
    } else {
        $sqlUpdate = "UPDATE age_horariofuncionamento SET hrfun_ativo='0' WHERE hrfun_diasemana = 4 and hrfun_ativo = 1";
        mysqli_query($connection, $sqlUpdate);
    }
} else {
    if ($quinta != 0) {
        $sqlInsert = "INSERT INTO age_horariofuncionamento(hrfun_diasemana, hrfun_horarioinicial, hrfun_horariofinal) VALUES ('4', '$inicio_quinta', '$fim_quinta')";
        mysqli_query($connection, $sqlInsert);
    }
}
//fim:quinta

//sexta
$sqlValidate = "SELECT * FROM age_horariofuncionamento WHERE hrfun_diasemana = 5 and hrfun_ativo = 1 LIMIT 1";
$queryValidate = mysqli_query($connection, $sqlValidate);
$rowValidate = mysqli_num_rows($queryValidate);

if ($rowValidate > 0) {
    if ($sexta != 0) {
        $sqlUpdate = "UPDATE age_horariofuncionamento SET hrfun_horarioinicial='$inicio_sexta', hrfun_horariofinal='$fim_sexta' WHERE hrfun_diasemana = 5 and hrfun_ativo = 1";
        mysqli_query($connection, $sqlUpdate);
    } else {
        $sqlUpdate = "UPDATE age_horariofuncionamento SET hrfun_ativo='0' WHERE hrfun_diasemana = 5 and hrfun_ativo = 1";
        mysqli_query($connection, $sqlUpdate);
    }
} else {
    if ($sexta != 0) {
        $sqlInsert = "INSERT INTO age_horariofuncionamento(hrfun_diasemana, hrfun_horarioinicial, hrfun_horariofinal) VALUES ('5', '$inicio_sexta', '$fim_sexta')";
        mysqli_query($connection, $sqlInsert);
    }
}
//fim:sexta

//sabado
$sqlValidate = "SELECT * FROM age_horariofuncionamento WHERE hrfun_diasemana = 6 and hrfun_ativo = 1 LIMIT 1";
$queryValidate = mysqli_query($connection, $sqlValidate);
$rowValidate = mysqli_num_rows($queryValidate);

if ($rowValidate > 0) {
    if ($sabado != 0) {
        $sqlUpdate = "UPDATE age_horariofuncionamento SET hrfun_horarioinicial='$inicio_sabado', hrfun_horariofinal='$fim_sabado' WHERE hrfun_diasemana = 6 and hrfun_ativo = 1";
        mysqli_query($connection, $sqlUpdate);
    } else {
        $sqlUpdate = "UPDATE age_horariofuncionamento SET hrfun_ativo='0' WHERE hrfun_diasemana = 6 and hrfun_ativo = 1";
        mysqli_query($connection, $sqlUpdate);
    }
} else {
    if ($sabado != 0) {
        $sqlInsert = "INSERT INTO age_horariofuncionamento(hrfun_diasemana, hrfun_horarioinicial, hrfun_horariofinal) VALUES ('6', '$inicio_sabado', '$fim_sabado')";
        mysqli_query($connection, $sqlInsert);
    }
}
//fim:sabado

header("Location: ../../../../../admin/page/index.php");
die();
