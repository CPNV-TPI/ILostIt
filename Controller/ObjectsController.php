<?php

namespace ILostIt\Controller;

use ILostIt\Controller\HomeController;
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
    public function objectsPage(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        // Get the link params
        $params = $request->getQueryParams();

        $filters = array(["status", "=", 1]);

        if (isset($params['type'])) {
            $filters[] = array("type", "=", $params['type']);
        }

        $objectsModel = new Objects();
        $page = isset($params['page']) && is_numeric($params['page']) ? $params['page'] : 1;
        $nbObjects = 12;
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

        return $this->render($response, "Les objets", "objects", $attributes);
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

        return $this->render($response, $object["title"], "object", ["object" => $object]);
    }

    public function solvedObjects(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        // Get the link params
        $params = $request->getQueryParams();

        $filters = array(["status", "=", 3]);

        if (isset($params['type'])) {
            $filters[] = array("type", "=", $params['type']);
        }

        $objectsModel = new Objects();
        $page = isset($params['page']) && is_numeric($params['page']) ? $params['page'] : 1;
        $nbObjects = 12;
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

        return $this->render($response, "Les objets résolus", "solved-objects", $attributes);
    }

    /**
     * Handler for route /objects via POST method
     *
     * @param  ServerRequestInterface $request
     * @param  ResponseInterface $response
     * @param  array $args
     * @return ResponseInterface
     */
    public function objectPost(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $body = $request->getParsedBody();
        $values = [];
        $home = new HomeController();

        foreach ($body as $key => $content) {
            if ($content != "") {
                $values[$key] = $content;
            }
        }
        $values['memberOwner_id'] = $_SESSION['id'];

        $images = [];
        foreach ($_FILES['image']['tmp_name'] as $key => $image) {
            // Checks image size and type to prevent empty files and incorrect named files
            if (filesize($image) <= 0 || !exif_imagetype($image)) {
                return $home->index($request, $response, $args, $error);
            }

            $imageType = $_FILES['image']['type'][$key];
            $imageType = str_replace('image/', '', $imageType);

            $images[] = ["image" => $image, "type" => $imageType];
        }

        $objectsModel = new Objects();
        $status = $objectsModel->publishObject($values, $images);

        if (!$status) {
            return $response->withHeader("Location", "/?created=false")->withStatus(302);
        }

        return $response->withHeader("Location", "/?created=true")->withStatus(302);
    }

    /**
     * Handler for route /objects via PATCH method
     *
     * @param  ServerRequestInterface $request
     * @param  ResponseInterface $response
     * @param  array $args
     * @return ResponseInterface
     */
    public function objectPatch(ServerRequestInterface $request, $response, array $args): ResponseInterface
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
    public function objectCancel(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $id = $args['id'];

        $objectsModel = new Objects();
        $status = $objectsModel->deleteObject($id);

        $response->getBody()->write("<script>window.location.replace('/objects');</script>");

        if ($status) {
            return $response->withStatus(200);
        }

        return $response->withStatus(500);
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
