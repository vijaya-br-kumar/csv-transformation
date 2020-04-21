<?php

require __DIR__ . '/twig.php';
require __DIR__ . '/helper.php';

use Spipu\Html2Pdf\Html2Pdf;

try
{
    $message = "";
    if(($_SERVER['REQUEST_METHOD'] == METHOD_POST) && (($_POST['parameters'] ?? "") != "") && ((($_POST['type'] ?? "")) != ""))
    {
        $transformedData = getTransformedData(json_decode(base64_decode($_POST['parameters']), true));
        switch ($_POST['type'])
        {
            case CSV:
                exportCsv($transformedData);
                break;
            case XLS:
                exportXls($transformedData);
                break;
            case PDF:
                exportPdf($transformedData, $twig);
                break;
            default:
                redirect("index.php", false, "error=Invalid Export Type");
                break;
        }
    }
    else
    {
        redirect("index.php", false, "error=Invalid Request Method");
    }
}
catch (Exception $exception)
{
    redirect("index.php", false, "error=Server Error");
}

function exportCsv(array $data)
{
    $filename = "csv_transformation.csv";
    $fp = fopen('php://output', 'w');
    header('Content-type: application/csv');
    header('Content-Disposition: attachment; filename='.$filename);
    fputcsv($fp, $data['header']);
    foreach ($data['row'] as $row)
    {
        fputcsv($fp, $row);
    }
    fclose($fp);
    exit;
}

function exportXls(array $data)
{
    $filename = "csv_transformation.xls";
    $fp = fopen('php://output', 'w');
    header("Content-Type: application/vnd.ms-excel");
    header('Content-Disposition: attachment; filename='.$filename);
    fputcsv($fp, $data['header'], "\t");
    foreach ($data['row'] as $row)
    {
        fputcsv($fp, $row, "\t");
    }
    fclose($fp);
    exit;
}

function exportPdf(array $data, \Twig\Environment $twig)
{
    $data['formData'] = "";
    $html2pdf = new Html2Pdf();
    $html2pdf->writeHTML($twig->render('pdf-export.html.twig', $data));
    $html2pdf->output();
}


