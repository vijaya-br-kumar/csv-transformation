<?php

function yieldCSVData($filePath, $ignoreHeader = false)
{
    $handle = fopen($filePath, "r");
    if ($ignoreHeader) {
        fgetcsv($handle);
    }
    while (($data = fgetcsv($handle)) != FALSE) {
        yield $data;
        unset($data);
    }
    return;
}
function redirect(string $path, bool $src = true, string $message = "")
{
    header(sprintf("Location: %s", sprintf("%s?%s", sitePath($path, $src), $message)));
}

function getTransformedData(array $data)
{
    $headerData = getRefinedRows(str_getcsv(file($data['filename'])[0]), $data['columns']);
    $rowData = [];
    $counter = 1;
    foreach(yieldCSVData($data['filename'], true) as $row)
    {
        if($counter >= $data['rowFrom'] && $counter <= $data['rowTo'])
        {
            $rowData[] = getRefinedRows($row, $data['columns']);
        }
        if($counter > $data['rowTo'])
        {
            break;
        }
        $counter++;
    }
    return ['header' => $headerData, 'row' => $rowData];
}

function getRefinedRows(array $row, array $requiredColumns)
{
    $refinedRows = [];
    foreach ($row as $key => $value)
    {
        if(in_array($key, $requiredColumns))
        {
            $refinedRows[] = $value;
        }
    }
    return $refinedRows;
}