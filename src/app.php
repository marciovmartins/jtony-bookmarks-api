<?php
use Silex\Application;
use Silex\Provider\UrlGeneratorServiceProvider;

$app = new Application();

$app['settings'] = require __DIR__ . '/../config/settings.php';

$app->register(new UrlGeneratorServiceProvider()); 

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => $app['settings']['connDb'],
));

return $app;
