<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app->get('/', function() use($app) {
    return 'silex is running A';
});

$app->get('/test', function() use($app) {
    return 'test is running A';
});

$app->get('/hello/{name}', function ($name) use ($app) {
    return 'Hello '.$app->escape($name).' A';
});

//echo "testeTres";
//exit;

/*
//Admin
$app->post('/admins/auth', 'Controllers\AdminController::authenticate');

//User
$app->post('/users/create', 'Controllers\UserController::create');
$app->post('/users/auth', 'Controllers\UserController::authenticate');

//Bookmark
$app->post('/bookmarks/create', 'Controllers\BookmarkController::create');
$app->post('/bookmarks', 'Controllers\BookmarkController::bookmarkList');
$app->post('/bookmarks/{id}', 'Controllers\BookmarkController::edit');
$app->delete('/bookmarks/{id}', 'Controllers\BookmarkController::edit');
*/