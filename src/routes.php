<?php

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app->get('/', function (Request $req, Response $res) {
	$basePath = $req->getUri()->getBasePath();
    //$this->logger->info("Slim-Skeleton '/' route");
    return $this->renderer->render($res, 'index.phtml', array("basePath" => $basePath));
});

$app->post('/process', function (Request $request, Response $response) {
	$json = array();
	$data = $request->getParsedBody();

	// Recibimos la variables
	$grid = isset($data['grid']) ? $data['grid'] : array();
	$quantity = isset($data['quantity']) ? (int)$data['quantity']: "";
	$movements = isset($data['movements']) ? $data['movements'] : array();

	// Validación de parametros
	if(count($grid) != 4 && count($movements) != $quantity){
		$json["messages"] = "No se cumplen con las reglas iniciales";
		$json["result"] = "No se cumplen con las reglas iniciales";
		return $response->withJson($json, 201);
	}

	// Convertimos el grid en array
	$grid = gridToArray($grid);

	// Iteramos por cada movimiento recibido
	foreach ($movements as $key => $movement) {
		// Ecogemos el tipo de movimiento y recibimos el mismo grid para el siguiente movimeiento
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
	$json["grid"] = gridToString($grid);
	$json["result"] = true;
	return $response->withJson($json, 201);
});

// Aquí movemos el grid a la derecha
function moveRigthGrid(array $grid){
	foreach ($grid as $key => $numbers) {
		$numbers = moveZeroLeft($numbers);
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
		$grid[$key] = $numbers;
	}
	return $grid;
}

// Aquí movemos el grid a la izquierda
function moveLeftGrid(array $grid){
	foreach ($grid as $key => $numbers) {
		$numbers = moveZeroRigth($numbers);
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
		$grid[$key] = $numbers;
	}
	return $grid;
}

// Aquí movemos el grid hacia arriba
function moveUpGrid(array $grid){
	$grid = moveZeroDown($grid);
	for ($i=0; $i < count($grid); $i++) { 
		if($grid[0][$i] == $grid[1][$i]){
			$grid[0][$i] = $grid[0][$i] + $grid[1][$i];
			$grid[1][$i] = $grid[2][$i];
			$grid[2][$i] = $grid[3][$i];
			$grid[3][$i] = 0;
		}
		if($grid[1][$i] == $grid[2][$i]){
			$grid[1][$i] = $grid[1][$i] + $grid[2][$i];
			$grid[2][$i] = $grid[3][$i];
			$grid[3][$i] = 0;
		}
		if($grid[2][$i] == $grid[3][$i]){
			$grid[2][$i] = $grid[2][$i] + $grid[3][$i];
			$grid[3][$i] = 0;
		}
	}
	return $grid;
}

// Aquí movemos el grid hacia abajo
function moveDownGrid(array $grid){
	$grid = moveZeroUp($grid);
	for ($i=0; $i < count($grid); $i++) { 
		if($grid[3][$i] == $grid[2][$i]){
			$grid[3][$i] = $grid[3][$i] + $grid[2][$i];
			$grid[2][$i] = $grid[1][$i];
			$grid[1][$i] = $grid[0][$i];
			$grid[0][$i] = 0;
		}
		if($grid[2][$i] == $grid[1][$i]){
			$grid[2][$i] = $grid[2][$i] + $grid[1][$i];
			$grid[1][$i] = $grid[0][$i];
			$grid[0][$i] = 0;
		}
		if($grid[1][$i] == $grid[0][$i]){
			$grid[1][$i] = $grid[1][$i] + $grid[0][$i];
			$grid[0][$i] = 0;
		}
	}
	return $grid;
}

// Movemos toodos los ceros hacia abajo
function moveZeroDown(array $grid){
	for ($i=0; $i < 4; $i++) { 
		$numbers = array_column($grid, $i);
		$columns = moveZeroRigth($numbers);

		$grid[0][$i] = $columns[0];
		$grid[1][$i] = $columns[1];
		$grid[2][$i] = $columns[2];
		$grid[3][$i] = $columns[3];
	}
	return $grid;
}

// Movemos toodos los ceros hacia arriba
function moveZeroUp(array $grid){
	for ($i=0; $i < 4; $i++) { 
		$numbers = array_column($grid, $i);
		$columns = moveZeroLeft($numbers);

		$grid[0][$i] = $columns[0];
		$grid[1][$i] = $columns[1];
		$grid[2][$i] = $columns[2];
		$grid[3][$i] = $columns[3];
	}
	return $grid;
}

// Movemos toodos los ceros hacia la derecha
function moveZeroRigth(array $numbers){
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

// Movemos toodos los ceros hacia la izquierda
function moveZeroLeft(array $numbers){
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


// Convertimos el grid a string
function gridToString($grid){
	foreach ($grid as $key => $numbers) {
		$grid[$key] = implode(" ", $numbers);
	}
	return implode("\r\n", $grid);
}

// Convertimos el grid a array
function gridToArray(array $grid){
	$result = array();
	foreach ($grid as $key => $line) {
		$result[] = explode(" ", $line);
	}
	return $result;
}