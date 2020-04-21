<?php
require __DIR__ . '/../vendor/autoload.php';
require_once ('constants.php');

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

$loader = new FilesystemLoader(TEMPLATE_PATH);
$twig = new Environment($loader);

$twig->addFunction(new \Twig\TwigFunction('site_path', 'sitePath'));
$twig->addFunction(new \Twig\TwigFunction('asset', 'asset'));

function sitePath($path = "", $src = true)
{
    return sprintf("%s%s%s", SITE_PATH, ($src ? "src/" : ""), $path);
}

function asset($path = "")
{
    return sprintf("%sassets/%s", SITE_PATH, $path);
}