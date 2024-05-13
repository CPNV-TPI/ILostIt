<?php

namespace ILostIt\Controller;

use ILostIt\Model\Members;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\PhpRenderer;

class MembersController
{
    public function index(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args,
        string $error = ""
    ): ResponseInterface {
        $attributes = ['title' => 'S\'enregistrer'];

        if ($error != "") {
            $attributes = array_merge($attributes, ['error' => $error]);
        }

        $render = new PhpRenderer(__DIR__ . '/../View', $attributes);
        $render->setLayout('gabarit.php');

        return $render->render($response, 'register.php');
    }

    public function register(
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
        $r = $members->registerNewMember($values);

        if (is_string($r)) {
            $this->index($request, $response, $args, $r);
            return $response;
        }

        return $response->withHeader('Location', '/')->withStatus(302);
    }

    public function verifyMemberPage(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $attributes = ['title' => 'Vérifiez votre compte'];
        $userIdToVerify = $args['id'];

        $render = new PhpRenderer(__DIR__ . '/../View', $attributes);
        $render->setLayout('gabarit.php');

        return $render->render($response, 'verify.php', ['email' => $userIdToVerify]);
    }

    public function verifyMember(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $userIdToVerify = $args['id'];

        $members = new Members();
        $result = $members->verifyUser($userIdToVerify);

        if (!$result) {
            return $response->withStatus(400);
        }

        return $response->withStatus(200);
    }
}
