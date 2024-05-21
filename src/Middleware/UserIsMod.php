<?php

namespace ILostIt\src\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseInterface as ResponseInterface;
use Slim\Psr7\Response as Response;

class UserIsMod
{
    public function __invoke(Request $request, RequestHandler $handler): ResponseInterface
    {
        if ($_SESSION["isMod"] != 1) {
            $response = new Response();

            return $response->withHeader("Location", "/")->withStatus(302);
        }

        return $handler->handle($request);
    }
}
