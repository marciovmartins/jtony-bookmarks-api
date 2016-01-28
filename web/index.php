<?php

// set the error handling
ini_set('display_errors', 1);
error_reporting(-1);

//DEFAULT PHP CONFIGS
setlocale(LC_ALL, 'pt_BR.UTF-8', 'pt_BR', 'portuguese');
date_default_timezone_set( "America/Sao_Paulo" );
mb_internal_encoding("UTF-8");

$loader = require_once __DIR__.'/../vendor/autoload.php';

$app = require __DIR__ . '/../src/app.php';
require __DIR__ . '/../src/routes.php';

$app->run();