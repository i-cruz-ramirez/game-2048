<?php

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app->get('/', function (Request $req, Response $res) {
	$basePath = $req->getUri()->getBasePath();
    //$this->logger->info("Slim-Skeleton '/' route");
    return $this->renderer->render($res, 'index.phtml', array("basePath" => $basePath));
});

$app->post('/process', function (Request $request, Response $response) {
	$data = $request->getParsedBody();
	$rules = isset($data['rules']) ? filter_var($data['rules'], FILTER_SANITIZE_STRING) : "";
	$lines = explode("\n",$rules);

	if(count($lines) > 5){
		$lines[0];
		$lines[1];
		$lines[2];
		$lines[3];
		$lines[4];
	}

    return $response->withJson($lines, 201);
});
