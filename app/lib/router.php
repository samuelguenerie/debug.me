<?php

require dirname(__DIR__) . '/config/routes.php';

$availableRouteNames = array_keys(ROUTES);

if (isset($_GET['page'])) {
    if ( in_array($_GET['page'], $availableRouteNames)) {
        $route = ROUTES[$_GET['page']];
        $controller = new $route['controller'];

        $controller->{$route['method']}();
    } else {
        throw new Exception('Page with route ' . $_GET['page'] . ' not found.');
    }
}