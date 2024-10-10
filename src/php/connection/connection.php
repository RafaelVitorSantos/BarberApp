<?php

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "barber";
// $user = "razons84_admin";
// $pass = "RazonSolucoes@";
// $dbname = "razons84_barber";
$port = 3306;

$connection = mysqli_connect($host, $user, $pass, $dbname);
//$connection = mysqli_connect($host, $user, $pass, $dbname);

try {
    $conn = new PDO("mysql:host=$host;dbname=" . $dbname, $user, $pass);
} catch (PDOException $err) {
    echo "Erro: Conexão com banco de dados não realizado com sucesso. Erro gerado " . $err->getMessage();
}
