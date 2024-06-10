<?php
namespace ILostIt\Controller;

use ILostIt\Model\Objects;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\PhpRenderer;

class HomeController
{
    public function index(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args,
        string $error = ""
    ): ResponseInterface {
        $objectsModel = new Objects();
        $nbObjects = 6;
        $filters = [["status", "=", 1]];
        $orderBy = [["id", "DESC"]];
        $objects = $objectsModel->getObjects($filters, 1, $nbObjects, $orderBy);

        $render = new PhpRenderer(__DIR__ . '/../View', ['title' => 'Accueil']);
        $render->setLayout('gabarit.php');

        if ($error != "") {
            return $render->render($response, 'home.php', ['error' => $error, 'objects' => $objects]);
        }

        return $render->render($response, 'home.php', ['objects' => $objects]);
    }
}