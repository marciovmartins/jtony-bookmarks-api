<?php

use Silex\Application;
use Silex\Provider\UrlGeneratorServiceProvider;

#TODO: Mover para um .conf alimentado por variaveis de ambiente (Docker/Tsuru)
/*
$conn = array (
        'driver'    => 'pdo_pgsql',
        'host'      => 'localhost',
        'port'      => '5432',
        'dbname'    => 'jtony_bookmarks_api',
        'user'      => 'jtony_blog_api_app',
        'password'  => 'jtony123'
    );
 */

$conn = array (
        'driver'    => 'pdo_pgsql',
        'host'      => 'ec2-54-204-25-54.compute-1.amazonaws.com',
        'port'      => '5432',
        'dbname'    => 'dbf4vmhmmaqam1',
        'user'      => 'rzydapbdnwkfqv',
        'password'  => 'cqcE_1Rl6_tNhrtR26ihK1sSZ0'
    );

$app = new Application();

$app->register(new UrlGeneratorServiceProvider()); 

/*
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => $conn,
));
*/

$app['SECURITY_HASH'] = '<(-+KZ&Y<})';

return $app;
