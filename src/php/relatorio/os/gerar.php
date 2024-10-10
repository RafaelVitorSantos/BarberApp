<?php
require_once("../../connection/connection.php");

$os = $_POST['os'];
sort($os);
$empresa = $_POST['empresa'];

if (isset($_POST['autoriza'])) {
    $aprovador = $_POST['aprovador'];

    foreach ($os as $chave) {
        $sqlAdd = "UPDATE os_ordemservico SET os_nomeaprovador='$aprovador' WHERE os_id = $chave";
        mysqli_query($connection, $sqlAdd);
    }
}

$sqlEmpresa = "SELECT * FROM cad_filial WHERE fil_id = $empresa";
$queryEmpresa = mysqli_query($connection, $sqlEmpresa);
$dadosEmpresa = mysqli_fetch_array($queryEmpresa);
$conteudo = '';

$numeroPaginas = count($os);
$contador = 0;
foreach ($os as $valor) {
    $contador++;
    $sqlOS = "SELECT * FROM os_ordemservico LEFT JOIN cad_funcionario ON os_func_id = func_id WHERE os_id = $valor";
    $queryOS = mysqli_query($connection, $sqlOS);
    $dadosOS = mysqli_fetch_array($queryOS);

    $numeroOS = str_pad($dadosOS['os_id'], 5, "0", STR_PAD_LEFT);

    $conteudo .= "<div class='wrapper-page'>";
    $conteudo .= "<div class='div'>
            <h1 style='text-align: center; font-size: 20px; font-weight: normal;'>Ordem de Serviço $numeroOS</h1>
            <h1 style='text-align: center; font-size: 25px;'>$dadosOS[os_titulo]</h1>
            <h2 style='text-align: center; font-size: 20px; font-weight: normal;'>$dadosEmpresa[fil_nome] - $dadosEmpresa[fil_cnpj]</h2>
            <p style='text-align: center; font-size: 20px; font-weight: normal;'>$dadosOS[func_nome]</p>
            <p style='text-align: center; font-size: 15px; font-weight: normal;'>$dadosOS[os_observacao]</p>
        </div>";

    $sqlServico = "SELECT * FROM os_ordemservicoitem LEFT JOIN cad_servico ON osi_ser_id = ser_id LEFT JOIN cad_equipamento ON osi_equ_id = equ_id WHERE osi_os_id = $valor and osi_ativo = 1";
    $queryServico = mysqli_query($connection, $sqlServico);

    $conteudo .= "<div class='div'>
            <h3 style='text-align: center; font-size: 25px;'>Serviços Prestados</h3>
            <table>
                <tr>
                    <th style='text-align: center; width: 33%;'>Nome do Serviço</th>
                    <th style='text-align: center; width: 33%;'>Valor do Serviço</th>
                    <th style='text-align: center; width: 33%;'>Equipamento Vinculado</th>
                </tr>";

    $total = 0;

    while ($dadosServico = mysqli_fetch_array($queryServico)) {
        $equipamento = ($dadosServico['equ_nome'] != '') ? $dadosServico['equ_nome'] : 'N/A';
        $patrimonio = ($dadosServico['equ_numeropatrimonio'] != '') ? "(" . $dadosServico['equ_numeropatrimonio'] . ")" : '';
        $valor = ($dadosServico['osi_valor'] != '') ? number_format($dadosServico['osi_valor'], 2, ",", ".") : 'Incluso';
        $conteudo .= "
                <tr>
                    <td style='text-align: center;'>$dadosServico[ser_nome]</th>
                    <td style='text-align: center;'>$valor</th>
                    <td style='text-align: center;'>$equipamento $patrimonio</th>
                </tr>
        ";
        $total += $dadosServico['osi_valor'];
    }

    $total = number_format($total, 2, ",", ".");

    $conteudo .= "</table></div>";

    $conteudo .= "<div class='div' style='display: flex; justify-content: start;'>
            <table style='width: 50%;'>
                <th style='text-align: end;'>Valor Final:</th>
                <td style='text-align: end;'>$total</td>
            </table>
        </div>";

    if ($contador == $numeroPaginas) {
        $conteudo .= "        <div class='div'>
        <h3 style='text-align: center; font-size: 25px;'>Assinaturas</h3>
    </div>
    <table>
        <tr style='border: 0px'>
            <td style='border: 0px;'>Assinatura da Empresa:</td>
            <td style='border: 0px;'>Assinatura do Aprovador:</td>
        </tr>
        <tr style='border: 0px; background-color: #fff;'>
            <td style='border: 0px; background-color: #fff;'>
                <div class='ass'></div>
            </td>
            <td style='border: 0px; background-color: #fff;'>
                <div class='ass'></div>
            </td>
        </tr>
    </table>";
    }

    $conteudo .= "</div>";
}

$html = "<!DOCTYPE html>
<html lang='pt-br'>

<head>
    <meta charset='UTF-8'>
    <style>
        * {
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            margin-top: 25px;
            margin-bottom: 20px;
            margin-left: 25px;
            margin-right: 25px;
        }

        .div {
            padding-top: 20px;
            padding-bottom: 20px;
            border-top: 1px solid #000;
        }

        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td,
        th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #dddddd;
        }

        .ass {
            background-color: #eee;
            height: 60px;
            border-bottom: 1px dashed #000;
        }

        .wrapper-page {
            page-break-after: always;
        }

        .wrapper-page:last-child {
            page-break-after: avoid;
        }
    </style>
</head>

<body>
$conteudo
</body>

</html>";

require_once("../../../../vendor/autoload.php");
// reference the Dompdf namespace
use Dompdf\Dompdf;

// instantiate and use the dompdf class

$tmp = sys_get_temp_dir();

$dompdf = new Dompdf([
    'logOutputFile' => '',
    // authorize DomPdf to download fonts and other Internet assets
    'isRemoteEnabled' => true,
    // all directories must exist and not end with /
    'fontDir' => $tmp,
    'fontCache' => $tmp,
    'tempDir' => $tmp,
    'chroot' => $tmp,
]);

$dompdf->loadHtml($html); //load an html

$dompdf->render();

$dompdf->stream('relatorio.pdf', [
    'compress' => true,
    'Attachment' => false,
]);

// $dompdf->loadHtmlFile($html);

// (Optional) Setup the paper size and orientation
// $dompdf->setPaper('A4', 'portrait');

// $dompdf->render();

// // Render the HTML as PDF
// header('Content-type: application/pdf');
// echo $dompdf->output();

// Output the generated PDF to Browser
// $dompdf->stream(true,);