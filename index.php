<?php

namespace ILostIt;

session_start();

use ILostIt\src\Middleware\UserIsMod;
use ILostIt\src\Middleware\UserLogged;
use ILostIt\src\Middleware\UserNotLogged;
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
    $group->get('', [\ILostIt\Controller\ObjectsController::class, 'objectsPage']);

    $group->get('/{id}', [\ILostIt\Controller\ObjectsController::class, 'objectPage']);

    $group->post('', [\ILostIt\Controller\ObjectsController::class, 'objectPost']);

    $group->patch('/{id}', [\ILostIt\Controller\ObjectsController::class, 'objectPatch']);

    $group->delete('/{id}', [\ILostIt\Controller\ObjectsController::class, 'objectCancel']);
})->add(new UserNotLogged());

$app->get('/solved-objects', [\ILostIt\Controller\ObjectsController::class, 'solvedObjects'])->add(new UserNotLogged());

# Auth route
$app->group('/auth', function (RouteCollectorProxy $group) {
    $group->group('/register', function (RouteCollectorProxy $group) {
        $group->get('', [\ILostIt\Controller\MembersController::class, 'registerPage']);

        $group->post('', [\ILostIt\Controller\MembersController::class, 'register']);

        $group->get('/verify/{id}', [\ILostIt\Controller\MembersController::class, 'verifyMemberPage']);

        $group->patch('/verify/{id}', [\ILostIt\Controller\MembersController::class, 'verifyMember']);
    });

    $group->group('/login', function (RouteCollectorProxy $group) {
        $group->get('', [\ILostIt\Controller\MembersController::class, 'loginPage']);
        $group->post('', [\ILostIt\Controller\MembersController::class, 'login']);
    });
})->add(new UserLogged());
$app->redirect('/auth', '/auth/login');

# Mod route
$app->group('/mod', function (RouteCollectorProxy $group) {
    $group->get('', [\ILostIt\Controller\ModController::class, 'get']);
})->add(new UserNotLogged())->add(new UserIsMod());

$app->addBodyParsingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

$app->run();
