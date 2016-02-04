<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Predis\Client;

$app->get('/', function() use($app) {
    return 'Rest API Root';
});

$app->get('/php', function() use($app) {
    phpinfo();
});

//Admin
$app->post('/admins/auth', 'Controllers\AdminController::authenticate');
$app->get('/admins/{idAdmin}/users', 'Controllers\AdminController::getUsers');

//User
$app->post('/users', 'Controllers\UserController::create');
$app->post('/users/auth', 'Controllers\UserController::authenticate');


//Bookmark
$app->get('/users/{idUser}/bookmarks', 'Controllers\BookmarkController::bookmarkList')->assert('idUser', '\d+');
$app->post('/users/{idUser}/bookmarks', 'Controllers\BookmarkController::create')->assert('idUser', '\d+');
$app->post('/bookmarks/{idBookmark}', 'Controllers\BookmarkController::edit')->assert('idBookmark', '\d+');
$app->get('/bookmarks/{idBookmark}', 'Controllers\BookmarkController::getBookmark')->assert('idBookmark', '\d+');
$app->delete('/bookmarks/{idBookmark}', 'Controllers\BookmarkController::delete')->assert('idBookmark', '\d+');
