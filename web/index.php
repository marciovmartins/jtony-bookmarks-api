<?php

// set the error handling
ini_set('display_errors', 1);
error_reporting(-1);

//DEFAULT PHP CONFIGS
setlocale(LC_ALL, 'pt_BR.UTF-8', 'pt_BR', 'portuguese');
date_default_timezone_set( "America/Sao_Paulo" );

$loader = require_once __DIR__.'/../vendor/autoload.php';

$app = require __DIR__ . '/../src/app.php';
require __DIR__ . '/../src/routes.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app->get('/hello/{name}', function ($name) use ($app) {
    return 'Hello '.$app->escape($name);
});

$app->run();