<?php
require __DIR__.'/twig.php';
require __DIR__.'/helper.php';

try
{
    $message = "";
    if(($_SERVER['REQUEST_METHOD'] == METHOD_POST) && ($_FILES['upload-file'] ?? false) && (!($_FILES['upload-file']['error'] ?? 1)))
    {
        $file = $_FILES['upload-file'];
        $validExtensions = ["csv", "application/vnd.ms-excel"];
        if((in_array(($file['type'] ?? ""), $validExtensions)) && (in_array((pathinfo($file['name'] ?? "")['extension'] ?? ""), $validExtensions)))
        {
            $inputFile = $_FILES['upload-file'];
            $totalCount = count(file($inputFile['tmp_name']));
            if($totalCount > 1) // 1 header + remaining is data
            {
                $filename = sprintf("%s/%s.csv",ini_get('upload_tmp_dir'),uniqid("file_"));
                file_put_contents($filename, file_get_contents($inputFile['tmp_name']));
                $columns = str_getcsv(file($inputFile['tmp_name'])[0]);
                echo $twig->render('process.html.twig', ['filename' => base64_encode($filename), 'columns' => $columns]);
            }
            else
            {
                $message = ($totalCount > 0) ? "error=Uploaded file contains only header" : "Uploaded file is empty";
                redirect("index.php", false, $message);
            }
        }
        else
        {
            redirect("index.php", false, "error=Input file error");
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