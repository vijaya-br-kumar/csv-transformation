<?php

require __DIR__ . '/twig.php';
require __DIR__ . '/helper.php';

try
{
//    echo $twig->render('pdf-export.html.twig',['header' => ['a','b'], 'row' => [['c','d'],['e','f']]]);die;
    if(($_SERVER['REQUEST_METHOD'] == METHOD_POST))
    {
        $validate = validateInput($_POST);
        if($validate['success'])
        {
            $data = getTransformedData($validate['data']);
            $data['formData'] = base64_encode(json_encode($validate['data']));
            echo $twig->render('render.html.twig', $data);
        }
        else
        {
            if($validate['errorType'] == FILE_ERROR)
            {
                $message = sprintf("error=%s", $validate['message']);
                redirect("index.php", false, $message);
            }
            else
            {
                $messages = ['error' => $validate['message']];
                $columns = str_getcsv(file(base64_decode($_POST['filename']))[0]);
                echo $twig->render('process.html.twig', ['filename' => $_POST['filename'], 'columns' => $columns, 'messages' => $messages]);
            }

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

function validateInput(array $requestData)
{
    $result = ['success' => false, 'data' => [], 'errorType' => null, 'message' => ""];
    $filename = base64_decode($requestData['filename'] ?? "");
    if(!file_exists($filename))
    {
       $result['message'] = "Invalid Request File not found";
       $result['errorType'] = FILE_ERROR;
       return $result;
    }
    $rowFrom = (int)$requestData['rowFrom'] ?? 0;
    $rowTo = (int)$requestData['rowTo'] ?? 0;
    $rowCompare = $rowFrom <=> $rowTo;
    $totalRowCount = count(file($filename)) - 1;


    if($rowFrom <= 0 || $rowTo <= 0 || $rowCompare > 0 || $rowFrom > $totalRowCount || $rowTo > $totalRowCount)
    {
        $result['message'] = "Row start and end value is not valid";
        $result['errorType'] = ROW_ERROR;
        return $result;
    }
    $columns = $requestData['columns'] ?? null;
    if(is_null($columns))
    {
        $result['message'] = "Please select at least one column";
        $result['errorType'] = COLUMN_ERROR;
        return $result;
    }
    $result['data']['filename'] = $filename;
    $result['data']['rowFrom'] = $rowFrom;
    $result['data']['rowTo'] = $rowTo;
    $result['data']['columns'] = explode(",", str_replace('column-',"",$columns));
    $result['success'] = true;
    return  $result;
}