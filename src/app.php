<?php

use Silex\Application;
use Silex\Provider\UrlGeneratorServiceProvider;

$app = new Application();

$app->register(new UrlGeneratorServiceProvider()); 

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => $conn,
));

$app['SECURITY_HASH'] = '<(-+KZ&Y<})';

return $app;
