<?php
require_once("../connection/connection.php");
require '../../../vendor/autoload.php';

use DateTime;
use onesignal\client\api\DefaultApi;
use onesignal\client\Configuration;
use onesignal\client\model\GetNotificationRequestBody;
use onesignal\client\model\Notification;
use onesignal\client\model\StringMap;
use onesignal\client\model\Player;
use onesignal\client\model\UpdatePlayerTagsRequestBody;
use onesignal\client\model\ExportPlayersRequestBody;
use onesignal\client\model\Segment;
use onesignal\client\model\FilterExpressions;
use PHPUnit\Framework\TestCase;
use GuzzleHttp;

$titulo = $_POST['titulo'];
$datainicial = $_POST['datainicial'];
$datafinal = $_POST['datafinal'];
$horarioinicial = $_POST['horarioinicial'];
$horariofinal = $_POST['horariofinal'];
$responsavel = $_POST['responsavel'];
$tipo = $_POST['tipo'];
$observacao = $_POST['observacao'];
$cliente = $_POST['cliente'];
$escola = $_POST['escola'];
$status = 1; // por padrão a O.S é criada com status em ABERTO.

if (is_null($horarioinicial) || is_null($horariofinal)) {
    $sql = "INSERT INTO os_ordemservico(os_titulo, os_func_id, os_tipo, os_datainicial, os_datafinal, os_observacao, os_status, os_cli_id, os_esc_id)
    VALUES('$titulo','$responsavel','$tipo','$datainicial','$datafinal','$observacao', '$status', '$cliente', '$escola')";
    mysqli_query($connection, $sql);
} else {
    $sql = "INSERT INTO os_ordemservico(os_titulo, os_func_id, os_tipo, os_datainicial, os_datafinal, os_horarioinicial, os_horariofinal, os_observacao, os_status, os_cli_id, os_esc_id)
    VALUES('$titulo','$responsavel','$tipo','$datainicial','$datafinal','$horarioinicial','$horariofinal','$observacao', '$status', '$cliente', '$escola')";
    mysqli_query($connection, $sql);
}

$id = mysqli_insert_id($connection);

$sqlCalendario = "INSERT INTO os_calendario(cal_nome, cal_data, cal_os_id) VALUES('$titulo', '$datainicial', '$id')";
mysqli_query($connection, $sqlCalendario);

$APP_ID = '16cd94fa-5189-4806-a570-6ab576f148c7';
$APP_KEY_TOKEN = 'MGMwM2ExODYtOTkzMC00ZjA2LTg5ZTctNjliNTYyMTJjZTIw';

$config = Configuration::getDefaultConfiguration()
->setAppKeyToken($APP_KEY_TOKEN);

$apiInstance = new DefaultApi(
    new GuzzleHttp\Client(),
    $config
);
    
function createNotification($enContent): Notification {
    $APP_ID = '16cd94fa-5189-4806-a570-6ab576f148c7';
    $content = new StringMap();
    $content->setEn($enContent);
    
    $notification = new Notification();
    $notification->setAppId($APP_ID);
    $notification->setContents($content);
    $notification->setIncludedSegments(['All']);

    return $notification;
}

$texto = $titulo . ' (' . date('d/m/Y', strtotime($datainicial));

if ($horarioinicial != '') {
    $texto .= ' - ' . date('H:i', strtotime($horarioinicial)) . ') ';
} else {
    $texto .= ') ';
}
    
$notification = createNotification("$texto");
$dt = new DateTime();
$dt->modify("$datainicial");
$notification->setSendAfter($dt);

$scheduledNotification = $apiInstance->createNotification($notification);

header("Location: ../../../system/os.php?id=$id&nvl=1");
die();
