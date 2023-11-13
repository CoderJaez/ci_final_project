<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();

$routes->get('dashboard', 'DashboardController::index', ['filter' => 'auth']);

// $routes->post('authors/list', 'AuthorController::list', ['filter' => 'groupfilter:admin']);
// $routes->post('posts/list', 'PostController::list');
$routes->post('offices/list', 'OfficeController::list');
$routes->resource('permissions', ['controller' => 'PermissionController', 'except' => ['new', 'edit'], 'filter' => 'auth']);
$routes->resource('users', ['controller' => 'UserController', 'except' => ['new', 'edit'], 'filter' => 'auth']);



// $routes->resource('authors', ['controller' => 'AuthorController', 'except' => ['new', 'edit'], 'filter' => 'groupfilter:admin']);
// $routes->resource('posts', ['controller' => 'PostController', 'except' => ['new', 'edit'], 'filter' => 'auth']);
$routes->resource('offices', ['controller' => 'OfficeController', 'except' => ['new', 'edit'], 'filter' => 'groupfilter:admin']);
service('auth')->routes($routes);
