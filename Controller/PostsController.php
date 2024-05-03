<?php

namespace ILostIt\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ILostIt\Model\PostsModel;

class PostsController
{
    /**
     * Returns the Posts page
     *
     * @param  ServerRequestInterface $request
     * @param  ResponseInterface $response
     * @param  array $args
     * @return ResponseInterface
     */
    public function get(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        // Get the link params
        $params = $request->getQueryParams();

        $filters = array(["active", "=", 1]);

        if (isset($params['search'])) {
            array_push($filters, array("title", "LIKE", "%" . $params['search'] . "%"));
        }

        if (isset($params['location'])) {
            array_push($filters, array("location", "=", $params['location']));
        }

        $postsModel = new PostsModel();
        $posts = $postsModel->getPosts($filters);

        require_once 'View/posts.php';

        return $response;
    }

    /**
     * Handler for route /posts via POST method
     *
     * @param  ServerRequestInterface $request
     * @param  ResponseInterface $response
     * @param  array $args
     * @return ResponseInterface
     */
    public function post(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $body = $request->getParsedBody();
        $values = array();

        foreach ($body as $key => $content) {
            if ($key == 'title' || $key == 'desc' || $key == 'location') {
                $values = array_merge($values, array($key => $content));
            }
        }

        $postsModel = new PostsModel();
        $status = $postsModel->publishPost($values);

        if ($status) {
            return $response->withHeader('Location', '/posts')->withStatus(302);
        }

        return $response->withHeader('Location', '/posts')->withStatus(500);
    }

    /**
     * Handler for route /posts via PATCH method
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
            if ($key == 'title' || $key == 'desc' || $key == 'location') {
                $values = array_merge($values, array($key => $content));
            }
        }

        $postsModel = new PostsModel();
        $status = $postsModel->updatePost($id, $values);

        if ($status) {
            return $response->withStatus(200);
        }

        return $response->withStatus(500);
    }

    /**
     * Handler for route /posts via DELETE method
     *
     * @param  ServerRequestInterface $request
     * @param  ResponseInterface $response
     * @param  array $args
     * @return ResponseInterface
     */
    public function delete(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = $args['id'];

        $postsModel = new PostsModel();
        $status = $postsModel->deletePost($id);

        $response->getBody()->write("<script>window.location.replace('/posts');</script>");

        if ($status) {
            return $response->withStatus(200);
        }

        return $response->withStatus(500);
    }
}
