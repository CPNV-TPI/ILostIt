<?php

namespace ILostIt\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ILostIt\Model\Objects;
use Slim\Views\PhpRenderer;

class ObjectsController
{
    /**
     * Returns the objects page
     *
     * @param  ServerRequestInterface $request
     * @param  ResponseInterface $response
     * @param  array $args
     * @return ResponseInterface
     */
    public function index(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        // Get the link params
        $params = $request->getQueryParams();

        $filters = array(["status", "=", 1]);

        if (isset($params['type'])) {
            array_push($filters, array("type", "=", $params['type']));
        }

        $objectsModel = new Objects();
        $objects = $objectsModel->getObjects($filters);

        $render = new PhpRenderer(__DIR__ . '/../View', ['title' => 'Accueil']);
        $render->setLayout('gabarit.php');

        return $render->render($response, 'objects.php', ["objects" => $objects]);
    }

    public function objectPage(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        // Get the link params
        $params = $request->getQueryParams();

        $filters = array(["id", "=", $args['id']]);

        $objectsModel = new Objects();
        $object = $objectsModel->getObjects($filters)[0];

        $object['image'] = json_decode($object['image']);

        $render = new PhpRenderer(__DIR__ . '/../View', ['title' => $object['title']]);
        $render->setLayout('gabarit.php');

        return $render->render($response, 'object.php', ["object" => $object]);
    }

    /**
     * Handler for route /objects via POST method
     *
     * @param  ServerRequestInterface $request
     * @param  ResponseInterface $response
     * @param  array $args
     * @return ResponseInterface
     */
    public function object(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $body = $request->getParsedBody();
        $values = array();

        foreach ($body as $key => $content) {
            if ($key == 'title' || $key == 'description' || $key == 'classroom') {
                $values = array_merge($values, array($key => $content));
            }
        }

        $objectsModel = new Objects();
        $status = $objectsModel->publishObject($values);

        if ($status) {
            return $response->withHeader('Location', '/objects')->withStatus(302);
        }

        return $response->withHeader('Location', '/objects')->withStatus(500);
    }

    /**
     * Handler for route /objects via PATCH method
     *
     * @param  ServerRequestInterface $request
     * @param  ResponseInterface $response
     * @param  array $args
     * @return ResponseInterface
     */
    public function patch(ServerRequestInterface $request, $response, array $args): ResponseInterface
    {
        $body = $request->getParsedBody();
        $values = array();
        $id = $args['id'];

        foreach ($body as $key => $content) {
            if ($key == 'title' || $key == 'description' || $key == 'classroom') {
                $values = array_merge($values, array($key => $content));
            }
        }

        $objectsModel = new Objects();
        $status = $objectsModel->updateObject($id, $values);

        if ($status) {
            return $response->withStatus(200);
        }

        return $response->withStatus(500);
    }

    /**
     * Handler for route /objects via DELETE method
     *
     * @param  ServerRequestInterface $request
     * @param  ResponseInterface $response
     * @param  array $args
     * @return ResponseInterface
     */
    public function delete(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = $args['id'];

        $objectsModel = new Objects();
        $status = $objectsModel->deleteObject($id);

        $response->getBody()->write("<script>window.location.replace('/objects');</script>");

        if ($status) {
            return $response->withStatus(200);
        }

        return $response->withStatus(500);
    }
}
