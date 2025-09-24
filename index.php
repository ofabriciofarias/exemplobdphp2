<?php

require_once __DIR__ . '/vendor/autoload.php';

// Start session once at the application entry point
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Basic routing
$request_uri = $_SERVER['REQUEST_URI'];
$request_method = $_SERVER['REQUEST_METHOD'];

// Get the URI from the 'url' GET parameter if it exists (from .htaccess rewrite)
$uri = isset($_GET['url']) ? '/' . $_GET['url'] : '/';

// Ensure URI starts with a slash
if (empty($uri) || $uri[0] !== '/') {
    $uri = '/' . $uri;
}

// --- DEBUGGING START ---
error_log("REQUEST_URI: " . $request_uri);
error_log("GET[url]: " . (isset($_GET['url']) ? $_GET['url'] : 'NOT SET'));
error_log("Processed URI: " . $uri);
// --- DEBUGGING END ---

$routes = [
    'GET /' => ['App\Controller\HomeController', 'index'], // Default route
    // Noticia routes
    'GET /noticias' => ['App\Controller\NoticiaController', 'index'],
    'GET /noticias/{id}' => ['App\Controller\NoticiaController', 'show'],
    'POST /noticias' => ['App\Controller\NoticiaController', 'create'],
    'PUT /noticias/{id}' => ['App\Controller\NoticiaController', 'update'],
    'DELETE /noticias/{id}' => ['App\Controller\NoticiaController', 'delete'],

    'GET /register' => ['App\Controller\UsuarioController', 'registerForm'],
        'POST /register' => ['App\Controller\UsuarioController', 'register'],
        'GET /login' => ['App\Controller\UsuarioController', 'loginForm'], // Assuming a login form
    'GET /logout' => ['App\Controller\UsuarioController', 'logout'], // Added logout route

    // Usuario routes
    'POST /usuarios/login' => ['App\Controller\UsuarioController', 'login'],
    'GET /usuarios' => ['App\Controller\UsuarioController', 'index'],
    'GET /usuarios/{id}' => ['App\Controller\UsuarioController', 'show'],
    'POST /usuarios' => ['App\Controller\UsuarioController', 'create'],
    'PUT /usuarios/{id}' => ['App\Controller\UsuarioController', 'update'],
    'DELETE /usuarios/{id}' => ['App\Controller\UsuarioController', 'delete'],
];

$matched = false;
foreach ($routes as $route => $handler) {
    list($method, $pattern) = explode(' ', $route);
    $pattern = preg_replace('/{id}/', '(\d+)', preg_quote($pattern, '/'));

    if ($request_method == $method && preg_match('/^' . $pattern . '$/', $uri, $matches)) {
        list($controller, $action) = $handler;
        
        if (class_exists($controller)) {
            $controller_instance = new $controller();
            $id = isset($matches[1]) ? $matches[1] : null;
            $controller_instance->$action($id);
        } else {
            http_response_code(404);
            echo json_encode(['error' => "Controller not found: $controller"]);
        }
        $matched = true;
        break;
    }
}

if (!$matched) {
    http_response_code(404);
    echo json_encode(['error' => 'Route not found']);
}