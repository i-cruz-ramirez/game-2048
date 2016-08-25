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

	$grid = isset($data['grid']) ? $data['grid'] : array();
	$quantity = isset($data['quantity']) ? (int)$data['quantity']: "";
	$movements = isset($data['movements']) ? $data['movements'] : array();

	if(count($grid) != 4 && count($movements) != $quantity){
		$error = array();
		$error[] = "No se cumplen con las reglas iniciales";
		return $response->withJson($error, 201);
	}
	foreach ($movements as $key => $movement) {
		switch ($movement) {
			case 'Izquierda':
				$grid = moveLeftGrid($grid);
				break;
			case 'Derecha':
				$grid = moveRigthGrid($grid);
				break;
			case 'Arriba':
				$grid = moveUpGrid($grid);
				break;
			case 'Abajo':
				$grid = moveDownGrid($grid);
				break;
		}
	}
	$grid = implode("\r\n", $grid);

	return $response->withJson(array("result"=>true, "grid" => $grid), 201);
});

function moveRigthGrid(array $grid)
{
	foreach ($grid as $key => $line) {
		$numbers = moveZeroToLeft(explode(" ", $line));
		if($numbers[3] == $numbers[2]){
			$numbers[3] = $numbers[3] + $numbers[2];
			$numbers[2] = $numbers[1];
			$numbers[1] = $numbers[0];
			$numbers[0] = 0;
		}
		if($numbers[2] == $numbers[1]){
			$numbers[2] = $numbers[1] + $numbers[2];
			$numbers[1] = $numbers[0];
			$numbers[0] = 0;
		}
		if($numbers[1] == $numbers[0]){
			$numbers[1] = $numbers[1] + $numbers[0];
			$numbers[0] = 0;
		}
		$grid[$key] = implode(" ", $numbers);
	}
	return $grid;
}

function moveLeftGrid(array $grid)
{
	foreach ($grid as $key => $line) {
		$numbers = moveZeroToRigth(explode(" ", $line));
		if($numbers[0] == $numbers[1]){
			$numbers[0] = $numbers[0] + $numbers[1];
			$numbers[1] = $numbers[2];
			$numbers[2] = $numbers[3];
			$numbers[3] = 0;
		}
		if($numbers[1] == $numbers[2]){
			$numbers[1] = $numbers[1] + $numbers[2];
			$numbers[2] = $numbers[3];
			$numbers[3] = 0;
		}
		if($numbers[2] == $numbers[3]){
			$numbers[2] = $numbers[2] + $numbers[3];
			$numbers[3] = 0;
		}
		$grid[$key] = implode(" ", $numbers);
	}
	return $grid;
}

function moveUpGrid(array $grid)
{
	return $grid;
}

function moveDownGrid(array $grid)
{
	return $grid;
}

function moveZeroToRigth(array $numbers)
{
	$return = array();
	for ($i = 0; $i < count($numbers); $i++) {
		if($numbers[$i] != 0){
			$return[] = $numbers[$i];
		}
	}
	for ($i=count($return); $i < count($numbers); $i++) { 
		$return[$i] = 0;
	}
	return $return;
}

function moveZeroToLeft(array $numbers)
{
	$return = array();
	for ($i = 0; $i < count($numbers); $i++) {
		if($numbers[$i] != 0){
			$return[] = $numbers[$i];
		}
	}
	for ($i=count($return); $i < count($numbers); $i++) { 
		array_unshift($return, "0");
	}
	return $return;
}