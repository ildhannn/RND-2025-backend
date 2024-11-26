<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

// Auth
$router->group(['prefix' => 'api'], function () use ($router) {
    $router->post('/login', 'AuthControllers@login');
});

$router->group(['prefix' => 'api', 'middleware' => 'jwt.auth'], function () use ($router) {
    $router->post('/logout', 'AuthControllers@logout');

    // User
    $router->get('/getAllUser', 'UserControllers@getAllUser');
    $router->get('/getIdUser/{id}', 'UserControllers@getIdUser');
    $router->post('/createUser', 'UserControllers@createUser');
    $router->post('/updateUser/{id}', 'UserControllers@updateUser');
    $router->post('/deleteUser/{id}', 'UserControllers@deleteUser');

    // Kewenangan
    $router->get('/getAllKewenangan', 'KewenanganControllers@getAllKewenangan');
    $router->post('/createKewenangan', 'KewenanganControllers@createKewenangan');
    $router->post('/getKewenanganMenu/{id}', 'KewenanganControllers@getKewenanganMenu');

    // Log
    $router->get('/getAllLog', 'LogControllers@getAllLog');

    // Menu
    $router->get('/getAllMenu', 'MenuControllers@getAllMenu');
    $router->get('/getMenuUser', 'MenuControllers@getMenuUser');
    $router->post('/createMenu', 'MenuControllers@createMenu');
    $router->post('/updateMenu/{id}', 'MenuControllers@updateMenu');
    $router->post('/getMenuId/{id}', 'MenuControllers@getMenuId');
    $router->post('/deleteMenu/{id}', 'MenuControllers@deleteMenu');

    // Pengaturan
    $router->get('/getPengaturan', 'PengaturanController@getPengaturan');
    $router->post('/updatePengaturan', 'PengaturanController@updatePengaturan');

    // Face Recognation
    $router->get('/getFR/{id}', 'FaceRecognationController@getFR');
    $router->post('/regisFR/{id}', 'FaceRecognationController@regisFR');

});
