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
		for($c = 0; $c < 4; $c++){
			$numbers = explode(" ",$lines[0]);
		}
		
	}

    return $response->withJson($lines, 201);
});
