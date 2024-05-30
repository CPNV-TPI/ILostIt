<?php

namespace ILostIt\Middleware;

use Psr\Http\Message\ResponseInterface as ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as Response;

class UserNotLogged
{
    public function __invoke(Request $request, RequestHandler $handler): ResponseInterface
    {
        if (!isset($_SESSION["id"])) {
            $response = new Response();

            return $response->withHeader("Location", "/auth")->withStatus(302);
        }

        return $handler->handle($request);
    }
}
