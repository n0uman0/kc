<?php

header("Access-Control-Allow-Origin: http://cc.localhost");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require dirname( __FILE__ ) . '/vendor/autoload.php';

use KnowledgeCity\Container;
use KnowledgeCity\Routes;
use KnowledgeCity\Database;
use KnowledgeCity\Controllers\CategoryController;
use KnowledgeCity\Controllers\CourseController;
use KnowledgeCity\Repositories\CategoryRepository;
use KnowledgeCity\Repositories\CourseRepository;

$container = new Container();

$container->bind(CategoryRepository::class, function() {
    return new CategoryRepository( Database::getInstance() );
});

$container->bind(CategoryController::class, function($container) {
    return new CategoryController(
        $container->make(CategoryRepository::class)
    );
});

$container->bind(CourseRepository::class, function() {
    return new CourseRepository( Database::getInstance() );
});

$container->bind(CourseController::class, function($container) {
    return new CourseController(
        $container->make(CourseRepository::class)
    );
});

$router = new Routes( $container);

$router->add('GET', '/categories', [CategoryController::class, 'getAll']);
$router->add('GET', '/categories/{id}', [CategoryController::class, 'getById']);
$router->add('GET', '/courses', [CourseController::class, 'getAll']);
$router->add('GET', '/courses/{id}', [CourseController::class, 'getById']);

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$router->dispatch($path);