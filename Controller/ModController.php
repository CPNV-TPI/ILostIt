<?php

namespace ILostIt\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ILostIt\Model\Objects;
use Slim\Views\PhpRenderer;

class ModController
{
    public function objectsAwaitingValidation(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $params = $request->getQueryParams();

        $filters = [["status", "=", "0"]];
        $page = isset($params['page']) && is_numeric($params['page']) ? $params['page'] : 1;
        $nbObjects = 12;

        $objectsModel = new Objects();
        $objects = $objectsModel->getObjects($filters, $page, $nbObjects);

        $canBeANextPage = count($objects) == $nbObjects ?
            count($objectsModel->getObjects($filters, $page + 1, $nbObjects)) :
            false;

        $nextPage = $canBeANextPage == 0 ? null : $page + 1;
        $previousPage = $page > 1 ? $page - 1 : null;

        $attributes = [
            "objects" => $objects,
            "nextPage" => $nextPage,
            "previousPage" => $previousPage,
            "byType" => $params['type'] ?? null,
        ];

        $render = new PhpRenderer(__DIR__ . '/../View', ['title' => 'Modération']);
        $render->setLayout('gabarit.php');

        return $render->render($response, 'mod.php', $attributes);
    }
}
