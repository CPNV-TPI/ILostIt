<?php

namespace ILostIt\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ILostIt\Model\Objects;
use Slim\Views\PhpRenderer;
use Throwable;

class ObjectsController
{
    /**
     * Returns the objects page
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     * @throws Throwable
     */
    public function objectsPage(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        // Get the link params
        $params = $request->getQueryParams();

        $filters = [["status", "=", 1]];

        if (isset($params['type'])) {
            $filters[] = ["type", "=", $params['type']];
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

    /**
     * Returns the page of an object
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @param string $error
     * @return ResponseInterface
     * @throws Throwable
     */
    public function objectPage(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args,
        string $error = ""
    ): ResponseInterface {
        $filters = [["id", "=", $args['id']]];

        $objectsModel = new Objects();
        $object = $objectsModel->getObjects($filters)[0];

        return $this->render($response, $object["title"], "object", ["object" => $object], $error);
    }

    /**
     * Returns the page with the solved objects
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     * @throws Throwable
     */
    public function solvedObjects(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        // Get the link params
        $params = $request->getQueryParams();

        $filters = [["status", "=", 3]];

        if (isset($params['type'])) {
            $filters[] = ["type", "=", $params['type']];
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
     * This method is designed to return the own objects page
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     * @throws Throwable
     */
    public function ownObjects(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        // Get the link params
        $params = $request->getQueryParams();

        $userId = $_SESSION['id'];
        $filters = [["memberOwner_id", "=", $userId]];

        if (isset($params['type'])) {
            $filters[] = ["type", "=", $params['type']];
        }

        if (isset($params['status'])) {
            $filters[] = ["status", "=", $params['status']];
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
            "byStatus" => $params['status'] ?? null,
        ];

        return $this->render($response, "Mes objets", "my-objects", $attributes);
    }

    /**
     * This function is designed to show the solve page
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     * @throws Throwable
     */
    public function solvePage(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $id = $args['id'];

        $attributes = ['id' => $id];
        return $this->render($response, "Résolution", "solve", $attributes);
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
                return $response->withHeader("Location", "/?created=false")->withStatus(302);
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
     * This method is designed to handle the validation of an object
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     */
    public function objectValidation(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $body = $request->getParsedBody();
        $reason = $body['reason'] ?? null;
        $objectId = $args['id'];
        $objectsModel = new Objects();

        $status = null;
        if ($reason == null) {
            $status = $objectsModel->validateObject($objectId);
        }

        if ($reason != "") {
            $status = $objectsModel->validateObject($objectId, false, $reason);
        }

        if (!$status) {
            return $response->withStatus(500);
        }

        return $response->withStatus(200);
    }

    /**
     * This method is designed to handle the contact form of an object
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     */
    public function objectContact(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $objectId = $args['id'];
        $userFinderEmail = $_SESSION['email'];

        $objectsModel = new Objects();

        $status = $objectsModel->contactOwner($objectId, $userFinderEmail);

        if (!$status) {
            return $this->objectPost($request, $response, $args);
        }

        return $response->withHeader("Location", "/objects")->withStatus(302);
    }

    /**
     * This method is designed to handle the resolution of an object
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     */
    public function solveObject(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $body = $request->getParsedBody();
        $finderEmail = isset($body['finder_email']) && $body['finder_email'] != "" ? $body['finder_email'] : null;
        $foundAlone = isset($body['found_alone']) && $body['found_alone'];

        $params = $request->getQueryParams();
        $confirmSolve = isset($params['confirm']) && $params['confirm'] == "true";

        $objectId = $args['id'];

        $objectsModel = new Objects();

        $status = false;
        if ($finderEmail != null) {
            $status = $objectsModel->solveObject($objectId, $_SESSION['id'], $finderEmail, $confirmSolve);
        } elseif ($foundAlone) {
            $status = $objectsModel->solveObject($objectId, $_SESSION['id']);
        } elseif ($confirmSolve) {
            $status = $objectsModel->solveObject($objectId, $_SESSION['id'], $finderEmail, true);
        }

        if (!$status) {
            return $response->withStatus(500);
        }

        return $response->withStatus(200);
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
        $status = $objectsModel->cancelObject($id);

        if (!$status) {
            return $response->withStatus(500);
        }

        return $response->withStatus(200);
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
