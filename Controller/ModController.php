<?php

namespace ILostIt\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ILostIt\Model\Objects;
use Slim\Views\PhpRenderer;

class ModController
{
    public function get(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $render = new PhpRenderer(__DIR__ . '/../View', ['title' => 'Modération']);
        $render->setLayout('gabarit.php');

        return $render->render($response, 'mod.php', ["posts" => []]);
    }
}
