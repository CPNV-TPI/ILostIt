<?php

namespace ILostIt\Controller;

use ILostIt\Model\Members;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\PhpRenderer;

class MembersController
{
    public function register(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $render = new PhpRenderer(__DIR__ . '/../View', ['title' => 'S\'enregistrer']);
        $render->setLayout('gabarit.php');

        return $render->render($response, 'register.php');
    }

    public function registerPost(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $body = $request->getParsedBody();
        $values = array();

        foreach ($body as $key => $content) {
            if ($key == 'lastname' || $key == 'firstname' || $key == 'email' || $key == 'password') {
                $values = array_merge($values, array($key => $content));
            }
        }

        $members = new Members();
        $response = $members->register($values);

        return $response->withHeader('Location', '/')->withStatus(200);
    }
}
