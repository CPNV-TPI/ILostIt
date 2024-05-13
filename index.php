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
$app->get('/', [\ILostIt\Controller\HomeController::class, 'index'])->setName('Accueil');
$app->redirect('/home', '/');

# Objects route
$app->group('/objects', function (RouteCollectorProxy $group) {
    $group->get('', [\ILostIt\Controller\ObjectsController::class, 'index']);

    $group->get('/{id}', [\ILostIt\Controller\ObjectsController::class, 'objectPage']);

    $group->post('', [\ILostIt\Controller\ObjectsController::class, 'post']);

    $group->patch('/{id}', [\ILostIt\Controller\ObjectsController::class, 'patch']);

    $group->delete('/{id}', [\ILostIt\Controller\ObjectsController::class, 'delete']);
});

# Auth route
$app->group('/auth', function (RouteCollectorProxy $group) {
    $group->group('/register', function (RouteCollectorProxy $group) {
        $group->get('', [\ILostIt\Controller\MembersController::class, 'index']);

        $group->post('', [\ILostIt\Controller\MembersController::class, 'register']);

        $group->get('/verify/{id}', [\ILostIt\Controller\MembersController::class, 'verifyMemberPage']);

        $group->patch('/verify/{id}', [\ILostIt\Controller\MembersController::class, 'verifyMember']);
    });

    $group->redirect('', '/auth/login');
});

# Mod route
$app->group('/mod', function (RouteCollectorProxy $group) {
    $group->get('', [\ILostIt\Controller\ModController::class, 'get']);
});

$app->addBodyParsingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

$app->run();
