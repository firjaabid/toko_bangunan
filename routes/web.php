<?php

/** @var \Laravel\Lumen\Routing\Router $router */

// Suppliers (Main Table)
$router->get('/suppliers', 'SuppliersController@index');
$router->get('/suppliers/{id}', 'SuppliersController@show');

// Materials (Secondary Table - supports ?supplier_id=...)
$router->get('/materials', 'MaterialsController@index');
$router->get('/materials/{id}', 'MaterialsController@show');

// Protected Endpoints (INSERT, UPDATE, DELETE) dengan Basic Auth
$router->group(['middleware' => 'basic.auth'], function () use ($router) {
    $router->post('/suppliers', 'SuppliersController@store');
    $router->put('/suppliers/{id}', 'SuppliersController@update');
    $router->delete('/suppliers/{id}', 'SuppliersController@destroy');

    $router->post('/materials', 'MaterialsController@store');
    $router->put('/materials/{id}', 'MaterialsController@update');
    $router->delete('/materials/{id}', 'MaterialsController@destroy');
});
