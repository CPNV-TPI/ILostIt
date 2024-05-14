<?php

namespace ILostIt\Controller;

use ILostIt\Model\Members;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\PhpRenderer;

class MembersController
{
    public function registerPage(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args,
        string $error = ""
    ): ResponseInterface {
        return $this->render($response, 'S\'enregistrer', 'register', [], $error);
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
            $this->registerPage($request, $response, $args, $r);
            return $response;
        }

        return $response->withHeader('Location', '/')->withStatus(302);
    }

    public function loginPage(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args,
        string $error = ""
    ): ResponseInterface {
        return $this->render($response, 'Se connecter', 'login', [], $error);
    }

    public function login(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $body = $request->getParsedBody();

        $email = $body['email'];
        $password = $body['password'];

        $members = new Members();
        $result = $members->checkLogin($email, $password);

        if (is_string($result)) {
            $this->loginPage($request, $response, $args, $result);
            return $response;
        }

        foreach ($result as $key => $content) {
            $_SESSION[$key] = $content;
        }

        return $response->withHeader('Location', '/')->withStatus(302);
    }

    public function verifyMemberPage(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $attributes = [ "id" =>  $args['id'] ];

        return $this->render($response, 'Vérifiez votre compte', 'verify', $attributes);
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

    private function render(
        ResponseInterface $response,
        string $title,
        string $page,
        array $attributes = [],
        string $error = ""
    ): ResponseInterface {
        $finalAttributes = ['title' => $title];

        if (!empty($attributes)) {
            foreach ($attributes as $key => $value) {
                $finalAttributes[$key] = $value;
            }
        }

        if ($error != "") {
            $finalAttributes['error'] = $error;
        }

        $render = new PhpRenderer(__DIR__ . '/../View', $finalAttributes);
        $render->setLayout('gabarit.php');

        return $render->render($response, $page . '.php');
    }
}
