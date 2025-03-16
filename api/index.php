<?php

use Assesment\Test\Controllers\CategoryController;
use Assesment\Test\Controllers\CourseController;

require './vendor/autoload.php';

// Set CORS headers before any output
header('Access-Control-Allow-Origin: http://cc.localhost');
header('Access-Control-Allow-Methods: GET, POST');

// Define routes
$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/courses', [CourseController::class, 'list']);
    $r->addRoute('GET', '/categories', [CategoryController::class, 'list']);
});

// Fetch method and URI from server request
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = rtrim($uri, '/'); // Normalize URI by trimming trailing slashes

// Dispatch request
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        http_response_code(404);
        echo json_encode(["error" => "404 Not Found"]);
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405);
        echo json_encode(["error" => "405 Method Not Allowed"]);
        break;
    case FastRoute\Dispatcher::FOUND:
        [$controller, $method] = $routeInfo[1];
        $vars = $routeInfo[2];
        $controllerInstance = new $controller();
        echo json_encode(call_user_func([$controllerInstance, $method], $vars));
        break;
}
