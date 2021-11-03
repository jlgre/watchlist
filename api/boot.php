<?php

require_once('secret/variables.php');
require_once('config/db.php');

// Require any route handlers here
$movie_routes = require_once('routes/movies.php');
$admin = require_once('routes/admin.php');

$db = get_db(
    DATABASE_CONNECTION_INFO['host'],
    DATABASE_CONNECTION_INFO['port'],
    DATABASE_CONNECTION_INFO['db'],
    DATABASE_CONNECTION_INFO['user'],
    DATABASE_CONNECTION_INFO['pass'],
);

$routes = [
    '/movies' => $movie_routes['list'],
    '/movies/markWatched' => $movie_routes['markWatched'],
    '/movies/add' => $movie_routes['add'],
    '/auth' => $admin['auth']
];

$uri = str_replace('/api', '', $_SERVER['REQUEST_URI']);
$uri = strpos($uri, '?') ? substr($uri, 0, strpos($uri, '?')) : $uri;
if (substr($uri, -1) === "/") {
    $uri = substr($uri, 0, strlen($uri) - 1);
}

if (in_array($uri, array_keys($routes))) {
    echo call_user_func($routes[$uri], $db, $_REQUEST);
} else {
    http_response_code(404);
    echo $uri;
}