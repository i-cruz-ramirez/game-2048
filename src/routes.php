<?php

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app->get('/', function (Request $req, Response $res, $args) {
    $this->logger->info("Slim-Skeleton '/' route");
    return $this->renderer->render($res, 'index.phtml', $args);
});

$app->get('/process', function (Request $req,  Response $res, $args = []) {
    return $res->withStatus(400)->write('Bad Request');
});
