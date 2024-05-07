<?php
namespace ILostIt\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\PhpRenderer;

class HomeController
{
    public function index(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $render = new PhpRenderer(__DIR__ . '/../View', ['title' => 'Accueil']);
        $render->setLayout('gabarit.php');

        return $render->render($response, 'home.php');
    }
}