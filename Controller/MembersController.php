<?php

namespace ILostIt\Controller;

use ILostIt\Model\Members;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\PhpRenderer;
use Throwable;

class MembersController
{
    /**
     * This method returns the register page
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @param string $error
     * @return ResponseInterface
     * @throws Throwable
     */
    public function registerPage(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args,
        string $error = ""
    ): ResponseInterface {
        return $this->render($response, 'S\'enregistrer', 'register', [], $error);
    }

    /**
     * This method handles the user register request
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     * @throws Throwable
     */
    public function register(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $body = $request->getParsedBody();
        $values = array();

        foreach ($body as $key => $content) {
            if (
                ($key == "email" && $content == "")
                || ($key == "password" && $content == "")
            ) {
                $error = "Une erreur est survenue !";
                $this->registerPage($request, $response, $args, $error);

                return $response;
            }

            $values[$key] = $content;
        }

        $members = new Members();
        $r = $members->registerNewMember($values);

        if (is_string($r)) {
            $this->registerPage($request, $response, $args, $r);
            return $response;
        }

        return $response->withHeader('Location', '/')->withStatus(302);
    }

    /**
     * This method returns the login page
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @param string $error
     * @return ResponseInterface
     * @throws Throwable
     */
    public function loginPage(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args,
        string $error = ""
    ): ResponseInterface {
        return $this->render($response, 'Se connecter', 'login', [], $error);
    }

    /**
     * This method handles the login request
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     * @throws Throwable
     */
    public function login(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $body = $request->getParsedBody();

        if (
            isset($body['email'])
            && isset($body['password'])
            && $body['email'] != ""
            && $body['password'] != ""
        ) {
            $email = $body['email'];
            $password = $body['password'];
        } else {
            $error = "Une erreur est suvernue !";
            $this->loginPage($request, $response, $args, $error);
            return $response;
        }

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

    /**
     * This method returns the verify member page
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     * @throws Throwable
     */
    public function verifyMemberPage(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $attributes = ["id" => $args['id']];

        return $this->render($response, 'Vérifiez votre compte', 'verify', $attributes);
    }

    /**
     * This method handles the verify member request
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     */
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

    /**
     * This method handles the logout request
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     */
    public function logout(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        session_destroy();

        return $response->withHeader('Location', '/')->withStatus(302);
    }

    /**
     * This method is designed to render a view
     *
     * @param ResponseInterface $response
     * @param string $title
     * @param string $page
     * @param array $attributes
     * @param string $error
     * @return ResponseInterface
     * @throws Throwable
     */
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
