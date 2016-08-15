<?php

 $app->get('/', [
 	'view' => 'pages/home',
 	'defaults' => []
 ]);

 $app->get('/test/{name}', [
 	'view' => 'pages/test',
	'defaults' => ['name' => 'josel'],
 	'action' => 'TestController@index'
 ]);

$app->get('/asdf', [
	'_controller' => '\App\Http\Controllers\TestController::index'
]);