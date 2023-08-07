<?php

require_once '../services/users.php';
require_once '../services/sales.php';
require_once '../core/http.php';
require_once '../core/database.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$routes = [
    '/'           => 'users/login.php',
    '/login'      => 'users/login.php',
    '/register'   => 'users/register.php',
    '/logout'     => 'users/logout.php',

    '/dashboard'    => 'dashboard/dashboard.php',

    '/sales/list'        => 'sales/list.php',
    '/sales/detail'      => 'sales/detail.php',
    '/sales/create'      => 'sales/create.php',
    '/sales/edit'        => 'sales/edit.php',
    '/sales/delete'      => 'sales/delete.php',

    '/services/list'        => 'services/list.php',
    '/services/detail'      => 'services/detail.php',
    '/services/create'      => 'services/create.php',
    '/services/edit'        => 'services/edit.php',
    '/services/delete'      => 'services/delete.php',
];

$route = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/';

$controller = isset($routes[$route]) ? $routes[$route] : null;

if (is_null($controller)) {
    header("HTTP/1.1 404 Not Found");
    die('404 Not Found');
};

$path = '../controllers/' . $controller;

if (!file_exists($path)) {
    header("HTTP/1.1 500 Internal Server Error");
    die('Controller file not found');
}

require_once $path;
