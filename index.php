<?php

namespace ILostIt;

use Slim\Middleware\MethodOverrideMiddleware;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;

require __DIR__ . '/vendor/autoload.php';

$app = AppFactory::create();

$app->addRoutingMiddleware();

// Add MethodOverride middleware
$methodOverrideMiddleware = new MethodOverrideMiddleware();
$app->add($methodOverrideMiddleware);

# Home route
$app->get('/', [\ILostIt\Controller\HomeController::class, 'index'])->setName('Home');
$app->redirect('/home', '/');

# Posts route
$app->group('/posts', function (RouteCollectorProxy $group) {
    $group->get('', [\ILostIt\Controller\PostsController::class, 'get']);

    $group->post('', [\ILostIt\Controller\PostsController::class, 'post']);

    $group->patch('/{id}', [\ILostIt\Controller\PostsController::class, 'patch']);

    $group->delete('/{id}', [\ILostIt\Controller\PostsController::class, 'delete']);
});

$app->addBodyParsingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(false, true, true);

$app->run();
