<?php

use Silex\Application;
use Silex\Provider\UrlGeneratorServiceProvider;

/*
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;	
*/

#TODO: Mover para um .conf alimentado por variaveis de ambiente (Docker/Tsuru)
$conn = array (
        'driver'    => 'pdo_pgsql',
        'host'      => 'localhost',
        'port'      => '5432',
        'dbname'    => 'jtony_bookmarks_api',
        'user'      => 'jtony_blog_api_app',
        'password'  => 'jtony123'
    );

$app = new Application();

$app->register(new UrlGeneratorServiceProvider()); 

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => $conn,
));

$app['SECURITY_HASH'] = '<(-+KZ&Y<})';

return $app;
