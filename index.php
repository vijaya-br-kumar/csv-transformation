<?php
require __DIR__.'/src/twig.php';

try
{
    echo $twig->render('index.html.twig', ['messages' => $_GET]);
}
catch (\Exception $exception)
{
    die(sprintf("Error occurred: %s", $exception->getMessage()));
}