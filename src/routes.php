<?php

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app->get('/', function (Request $req, Response $res) {
	$basePath = $req->getUri()->getBasePath();
    $this->logger->info("Slim-Skeleton '/' route");
    return $this->renderer->render($res, 'index.phtml', array("basePath" => $basePath));
});

$app->post('/process', function (Request $request, Response $response) {
    return $res->withStatus(400)->write('Bad Request');
});
