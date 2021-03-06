<?php
declare(strict_types=1);

error_reporting(E_ALL);

require_once __DIR__ . '/vendor/autoload.php';

use pskuza\dyndns;

try {

    $rss = new dyndns\server("config.ini", "dyndns.db");

    $dispatcher = FastRoute\cachedDispatcher(function(FastRoute\RouteCollector $r) {
        $r->addRoute('GET', '/update', 'update');
        $r->addRoute('GET', '/register', 'register');
    }, [
        'cacheFile' => __DIR__ . '/route.cache'
    ]);

    $httpMethod = $_SERVER['REQUEST_METHOD'];
    $uri = $_SERVER['REQUEST_URI'];

    $uri = rawurldecode($uri);

    $routeInfo = $dispatcher->dispatch($httpMethod, $uri);
    switch ($routeInfo[0]) {
        case FastRoute\Dispatcher::NOT_FOUND:
            $rss->error(404, '404');
            break;
        case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
            $rss->error(405, '405');
            break;
        case FastRoute\Dispatcher::FOUND:
            $handler = $routeInfo[1];
            $options = (array) $routeInfo[2];
            try {
                $return = $rss->$handler($options);
            } catch (\InvalidArgumentException $e) {
                $rss->error(400, 'Invalid Argument: ' . $e->getMessage());
            }
            if ($return[0] === true) {
                $rss->success($return[1], $return[2]);
            }
            $rss->error($return[1], $return[2]);
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    error_log("Uncatched exception." . $e->getMessage(), 0);
}